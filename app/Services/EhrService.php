<?php

namespace App\Services;

use App\Models\EhrRecord;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class EhrService
{
    /**
     * Get EHR records for a patient with optional filtering
     */
    public function getPatientRecords(Patient $patient, array $filters = []): LengthAwarePaginator
    {
        $query = $patient->ehrRecords()->with(['creator', 'appointment', 'reviewer']);

        // Apply type filter
        if (!empty($filters['type']) && $filters['type'] !== 'all') {
            $query->where('record_type', $filters['type']);
        }

        // Apply status filter
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        // Apply date range filter
        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        // Apply search filter
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('notes', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * Create a new EHR record
     */
    public function createRecord(array $data, Patient $patient, User $createdBy): EhrRecord
    {
        try {
            $record = new EhrRecord($data);
            $record->patient_id = $patient->id;
            $record->created_by = $createdBy->id;
            $record->created_by_role = $this->getUserRole($createdBy);
            $record->status = $this->determineInitialStatus($record->created_by_role);

            if ($record->status === EhrRecord::STATUS_APPROVED) {
                $record->reviewed_by = $createdBy->id;
                $record->reviewed_at = now();
            } else {
                $record->reviewed_by = null;
                $record->reviewed_at = null;
                $record->review_notes = null;
            }

            $record->save();

            // Handle file uploads if any
            if (isset($data['attachments']) && is_array($data['attachments'])) {
                $this->handleFileUploads($record, $data['attachments']);
            }

            Log::info('EHR record created', [
                'record_id' => $record->id,
                'patient_id' => $patient->id,
                'created_by' => $createdBy->id,
                'record_type' => $record->record_type,
                'status' => $record->status
            ]);

            return $record->load(['creator', 'appointment', 'reviewer']);

        } catch (\Exception $e) {
            Log::error('Failed to create EHR record', [
                'patient_id' => $patient->id,
                'created_by' => $createdBy->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            throw $e;
        }
    }

    /**
     * Update an EHR record
     */
    public function updateRecord(EhrRecord $record, array $data): EhrRecord
    {
        $record->update($data);

        // Handle file uploads if any
        if (isset($data['attachments']) && is_array($data['attachments'])) {
            $this->handleFileUploads($record, $data['attachments']);
        }

        return $record->load(['creator', 'appointment', 'reviewer']);
    }

    /**
     * Update EHR review status and notes
     */
    public function reviewRecord(EhrRecord $record, User $reviewer, string $status, ?string $notes = null): EhrRecord
    {
        try {
            if (!in_array($status, [EhrRecord::STATUS_APPROVED, EhrRecord::STATUS_FLAGGED], true)) {
                throw new \InvalidArgumentException('Invalid review status provided.');
            }

            $oldStatus = $record->status;
            $record->status = $status;
            $record->reviewed_by = $reviewer->id;
            $record->review_notes = $notes;
            $record->reviewed_at = now();
            $record->save();

            Log::info('EHR record reviewed', [
                'record_id' => $record->id,
                'patient_id' => $record->patient_id,
                'reviewed_by' => $reviewer->id,
                'old_status' => $oldStatus,
                'new_status' => $status,
                'has_notes' => !empty($notes)
            ]);

            return $record->load(['creator', 'appointment', 'reviewer']);

        } catch (\Exception $e) {
            Log::error('Failed to review EHR record', [
                'record_id' => $record->id,
                'reviewer_id' => $reviewer->id,
                'status' => $status,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Delete an EHR record
     */
    public function deleteRecord(EhrRecord $record): bool
    {
        // Delete associated files
        if ($record->attachments) {
            foreach ($record->attachments as $file) {
                Storage::disk('public')->delete($file['path']);
            }
        }

        return $record->delete();
    }

    /**
     * Sync appointment data to EHR records
     */
    public function syncAppointmentToEhr(Appointment $appointment): ?EhrRecord
    {
        // Check if EHR record already exists for this appointment
        $existingRecord = EhrRecord::where('appointment_id', $appointment->id)->first();
        if ($existingRecord) {
            return $existingRecord;
        }

        // Create EHR record from appointment
        $recordData = [
            'record_type' => 'appointment',
            'title' => 'Appointment Completed',
            'description' => "Appointment on {$appointment->appointment_date->format('M d, Y')} at {$appointment->appointment_time}. Reason: {$appointment->reason}",
            'appointment_id' => $appointment->id,
        ];

        // Determine creator based on appointment provider
        $creator = $appointment->doctor?->user ?? $appointment->midwife?->user ?? $appointment->bhw?->user ?? null;
        if (!$creator) {
            Log::warning("No creator found for appointment {$appointment->id}");
            return null;
        }

        return $this->createRecord($recordData, $appointment->patient, $creator);
    }

    /**
     * Handle file uploads for EHR record
     */
    private function handleFileUploads(EhrRecord $record, array $files): void
    {
        $uploadedFiles = $record->attachments ?? [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = $file->store('ehr_attachments', 'public');
                $uploadedFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_at' => now()->toISOString(),
                ];
            }
        }

        $record->update(['attachments' => $uploadedFiles]);
    }

    /**
     * Determine initial status based on creator role
     */
    private function determineInitialStatus(string $creatorRole): string
    {
        return match ($creatorRole) {
            'doctor' => EhrRecord::STATUS_APPROVED,
            'midwife', 'bhw', 'patient' => EhrRecord::STATUS_PENDING_REVIEW,
            default => EhrRecord::STATUS_PENDING_REVIEW,
        };
    }

    /**
     * Get user role for EHR record
     */
    private function getUserRole(User $user): string
    {
        if ($user->hasRole('doctor')) {
            return 'doctor';
        } elseif ($user->hasRole('midwife')) {
            return 'midwife';
        } elseif ($user->hasRole('bhw')) {
            return 'bhw';
        } elseif ($user->hasRole('patient')) {
            return 'patient';
        }

        return 'system';
    }

    /**
     * Get EHR statistics for a patient
     */
    public function getPatientStatistics(Patient $patient): array
    {
        $records = $patient->ehrRecords();

        return [
            'total_records' => $records->count(),
            'records_by_type' => $records->selectRaw('record_type, COUNT(*) as count')
                ->groupBy('record_type')
                ->pluck('count', 'record_type')
                ->toArray(),
            'records_by_role' => $records->selectRaw('created_by_role, COUNT(*) as count')
                ->groupBy('created_by_role')
                ->pluck('count', 'created_by_role')
                ->toArray(),
            'recent_records' => $records->recent()->count(),
        ];
    }

    /**
     * Get analytics data for doctor's EHR dashboard
     */
    public function getDoctorAnalytics(int $doctorId): array
    {
        try {
            // Get all patients for this doctor
            $patientIds = DB::table('appointments')
                ->where('doctor_id', $doctorId)
                ->distinct()
                ->pluck('patient_id');

            if ($patientIds->isEmpty()) {
                return $this->getEmptyAnalytics();
            }

            // Get EHR records for these patients
            $records = EhrRecord::whereIn('patient_id', $patientIds);

            // Basic statistics
            $totalRecords = $records->count();
            $pendingRecords = (clone $records)->where('status', EhrRecord::STATUS_PENDING_REVIEW)->count();
            $approvedRecords = (clone $records)->where('status', EhrRecord::STATUS_APPROVED)->count();
            $flaggedRecords = (clone $records)->where('status', EhrRecord::STATUS_FLAGGED)->count();

            // Records by type
            $recordsByType = (clone $records)->selectRaw('record_type, COUNT(*) as count')
                ->groupBy('record_type')
                ->pluck('count', 'record_type')
                ->toArray();

            // Records by creator role
            $recordsByRole = (clone $records)->selectRaw('created_by_role, COUNT(*) as count')
                ->groupBy('created_by_role')
                ->pluck('count', 'created_by_role')
                ->toArray();

            // Monthly trends (last 6 months)
            $monthlyTrends = (clone $records)->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();

            // Recent activity (last 30 days)
            $recentActivity = (clone $records)->where('created_at', '>=', now()->subDays(30))->count();

            // Approval rate
            $totalReviewed = $approvedRecords + $flaggedRecords;
            $approvalRate = $totalReviewed > 0 ? round(($approvedRecords / $totalReviewed) * 100, 1) : 0;

            return [
                'total_records' => $totalRecords,
                'pending_records' => $pendingRecords,
                'approved_records' => $approvedRecords,
                'flagged_records' => $flaggedRecords,
                'records_by_type' => $recordsByType,
                'records_by_role' => $recordsByRole,
                'monthly_trends' => $monthlyTrends,
                'recent_activity' => $recentActivity,
                'approval_rate' => $approvalRate,
                'total_patients' => $patientIds->count(),
            ];

        } catch (\Exception $e) {
            Log::error('Error getting doctor analytics', [
                'doctor_id' => $doctorId,
                'error' => $e->getMessage()
            ]);

            return $this->getEmptyAnalytics();
        }
    }

    /**
     * Get empty analytics structure
     */
    private function getEmptyAnalytics(): array
    {
        return [
            'total_records' => 0,
            'pending_records' => 0,
            'approved_records' => 0,
            'flagged_records' => 0,
            'records_by_type' => [],
            'records_by_role' => [],
            'monthly_trends' => [],
            'recent_activity' => 0,
            'approval_rate' => 0,
            'total_patients' => 0,
        ];
    }

    /**
     * Export patient EHR records
     */
    public function exportPatientRecords(Patient $patient, array $filters = []): string
    {
        $records = $this->getPatientRecords($patient, $filters)->get();

        $exportData = [
            'patient' => [
                'name' => $patient->full_name,
                'id' => $patient->id,
                'exported_at' => now()->toISOString(),
            ],
            'records' => $records->map(function($record) {
                return [
                    'id' => $record->id,
                    'type' => $record->record_type_display,
                    'title' => $record->title,
                    'description' => $record->description,
                    'notes' => $record->notes,
                    'created_by' => $record->created_by_role_display,
                    'creator_name' => $record->creator_name,
                    'created_at' => $record->created_at->toISOString(),
                    'attachments_count' => count($record->attachments ?? []),
                ];
            }),
        ];

        return json_encode($exportData, JSON_PRETTY_PRINT);
    }

    /**
     * Generate PDF for patient EHR records with enhanced formatting
     */
    public function generatePatientRecordsPdf(Patient $patient, array $filters = []): string
    {
        $records = $this->getPatientRecords($patient, $filters)->get();

        // Get additional patient data for comprehensive PDF
        $patient->load([
            'diagnoses' => function($query) {
                $query->latest()->take(10);
            },
            'prescriptions' => function($query) {
                $query->with('medications')->latest()->take(5);
            },
            'labResults' => function($query) {
                $query->latest()->take(10);
            },
            'allergies' => function($query) {
                $query->active();
            },
            'medications' => function($query) {
                $query->active();
            }
        ]);

        $html = $this->buildEnhancedPdfHtml($patient, $records);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions(['defaultFont' => 'Arial']);

        return $pdf->download('ehr_records_' . $patient->id . '_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Generate PDF for single EHR record
     */
    public function generateSingleRecordPdf(EhrRecord $record): string
    {
        $html = $this->buildSingleRecordPdfHtml($record);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('ehr_record_' . $record->id . '_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Build enhanced HTML for patient records PDF with comprehensive data
     */
    private function buildEnhancedPdfHtml(Patient $patient, Collection $records): string
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Comprehensive EHR Summary - ' . $patient->full_name . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; line-height: 1.4; }
                .header { text-align: center; border-bottom: 3px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 20px; }
                .patient-info { margin-bottom: 30px; background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid #2563eb; }
                .section { margin-bottom: 25px; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
                .section-header { background: #2563eb; color: white; padding: 12px 15px; font-weight: bold; font-size: 14px; }
                .section-content { padding: 15px; }
                .record { margin-bottom: 15px; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; background: #fafafa; }
                .record-header { font-weight: bold; color: #1f2937; margin-bottom: 8px; font-size: 13px; }
                .record-meta { font-size: 11px; color: #6b7280; margin-bottom: 8px; }
                .record-content { margin-bottom: 8px; }
                .attachments { margin-top: 10px; padding-top: 10px; border-top: 1px solid #e5e7eb; }
                .attachment { font-size: 11px; color: #374151; margin-bottom: 3px; }
                .summary-grid { display: table; width: 100%; margin-bottom: 20px; }
                .summary-row { display: table-row; }
                .summary-cell { display: table-cell; padding: 8px; border: 1px solid #e5e7eb; }
                .summary-header { background: #f3f4f6; font-weight: bold; }
                .status-approved { color: #059669; font-weight: bold; }
                .status-flagged { color: #dc2626; font-weight: bold; }
                .status-pending { color: #d97706; font-weight: bold; }
                .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 20px; }
                .vital-signs { background: #ecfdf5; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
                .alert { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 10px; margin-bottom: 15px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1 style="color: #1f2937; margin: 0; font-size: 24px;">Electronic Health Records</h1>
                <h2 style="color: #374151; margin: 10px 0; font-size: 18px;">' . htmlspecialchars($patient->full_name) . '</h2>
                <p style="color: #6b7280; margin: 5px 0;">Comprehensive Medical Summary</p>
                <p style="color: #6b7280; margin: 5px 0; font-size: 11px;">Generated on ' . now()->format('F d, Y \a\t g:i A') . '</p>
            </div>

            <div class="patient-info">
                <div style="display: table; width: 100%;">
                    <div style="display: table-row;">
                        <div style="display: table-cell; width: 25%; padding: 5px;"><strong>Patient ID:</strong> ' . $patient->id . '</div>
                        <div style="display: table-cell; width: 25%; padding: 5px;"><strong>Date of Birth:</strong> ' . ($patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'Not specified') . '</div>
                        <div style="display: table-cell; width: 25%; padding: 5px;"><strong>Age:</strong> ' . ($patient->date_of_birth ? $patient->date_of_birth->age : 'N/A') . ' years</div>
                        <div style="display: table-cell; width: 25%; padding: 5px;"><strong>Total Records:</strong> ' . $records->count() . '</div>
                    </div>
                </div>
            </div>';

        // Active Problems/Allergies Section
        if ($patient->allergies->count() > 0 || $patient->diagnoses->count() > 0) {
            $html .= '<div class="section"><div class="section-header">Active Problems & Allergies</div><div class="section-content">';

            if ($patient->allergies->count() > 0) {
                $html .= '<div class="alert"><strong>Allergies:</strong> ';
                $html .= $patient->allergies->pluck('allergen')->join(', ');
                $html .= '</div>';
            }

            if ($patient->diagnoses->count() > 0) {
                $html .= '<div><strong>Active Diagnoses:</strong><br>';
                foreach ($patient->diagnoses as $diagnosis) {
                    $html .= '• ' . htmlspecialchars($diagnosis->diagnosis_name) . ' (' . $diagnosis->created_at->format('M d, Y') . ')<br>';
                }
                $html .= '</div>';
            }

            $html .= '</div></div>';
        }

        // Current Medications Section
        if ($patient->medications->count() > 0) {
            $html .= '<div class="section"><div class="section-header">Current Medications</div><div class="section-content">';
            foreach ($patient->medications as $medication) {
                $html .= '<div>• <strong>' . htmlspecialchars($medication->medication_name) . '</strong> - ' . htmlspecialchars($medication->dosage) . ' (' . htmlspecialchars($medication->frequency) . ')</div>';
            }
            $html .= '</div></div>';
        }

        // Recent Lab Results Section
        if ($patient->labResults->count() > 0) {
            $html .= '<div class="section"><div class="section-header">Recent Lab Results</div><div class="section-content">';
            $html .= '<div class="summary-grid">';
            $html .= '<div class="summary-row summary-header"><div class="summary-cell">Test</div><div class="summary-cell">Value</div><div class="summary-cell">Normal Range</div><div class="summary-cell">Date</div></div>';

            foreach ($patient->labResults->take(5) as $labResult) {
                $statusClass = match(strtolower($labResult->status ?? '')) {
                    'normal' => 'status-approved',
                    'abnormal', 'critical' => 'status-flagged',
                    default => ''
                };

                $html .= '<div class="summary-row">';
                $html .= '<div class="summary-cell">' . htmlspecialchars($labResult->test_name) . '</div>';
                $html .= '<div class="summary-cell ' . $statusClass . '">' . htmlspecialchars($labResult->result_value . ' ' . $labResult->unit) . '</div>';
                $html .= '<div class="summary-cell">' . htmlspecialchars($labResult->normal_range ?? 'N/A') . '</div>';
                $html .= '<div class="summary-cell">' . $labResult->test_date->format('M d, Y') . '</div>';
                $html .= '</div>';
            }
            $html .= '</div></div></div>';
        }

        // EHR Records Section
        $html .= '<div class="section"><div class="section-header">Electronic Health Records</div><div class="section-content">';

        if ($records->isEmpty()) {
            $html .= '<p>No EHR records found for this patient.</p>';
        } else {
            foreach ($records as $record) {
                $statusClass = match($record->status) {
                    EhrRecord::STATUS_APPROVED => 'status-approved',
                    EhrRecord::STATUS_FLAGGED => 'status-flagged',
                    EhrRecord::STATUS_PENDING_REVIEW => 'status-pending',
                    default => ''
                };

                $html .= '<div class="record">
                    <div class="record-header">' . htmlspecialchars($record->title) . '</div>
                    <div class="record-meta">
                        Type: ' . $record->record_type_display . ' |
                        Created: ' . $record->created_at->format('M d, Y') . ' |
                        By: ' . $record->created_by_role_display . ' |
                        Status: <span class="' . $statusClass . '">' . $record->status_display . '</span>
                    </div>
                    <div class="record-content">' . nl2br(htmlspecialchars($record->description ?? 'No description provided')) . '</div>';

                if ($record->notes) {
                    $html .= '<div><strong>Notes:</strong> ' . nl2br(htmlspecialchars($record->notes)) . '</div>';
                }

                if ($record->attachments && count($record->attachments) > 0) {
                    $html .= '<div class="attachments"><strong>Attachments:</strong><br>';
                    foreach ($record->attachments as $attachment) {
                        $html .= '<div class="attachment">• ' . htmlspecialchars($attachment['original_name']) . ' (' . number_format($attachment['size'] / 1024, 1) . ' KB)</div>';
                    }
                    $html .= '</div>';
                }

                if ($record->review_notes) {
                    $html .= '<div style="margin-top: 10px; padding: 8px; background: #f3f4f6; border-radius: 4px;"><strong>Review Notes:</strong><br>' . nl2br(htmlspecialchars($record->review_notes)) . '</div>';
                }

                $html .= '</div>';
            }
        }

        $html .= '</div></div>';

        $html .= '<div class="footer">
            <p><strong>Confidential Medical Information</strong></p>
            <p>This document contains confidential medical information and is intended for authorized healthcare providers only.</p>
            <p>Generated by Barangay Health Center Monitoring System on ' . now()->format('F d, Y \a\t g:i A') . '</p>
            <p>© ' . date('Y') . ' All rights reserved.</p>
        </div>
        </body>
        </html>';

        return $html;
    }



    /**
     * Build HTML for single record PDF
     */
    private function buildSingleRecordPdfHtml(EhrRecord $record): string
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>EHR Record - ' . $record->title . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
                .record-details { margin-bottom: 30px; }
                .detail-row { margin-bottom: 10px; }
                .detail-label { font-weight: bold; display: inline-block; width: 150px; }
                .content { margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; }
                .attachments { margin-top: 20px; }
                .attachment { margin-bottom: 5px; padding: 5px; background-color: #e9ecef; border-radius: 3px; }
                .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #666; border-top: 1px solid #ddd; padding-top: 20px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Electronic Health Record</h1>
                <h2>' . htmlspecialchars($record->title) . '</h2>
            </div>

            <div class="record-details">
                <div class="detail-row">
                    <span class="detail-label">Patient:</span>
                    ' . $record->patient->full_name . '
                </div>
                <div class="detail-row">
                    <span class="detail-label">Record Type:</span>
                    ' . $record->record_type_display . '
                </div>
                <div class="detail-row">
                    <span class="detail-label">Created By:</span>
                    ' . $record->created_by_role_display . '
                </div>
                <div class="detail-row">
                    <span class="detail-label">Created Date:</span>
                    ' . $record->created_at->format('M d, Y \a\t g:i A') . '
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    ' . $record->status_display . '
                </div>';

        if ($record->appointment) {
            $html .= '
                <div class="detail-row">
                    <span class="detail-label">Related Appointment:</span>
                    #' . $record->appointment->id . ' - ' . $record->appointment->appointment_date->format('M d, Y') . '
                </div>';
        }

        $html .= '
            </div>

            <div class="content">
                <strong>Description:</strong><br>
                ' . nl2br(htmlspecialchars($record->description ?? 'No description provided')) . '
            </div>';

        if ($record->attachments && count($record->attachments) > 0) {
            $html .= '<div class="attachments"><strong>Attachments:</strong>';
            foreach ($record->attachments as $attachment) {
                $html .= '<div class="attachment">' . htmlspecialchars($attachment['original_name']) . ' (' . number_format($attachment['size'] / 1024, 1) . ' KB)</div>';
            }
            $html .= '</div>';
        }

        if ($record->review_notes) {
            $html .= '
            <div class="content" style="margin-top: 20px; background-color: #d1ecf1;">
                <strong>Review Notes:</strong><br>
                ' . nl2br(htmlspecialchars($record->review_notes)) . '
            </div>';
        }

        $html .= '
            <div class="footer">
                <p>This document contains confidential medical information. Generated by Barangay Health Center Monitoring System.</p>
                <p>© ' . date('Y') . ' All rights reserved.</p>
            </div>
        </body>
        </html>';

        return $html;
    }
}
