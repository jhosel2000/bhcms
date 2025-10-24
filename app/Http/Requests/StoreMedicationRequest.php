<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        return $user && $user->role === 'doctor';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'medication_name' => ['required', 'string', 'max:255'],
            'dosage' => ['required', 'string', 'max:100'],
            'frequency' => ['required', 'string', 'max:100'],
            'route' => ['sometimes', 'string', 'max:50', 'in:oral,intravenous,intramuscular,subcutaneous,topical,rectal,inhaled,other'],
            'status' => ['sometimes', 'string', 'in:active,discontinued,completed'],
            'start_date' => ['required', 'date', 'before_or_equal:end_date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'prescribed_by' => ['nullable', 'string', 'max:255'],
            'indication' => ['nullable', 'string', 'max:500'],
            'instructions' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'medication_name.required' => 'Medication name is required.',
            'medication_name.max' => 'Medication name must not exceed 255 characters.',
            'dosage.required' => 'Dosage is required.',
            'dosage.max' => 'Dosage must not exceed 100 characters.',
            'frequency.required' => 'Frequency is required.',
            'frequency.max' => 'Frequency must not exceed 100 characters.',
            'route.in' => 'Invalid administration route selected.',
            'status.in' => 'Invalid status selected.',
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'start_date.before_or_equal' => 'Start date cannot be after end date.',
            'end_date.date' => 'End date must be a valid date.',
            'end_date.after' => 'End date must be after start date.',
            'prescribed_by.exists' => 'Selected prescriber does not exist.',
            'indication.max' => 'Indication must not exceed 500 characters.',
            'instructions.max' => 'Instructions must not exceed 1000 characters.',
            'notes.max' => 'Notes must not exceed 2000 characters.',
            'attachment.file' => 'Attachment must be a valid file.',
            'attachment.mimes' => 'Attachment must be a PDF, image, or document file.',
            'attachment.max' => 'Attachment must not exceed 10MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'medication_name' => 'medication name',
            'start_date' => 'start date',
            'end_date' => 'end date',
            'prescribed_by' => 'prescribed by',
            'attachment' => 'attachment',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize input data
        $this->merge([
            'medication_name' => $this->filled('medication_name') ? trim($this->medication_name) : null,
            'dosage' => $this->filled('dosage') ? trim($this->dosage) : null,
            'frequency' => $this->filled('frequency') ? trim($this->frequency) : null,
            'indication' => $this->filled('indication') ? trim($this->indication) : null,
            'instructions' => $this->filled('instructions') ? trim($this->instructions) : null,
            'notes' => $this->filled('notes') ? trim($this->notes) : null,
        ]);
    }
}
