<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRiskRequest extends FormRequest
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
            'risk_type' => ['required', 'string', 'max:50', 'in:cardiovascular,diabetes,cancer,mental_health,infection,fall_risk,medication,lifestyle,genetic,other'],
            'title' => ['required', 'string', 'max:255'],
            'severity' => ['required', 'string', 'max:20', 'in:low,moderate,high,critical'],
            'description' => ['required', 'string', 'max:1000'],
            'identified_date' => ['required', 'date', 'before_or_equal:today'],
            'review_date' => ['nullable', 'date', 'after:identified_date'],
            'identified_by' => ['nullable', 'string', 'max:255'],
            'management_plan' => ['nullable', 'string', 'max:2000'],
            'status' => ['sometimes', 'string', 'in:active,resolved,monitoring,inactive'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'requires_alert' => ['sometimes', 'boolean'],
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
            'risk_type.required' => 'Risk type is required.',
            'risk_type.in' => 'Invalid risk type selected.',
            'title.required' => 'Risk title is required.',
            'title.max' => 'Risk title must not exceed 255 characters.',
            'severity.required' => 'Severity level is required.',
            'severity.in' => 'Invalid severity level selected.',
            'description.required' => 'Risk description is required.',
            'description.max' => 'Risk description must not exceed 1000 characters.',
            'identified_date.required' => 'Identification date is required.',
            'identified_date.date' => 'Identification date must be a valid date.',
            'identified_date.before_or_equal' => 'Identification date cannot be in the future.',
            'review_date.date' => 'Review date must be a valid date.',
            'review_date.after' => 'Review date must be after the identification date.',
            'identified_by.max' => 'Identified by must not exceed 255 characters.',
            'management_plan.max' => 'Management plan must not exceed 2000 characters.',
            'status.in' => 'Invalid status selected.',
            'notes.max' => 'Notes must not exceed 2000 characters.',
            'requires_alert.boolean' => 'Requires alert must be true or false.',
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
            'risk_type' => 'risk type',
            'title' => 'risk title',
            'severity' => 'severity level',
            'description' => 'risk description',
            'identified_date' => 'identification date',
            'review_date' => 'review date',
            'identified_by' => 'identified by',
            'management_plan' => 'management plan',
            'requires_alert' => 'requires alert',
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
            'title' => $this->filled('title') ? trim($this->title) : null,
            'description' => $this->filled('description') ? trim($this->description) : null,
            'management_plan' => $this->filled('management_plan') ? trim($this->management_plan) : null,
            'notes' => $this->filled('notes') ? trim($this->notes) : null,
            'identified_by' => $this->filled('identified_by') ? trim($this->identified_by) : auth()->user()->name,
        ]);
    }
}
