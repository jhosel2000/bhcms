<?php

namespace App\Services;

use App\Models\Allergy;
use App\Models\Patient;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AllergyService
{
    /**
     * Create a new allergy for a patient
     */
    public function create(array $data, Patient $patient): Allergy
    {
        try {
            DB::beginTransaction();

            $filePath = null;
            $fileName = null;

            if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
                $filePath = $data['attachment']->store('allergies', 'public');
                $fileName = $data['attachment']->getClientOriginalName();
            }

            $allergy = $patient->allergies()->create([
                'allergen_type' => $data['allergen_type'],
                'allergen_name' => $data['allergen_name'],
                'severity' => $data['severity'],
                'reaction_description' => $data['reaction_description'],
                'status' => $data['status'] ?? 'active',
                'first_occurrence' => $data['first_occurrence'] ?? now(),
                'notes' => $data['notes'] ?? null,
                'file_path' => $filePath,
                'file_name' => $fileName,
            ]);

            // Log the activity
            Log::info('Allergy created', [
                'allergy_id' => $allergy->id,
                'patient_id' => $patient->id,
                'doctor_id' => auth()->user()->doctor->id,
                'severity' => $allergy->severity,
            ]);

            DB::commit();
            return $allergy;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create allergy', [
                'patient_id' => $patient->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing allergy
     */
    public function update(Allergy $allergy, array $data): Allergy
    {
        try {
            DB::beginTransaction();

            $oldData = $allergy->toArray();

            // Handle file upload
            if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
                // Delete old file if exists
                if ($allergy->file_path) {
                    Storage::disk('public')->delete($allergy->file_path);
                }
                $data['file_path'] = $data['attachment']->store('allergies', 'public');
                $data['file_name'] = $data['attachment']->getClientOriginalName();
            } elseif (!isset($data['attachment'])) {
                // If no new file, keep existing
                unset($data['file_path'], $data['file_name']);
            }

            $allergy->update($data);

            // Log the activity
            Log::info('Allergy updated', [
                'allergy_id' => $allergy->id,
                'patient_id' => $allergy->patient_id,
                'old_data' => $oldData,
                'new_data' => $data,
            ]);

            DB::commit();
            return $allergy->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update allergy', [
                'allergy_id' => $allergy->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete an allergy
     */
    public function delete(Allergy $allergy): bool
    {
        try {
            DB::beginTransaction();

            // Log the activity before deletion
            Log::info('Allergy deleted', [
                'allergy_id' => $allergy->id,
                'patient_id' => $allergy->patient_id,
                'allergen_name' => $allergy->allergen_name,
            ]);

            $allergy->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete allergy', [
                'allergy_id' => $allergy->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get allergies by severity level
     */
    public function getBySeverity(Patient $patient, string $severity): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->allergies()
            ->where('severity', $severity)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get critical allergies for a patient
     */
    public function getCriticalAllergies(Patient $patient): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->allergies()
            ->where('status', 'active')
            ->whereIn('severity', ['severe', 'life_threatening'])
            ->orderBy('severity', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Search allergies by allergen name or type
     */
    public function search(Patient $patient, string $query): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->allergies()
            ->where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('allergen_name', 'like', "%{$query}%")
                  ->orWhere('allergen_type', 'like', "%{$query}%")
                  ->orWhere('reaction_description', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get allergy statistics for a patient
     */
    public function getAllergyStatistics(Patient $patient): array
    {
        $totalAllergies = $patient->allergies()->count();
        $activeAllergies = $patient->allergies()->where('status', 'active')->count();
        $criticalAllergies = $patient->allergies()->where('status', 'active')
            ->whereIn('severity', ['severe', 'life_threatening'])->count();

        $severities = $patient->allergies()
            ->where('status', 'active')
            ->selectRaw('severity, COUNT(*) as count')
            ->groupBy('severity')
            ->pluck('count', 'severity')
            ->toArray();

        $types = $patient->allergies()
            ->where('status', 'active')
            ->selectRaw('allergen_type, COUNT(*) as count')
            ->groupBy('allergen_type')
            ->pluck('count', 'allergen_type')
            ->toArray();

        return [
            'total' => $totalAllergies,
            'active' => $activeAllergies,
            'critical' => $criticalAllergies,
            'by_severity' => $severities,
            'by_type' => $types,
        ];
    }

    /**
     * Get available allergen types
     */
    public function getAllergenTypes(): array
    {
        return [
            'medication' => 'Medication',
            'food' => 'Food',
            'environmental' => 'Environmental',
            'insect' => 'Insect',
            'latex' => 'Latex',
            'other' => 'Other',
        ];
    }

    /**
     * Get available severity levels
     */
    public function getSeverityLevels(): array
    {
        return [
            'mild' => 'Mild',
            'moderate' => 'Moderate',
            'severe' => 'Severe',
            'life_threatening' => 'Life Threatening',
        ];
    }

    /**
     * Toggle allergy status (active/inactive)
     */
    public function toggleStatus(Allergy $allergy): Allergy
    {
        try {
            DB::beginTransaction();

            $newStatus = $allergy->status === 'active' ? 'inactive' : 'active';
            $allergy->update(['status' => $newStatus]);

            // Log the activity
            Log::info('Allergy status toggled', [
                'allergy_id' => $allergy->id,
                'patient_id' => $allergy->patient_id,
                'old_status' => $allergy->status,
                'new_status' => $newStatus,
            ]);

            DB::commit();
            return $allergy->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to toggle allergy status', [
                'allergy_id' => $allergy->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
