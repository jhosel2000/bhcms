<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\MedicalAttachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MedicalAttachmentService
{
    public function getCategories(): array
    {
        return ['referral', 'prescription', 'imaging', 'other'];
    }

    public function create(array $data, Patient $patient): MedicalAttachment
    {
        try {
            DB::beginTransaction();

            $filePath = null;
            $fileName = null;

            if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
                $filePath = $data['attachment']->store('medical-attachments', 'public');
                $fileName = $data['attachment']->getClientOriginalName();
            }

            $attachment = $patient->medicalAttachments()->create([
                'title' => $data['title'],
                'category' => $data['category'] ?? null,
                'description' => $data['description'] ?? null,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'is_confidential' => $data['is_confidential'] ?? false,
                'uploaded_by' => auth()->id(),
                'uploaded_at' => now(),
            ]);

            // Log the activity
            Log::info('Medical attachment created', [
                'attachment_id' => $attachment->id,
                'patient_id' => $patient->id,
                'doctor_id' => auth()->user()->doctor->id ?? null,
                'file_name' => $fileName,
            ]);

            DB::commit();
            return $attachment;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create medical attachment', [
                'patient_id' => $patient->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function update(MedicalAttachment $attachment, array $data): MedicalAttachment
    {
        try {
            DB::beginTransaction();

            $oldData = $attachment->toArray();

            // Handle file upload
            if (isset($data['attachment']) && $data['attachment'] instanceof UploadedFile) {
                // Delete old file if exists
                if ($attachment->file_path) {
                    Storage::disk('public')->delete($attachment->file_path);
                }
                $data['file_path'] = $data['attachment']->store('medical-attachments', 'public');
                $data['file_name'] = $data['attachment']->getClientOriginalName();
            } elseif (!isset($data['attachment'])) {
                // If no new file, keep existing
                unset($data['file_path'], $data['file_name']);
            }

            $attachment->update($data);

            // Log the activity
            Log::info('Medical attachment updated', [
                'attachment_id' => $attachment->id,
                'patient_id' => $attachment->patient_id,
                'old_data' => $oldData,
                'new_data' => $data,
            ]);

            DB::commit();
            return $attachment->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update medical attachment', [
                'attachment_id' => $attachment->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function delete(MedicalAttachment $attachment): void
    {
        try {
            DB::beginTransaction();

            // Delete the file from storage
            if ($attachment->file_path) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            // Log the activity before deletion
            Log::info('Medical attachment deleted', [
                'attachment_id' => $attachment->id,
                'patient_id' => $attachment->patient_id,
                'file_name' => $attachment->file_name,
            ]);

            $attachment->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete medical attachment', [
                'attachment_id' => $attachment->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
