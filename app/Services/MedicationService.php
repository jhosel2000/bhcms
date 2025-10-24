<?php

namespace App\Services;

use App\Models\Medication;
use App\Models\Patient;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MedicationService
{
    /**
     * Create a new medication for a patient
     */
    public function create(array $data, Patient $patient): Medication
    {
        try {
            DB::beginTransaction();

            $filePath = null;
            $fileName = null;

            if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
                $filePath = $data['attachment']->store('medications', 'public');
                $fileName = $data['attachment']->getClientOriginalName();
            }

            $medication = $patient->medications()->create([
                'medication_name' => $data['medication_name'],
                'dosage' => $data['dosage'],
                'frequency' => $data['frequency'],
                'route' => $data['route'] ?? 'oral',
                'status' => $data['status'] ?? 'active',
                'start_date' => $data['start_date'] ?? now(),
                'end_date' => $data['end_date'] ?? null,
                'prescribed_by' => $data['prescribed_by'] ?? auth()->user()->name,
                'indication' => $data['indication'] ?? null,
                'instructions' => $data['instructions'] ?? null,
                'notes' => $data['notes'] ?? null,
                'file_path' => $filePath,
                'file_name' => $fileName,
            ]);

            // Log the activity
            Log::info('Medication created', [
                'medication_id' => $medication->id,
                'patient_id' => $patient->id,
                'doctor_id' => auth()->user()->doctor->id,
                'medication_name' => $medication->medication_name,
            ]);

            DB::commit();
            return $medication;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create medication', [
                'patient_id' => $patient->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing medication
     */
    public function update(Medication $medication, array $data): Medication
    {
        try {
            DB::beginTransaction();

            $oldData = $medication->toArray();

            // Handle file upload
            if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
                // Delete old file if exists
                if ($medication->file_path) {
                    Storage::disk('public')->delete($medication->file_path);
                }
                $data['file_path'] = $data['attachment']->store('medications', 'public');
                $data['file_name'] = $data['attachment']->getClientOriginalName();
            } elseif (!isset($data['attachment'])) {
                // If no new file, keep existing
                unset($data['file_path'], $data['file_name']);
            }

            $medication->update($data);

            // Log the activity
            Log::info('Medication updated', [
                'medication_id' => $medication->id,
                'patient_id' => $medication->patient_id,
                'old_data' => $oldData,
                'new_data' => $data,
            ]);

            DB::commit();
            return $medication->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update medication', [
                'medication_id' => $medication->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a medication
     */
    public function delete(Medication $medication): bool
    {
        try {
            DB::beginTransaction();

            // Log the activity before deletion
            Log::info('Medication deleted', [
                'medication_id' => $medication->id,
                'patient_id' => $medication->patient_id,
                'medication_name' => $medication->medication_name,
            ]);

            $medication->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete medication', [
                'medication_id' => $medication->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get current medications for a patient
     */
    public function getCurrentMedications(Patient $patient): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->medications()
            ->where(function ($query) {
                $query->where('status', 'active')
                      ->orWhere(function ($q) {
                          $q->where('status', '!=', 'discontinued')
                            ->where('end_date', '>', now());
                      });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get medications by status
     */
    public function getByStatus(Patient $patient, string $status): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->medications()
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Search medications by name or indication
     */
    public function search(Patient $patient, string $query): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->medications()
            ->where(function ($q) use ($query) {
                $q->where('medication_name', 'like', "%{$query}%")
                  ->orWhere('indication', 'like', "%{$query}%")
                  ->orWhere('instructions', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check for potential drug interactions
     */
    public function checkInteractions(array $medicationNames): array
    {
        // This would integrate with a drug interaction API or database
        // For now, return empty array as placeholder
        $interactions = [];

        // Basic check for common interactions
        $dangerousCombinations = [
            ['warfarin', 'aspirin'],
            ['lisinopril', 'potassium'],
            ['metformin', 'alcohol'],
        ];

        foreach ($dangerousCombinations as $combination) {
            $found = array_filter($medicationNames, function ($med) use ($combination) {
                return stripos($med, $combination[0]) !== false ||
                       stripos($med, $combination[1]) !== false;
            });

            if (count($found) >= 2) {
                $interactions[] = [
                    'medications' => array_intersect($medicationNames, $found),
                    'severity' => 'high',
                    'description' => 'Potential drug interaction detected',
                ];
            }
        }

        return $interactions;
    }

    /**
     * Get medications expiring soon
     */
    public function getExpiringSoon(Patient $patient, int $days = 30): \Illuminate\Database\Eloquent\Collection
    {
        $cutoffDate = now()->addDays($days);

        return $patient->medications()
            ->whereNotNull('end_date')
            ->where('end_date', '<=', $cutoffDate)
            ->where('end_date', '>', now())
            ->where('status', 'active')
            ->orderBy('end_date', 'asc')
            ->get();
    }

    /**
     * Get medications expiring within specified days (alias for getExpiringSoon)
     */
    public function getExpiringMedications(Patient $patient, ?int $days = 30): \Illuminate\Database\Eloquent\Collection
    {
        return $this->getExpiringSoon($patient, $days);
    }

    /**
     * Get medication statistics for a patient
     */
    public function getMedicationStatistics(Patient $patient): array
    {
        $totalMedications = $patient->medications()->count();
        $activeMedications = $patient->medications()->where('status', 'active')->count();
        $currentMedications = $this->getCurrentMedications($patient)->count();
        $expiringSoon = $this->getExpiringSoon($patient)->count();

        $routes = $patient->medications()
            ->where('status', 'active')
            ->selectRaw('route, COUNT(*) as count')
            ->groupBy('route')
            ->pluck('count', 'route')
            ->toArray();

        $frequencies = $patient->medications()
            ->where('status', 'active')
            ->selectRaw('frequency, COUNT(*) as count')
            ->groupBy('frequency')
            ->pluck('count', 'frequency')
            ->toArray();

        return [
            'total' => $totalMedications,
            'active' => $activeMedications,
            'current' => $currentMedications,
            'expiring_soon' => $expiringSoon,
            'by_route' => $routes,
            'by_frequency' => $frequencies,
        ];
    }

    /**
     * Get available medication routes
     */
    public function getRoutes(): array
    {
        return [
            'oral' => 'Oral',
            'topical' => 'Topical',
            'injection' => 'Injection',
            'inhalation' => 'Inhalation',
            'rectal' => 'Rectal',
            'vaginal' => 'Vaginal',
            'ophthalmic' => 'Ophthalmic',
            'otic' => 'Otic',
            'nasal' => 'Nasal',
            'transdermal' => 'Transdermal',
        ];
    }

    /**
     * Get available medication frequencies
     */
    public function getFrequencies(): array
    {
        return [
            'once_daily' => 'Once Daily',
            'twice_daily' => 'Twice Daily',
            'three_times_daily' => 'Three Times Daily',
            'four_times_daily' => 'Four Times Daily',
            'every_6_hours' => 'Every 6 Hours',
            'every_8_hours' => 'Every 8 Hours',
            'every_12_hours' => 'Every 12 Hours',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'as_needed' => 'As Needed',
            'other' => 'Other',
        ];
    }

    /**
     * Get available medication statuses
     */
    public function getStatuses(): array
    {
        return [
            'active' => 'Active',
            'discontinued' => 'Discontinued',
            'completed' => 'Completed',
            'on_hold' => 'On Hold',
        ];
    }

    /**
     * Check for potential drug interactions for a patient
     */
    public function checkDrugInteractions(Patient $patient, array $medicationNames): array
    {
        $currentMedications = $this->getCurrentMedications($patient)->pluck('medication_name')->toArray();
        $allMedications = array_merge($currentMedications, $medicationNames);

        return $this->checkInteractions($allMedications);
    }

    /**
     * Get patient medications with filters
     */
    public function getPatientMedications(Patient $patient, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $patient->medications();

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('medication_name', 'like', "%{$search}%")
                  ->orWhere('indication', 'like', "%{$search}%")
                  ->orWhere('instructions', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply route filter
        if (!empty($filters['route'])) {
            $query->where('route', $filters['route']);
        }

        // Apply frequency filter
        if (!empty($filters['frequency'])) {
            $query->where('frequency', $filters['frequency']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * Toggle medication status (active/discontinued)
     */
    public function toggleStatus(Medication $medication): Medication
    {
        try {
            DB::beginTransaction();

            $newStatus = $medication->status === 'active' ? 'discontinued' : 'active';
            $medication->update(['status' => $newStatus]);

            // Log the activity
            Log::info('Medication status toggled', [
                'medication_id' => $medication->id,
                'patient_id' => $medication->patient_id,
                'old_status' => $medication->status,
                'new_status' => $newStatus,
            ]);

            DB::commit();
            return $medication->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to toggle medication status', [
                'medication_id' => $medication->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
