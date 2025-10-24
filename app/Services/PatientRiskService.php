<?php

namespace App\Services;

use App\Models\PatientRisk;
use App\Models\Patient;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PatientRiskService
{
    /**
     * Create a new patient risk assessment
     */
    public function create(array $data, Patient $patient): PatientRisk
    {
        try {
            DB::beginTransaction();

            $filePath = null;
            $fileName = null;

            if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
                $filePath = $data['attachment']->store('patient-risks', 'public');
                $fileName = $data['attachment']->getClientOriginalName();
            }

            $patientRisk = $patient->risks()->create([
                'risk_type' => $data['risk_type'],
                'title' => $data['title'] ?? $data['risk_type'],
                'severity' => $data['severity'],
                'description' => $data['description'],
                'status' => $data['status'] ?? 'active',
                'identified_date' => $data['identified_date'] ?? now(),
                'review_date' => $data['review_date'] ?? null,
                'identified_by' => $data['identified_by'] ?? auth()->user()?->name ?? 'System',
                'management_plan' => $data['management_plan'] ?? null,
                'notes' => $data['notes'] ?? null,
                'requires_alert' => $data['requires_alert'] ?? true,
                'file_path' => $filePath,
                'file_name' => $fileName,
            ]);

            // Log the activity
            Log::info('Patient risk assessment created', [
                'patient_risk_id' => $patientRisk->id,
                'patient_id' => $patient->id,
                'doctor_id' => auth()->user()->doctor?->id ?? null,
                'risk_type' => $patientRisk->risk_type,
                'severity' => $patientRisk->severity,
            ]);

            DB::commit();
            return $patientRisk;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create patient risk assessment', [
                'patient_id' => $patient->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing patient risk assessment
     */
    public function update(PatientRisk $patientRisk, array $data): PatientRisk
    {
        try {
            DB::beginTransaction();

            // Handle file upload if new attachment provided
            if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
                // Delete old file if exists
                if ($patientRisk->file_path && Storage::disk('public')->exists($patientRisk->file_path)) {
                    Storage::disk('public')->delete($patientRisk->file_path);
                }

                $filePath = $data['attachment']->store('patient-risks', 'public');
                $fileName = $data['attachment']->getClientOriginalName();

                $data['file_path'] = $filePath;
                $data['file_name'] = $fileName;
            }

            // Update requires_alert based on severity if not explicitly set
            if (isset($data['severity']) && !isset($data['requires_alert'])) {
                $data['requires_alert'] = in_array($data['severity'], ['high', 'critical']);
            }

            $patientRisk->update($data);

            // Log the activity
            Log::info('Patient risk assessment updated', [
                'patient_risk_id' => $patientRisk->id,
                'patient_id' => $patientRisk->patient_id,
                'doctor_id' => auth()->user()->doctor?->id ?? null,
                'risk_type' => $patientRisk->risk_type,
                'severity' => $patientRisk->severity,
            ]);

            DB::commit();
            return $patientRisk->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update patient risk assessment', [
                'patient_risk_id' => $patientRisk->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a patient risk assessment
     */
    public function delete(PatientRisk $patientRisk): bool
    {
        try {
            DB::beginTransaction();

            Log::info('Deleting patient risk', [
                'id' => $patientRisk->id,
                'patient_id' => $patientRisk->patient_id,
                'file_path' => $patientRisk->file_path,
            ]);

            // Delete associated file if exists
            if ($patientRisk->file_path && Storage::disk('public')->exists($patientRisk->file_path)) {
                Storage::disk('public')->delete($patientRisk->file_path);
            }

            $result = $patientRisk->delete();

            Log::info('Patient risk delete result', [
                'id' => $patientRisk->id,
                'result' => $result,
            ]);

            // Log the activity
            Log::info('Patient risk assessment deleted', [
                'patient_risk_id' => $patientRisk->id,
                'patient_id' => $patientRisk->patient_id,
                'doctor_id' => auth()->user()->doctor?->id ?? null,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete patient risk assessment', [
                'patient_risk_id' => $patientRisk->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get patient risk assessments with optional filtering
     */
    public function getPatientRiskAssessments(Patient $patient, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $patient->risks()->with('doctor');

        // Apply filters
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['severity']) && $filters['severity']) {
            $query->where('severity', $filters['severity']);
        }

        if (isset($filters['risk_type']) && $filters['risk_type']) {
            $query->where('risk_type', $filters['risk_type']);
        }

        if (isset($filters['requires_alert']) && $filters['requires_alert'] !== '') {
            $query->where('requires_alert', $filters['requires_alert'] === 'true');
        }

        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->where('identified_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->where('identified_date', '<=', $filters['date_to']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('management_plan', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('identified_date', 'desc')->paginate(15);
    }

    /**
     * Get patient risk statistics
     */
    public function getPatientRiskStatistics(Patient $patient): array
    {
        $totalRisks = $patient->risks()->count();
        $activeRisks = $patient->risks()->where('status', 'active')->count();
        $criticalRisks = $patient->risks()->where('severity', 'critical')->count();
        $highRisks = $patient->risks()->where('severity', 'high')->count();

        $severityCounts = $patient->risks()
            ->select('severity', DB::raw('count(*) as count'))
            ->groupBy('severity')
            ->get()
            ->pluck('count', 'severity')
            ->toArray();

        $riskTypes = $patient->risks()
            ->select('risk_type', DB::raw('count(*) as count'))
            ->groupBy('risk_type')
            ->get()
            ->pluck('count', 'risk_type')
            ->toArray();

        return [
            'total' => $totalRisks,
            'active' => $activeRisks,
            'critical' => $criticalRisks,
            'high' => $highRisks,
            'severity_counts' => $severityCounts,
            'risk_types' => $riskTypes,
        ];
    }

    /**
     * Get critical risk assessments for a patient
     */
    public function getCriticalRisks(Patient $patient): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->risks()
            ->whereIn('severity', ['high', 'critical'])
            ->where('status', 'active')
            ->orderBy('assessment_date', 'desc')
            ->get();
    }

    /**
     * Get upcoming risk reviews
     */
    public function getUpcomingReviews(Patient $patient, int $days = 30): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->risks()
            ->where('review_date', '<=', now()->addDays($days))
            ->where('review_date', '>=', now())
            ->where('status', 'active')
            ->orderBy('review_date', 'asc')
            ->get();
    }

    /**
     * Assess overall patient risk level
     */
    public function assessOverallRisk(Patient $patient): array
    {
        $risks = $patient->risks()->where('status', 'active')->get();

        $severityWeights = [
            'low' => 1,
            'moderate' => 2,
            'high' => 3,
            'critical' => 4,
        ];

        $totalScore = 0;
        $maxScore = 0;
        $criticalCount = 0;
        $highCount = 0;

        foreach ($risks as $risk) {
            $weight = $severityWeights[$risk->severity] ?? 1;
            $totalScore += $weight;
            $maxScore += 4; // Maximum weight

            if ($risk->severity === 'critical') {
                $criticalCount++;
            } elseif ($risk->severity === 'high') {
                $highCount++;
            }
        }

        $riskPercentage = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;

        $overallLevel = 'low';
        if ($criticalCount > 0 || $riskPercentage >= 75) {
            $overallLevel = 'critical';
        } elseif ($highCount > 0 || $riskPercentage >= 50) {
            $overallLevel = 'high';
        } elseif ($riskPercentage >= 25) {
            $overallLevel = 'moderate';
        }

        return [
            'overall_level' => $overallLevel,
            'risk_percentage' => round($riskPercentage, 1),
            'total_risks' => $risks->count(),
            'critical_count' => $criticalCount,
            'high_count' => $highCount,
            'assessment_date' => now(),
        ];
    }

    /**
     * Get risk types
     */
    public function getRiskTypes(): array
    {
        return [
            'cardiovascular' => 'Cardiovascular',
            'diabetes' => 'Diabetes',
            'cancer' => 'Cancer',
            'mental_health' => 'Mental Health',
            'infection' => 'Infection Risk',
            'fall_risk' => 'Fall Risk',
            'medication' => 'Medication Risk',
            'lifestyle' => 'Lifestyle',
            'genetic' => 'Genetic',
            'other' => 'Other',
        ];
    }

    /**
     * Get severity options
     */
    public function getSeverityOptions(): array
    {
        return [
            'low' => 'Low',
            'moderate' => 'Moderate',
            'high' => 'High',
            'critical' => 'Critical',
        ];
    }

    /**
     * Get status options
     */
    public function getStatusOptions(): array
    {
        return [
            'active' => 'Active',
            'resolved' => 'Resolved',
            'monitoring' => 'Monitoring',
            'inactive' => 'Inactive',
        ];
    }

    /**
     * Get risks by severity
     */
    public function getRisksBySeverity(Patient $patient, string $severity): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->risks()
            ->where('severity', $severity)
            ->orderBy('identified_date', 'desc')
            ->get();
    }

    /**
     * Get alert risks (high and critical severity)
     */
    public function getAlertRisks(Patient $patient): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->risks()
            ->whereIn('severity', ['high', 'critical'])
            ->where('status', 'active')
            ->where('requires_alert', true)
            ->orderBy('identified_date', 'desc')
            ->get();
    }

    /**
     * Update risk status
     */
    public function updateStatus(PatientRisk $patientRisk, string $status): PatientRisk
    {
        try {
            $patientRisk->update(['status' => $status]);

            // Log the activity
            Log::info('Patient risk status updated', [
                'patient_risk_id' => $patientRisk->id,
                'patient_id' => $patientRisk->patient_id,
                'old_status' => $patientRisk->getOriginal('status'),
                'new_status' => $status,
                'doctor_id' => auth()->user()->doctor?->id ?? null,
            ]);

            return $patientRisk->fresh();

        } catch (\Exception $e) {
            Log::error('Failed to update patient risk status', [
                'patient_risk_id' => $patientRisk->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
