<?php

namespace App\Services;

use App\Models\LabResult;
use App\Models\Patient;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LabResultService
{
    /**
     * Create a new lab result for a patient
     */
    public function create(array $data, Patient $patient): LabResult
    {
        try {
            DB::beginTransaction();

            $filePath = null;
            $fileName = null;

            if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
                $filePath = $data['attachment']->store('lab-results', 'public');
                $fileName = $data['attachment']->getClientOriginalName();
            }

            $labResult = $patient->labResults()->create([
                'test_name' => $data['test_name'],
                'test_code' => $data['test_code'] ?? null,
                'category' => $data['category'],
                'test_date' => $data['test_date'],
                'result_date' => $data['result_date'] ?? null,
                'result_value' => $data['result_value'],
                'unit' => $data['unit'] ?? null,
                'reference_range' => $data['reference_range'] ?? null,
                'status' => $data['status'] ?? 'pending',
                'interpretation' => $data['interpretation'] ?? null,
                'notes' => $data['notes'] ?? null,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'ordered_by' => $data['ordered_by'] ?? null,
                'performed_by' => $data['performed_by'] ?? null,
            ]);

            // Log the activity
            Log::info('Lab result created', [
                'lab_result_id' => $labResult->id,
                'patient_id' => $patient->id,
                'doctor_id' => auth()->user()->doctor->id,
                'test_name' => $labResult->test_name,
            ]);

            DB::commit();
            return $labResult;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create lab result', [
                'patient_id' => $patient->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing lab result
     */
    public function update(LabResult $labResult, array $data): LabResult
    {
        try {
            DB::beginTransaction();

            // Handle file upload if new attachment provided
            if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
                // Delete old file if exists
                if ($labResult->file_path && Storage::disk('public')->exists($labResult->file_path)) {
                    Storage::disk('public')->delete($labResult->file_path);
                }

                $filePath = $data['attachment']->store('lab-results', 'public');
                $fileName = $data['attachment']->getClientOriginalName();

                $data['file_path'] = $filePath;
                $data['file_name'] = $fileName;
            }

            $labResult->update($data);

            // Log the activity
            Log::info('Lab result updated', [
                'lab_result_id' => $labResult->id,
                'patient_id' => $labResult->patient_id,
                'doctor_id' => auth()->user()->doctor->id,
                'test_name' => $labResult->test_name,
            ]);

            DB::commit();
            return $labResult->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update lab result', [
                'lab_result_id' => $labResult->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a lab result
     */
    public function delete(LabResult $labResult): bool
    {
        try {
            DB::beginTransaction();

            // Delete associated file if exists
            if ($labResult->file_path && Storage::disk('public')->exists($labResult->file_path)) {
                Storage::disk('public')->delete($labResult->file_path);
            }

            $labResult->delete();

            // Log the activity
            Log::info('Lab result deleted', [
                'lab_result_id' => $labResult->id,
                'patient_id' => $labResult->patient_id,
                'doctor_id' => auth()->user()->doctor->id,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete lab result', [
                'lab_result_id' => $labResult->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get lab results for a patient with optional filtering
     */
    public function getPatientLabResults(Patient $patient, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $patient->labResults()->with('patient');

        // Apply filters
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['category']) && $filters['category']) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->where('test_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->where('test_date', '<=', $filters['date_to']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('test_name', 'like', "%{$search}%")
                  ->orWhere('result_value', 'like', "%{$search}%")
                  ->orWhere('interpretation', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('test_date', 'desc')->paginate(15);
    }

    /**
     * Get lab result statistics for a patient
     */
    public function getLabResultStatistics(Patient $patient): array
    {
        $total = $patient->labResults()->count();
        $normal = $patient->labResults()->where('status', 'normal')->count();
        $abnormal = $patient->labResults()->where('status', 'abnormal')->count();
        $pending = $patient->labResults()->where('status', 'pending')->count();
        $critical = $patient->labResults()->where('status', 'critical')->count();

        return [
            'total' => $total,
            'normal' => $normal,
            'abnormal' => $abnormal,
            'pending' => $pending,
            'critical' => $critical,
        ];
    }

    /**
     * Get critical lab results for a patient
     */
    public function getCriticalResults(Patient $patient): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->labResults()
            ->where('status', 'critical')
            ->orderBy('test_date', 'desc')
            ->get();
    }

    /**
     * Get recent lab results for a patient
     */
    public function getRecentResults(Patient $patient, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->labResults()
            ->orderBy('test_date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get test categories
     */
    public function getTestCategories(): array
    {
        return [
            'hematology' => 'Hematology',
            'chemistry' => 'Chemistry',
            'microbiology' => 'Microbiology',
            'immunology' => 'Immunology',
            'urinalysis' => 'Urinalysis',
            'parasitology' => 'Parasitology',
            'other' => 'Other',
        ];
    }

    /**
     * Get status options
     */
    public function getStatusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'critical' => 'Critical',
            'reviewed' => 'Reviewed',
        ];
    }
}
