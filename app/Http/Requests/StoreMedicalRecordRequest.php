<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
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
            'record_type' => ['required', 'string', 'max:100', 'in:consultation,diagnosis,treatment,surgery,emergency,follow_up,discharge,referral,imaging,pathology,other'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'record_date' => ['required', 'date', 'before_or_equal:today'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'], // 10MB max
            'confidentiality_level' => ['nullable', 'string', 'in:standard,sensitive,confidential'],
            'status' => ['sometimes', 'string', 'in:active,archived,draft'],
            'notes' => ['nullable', 'string', 'max:2000'],
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
            'record_type.required' => 'Record type is required.',
            'record_type.in' => 'Invalid record type selected.',
            'title.required' => 'Record title is required.',
            'title.max' => 'Record title must not exceed 255 characters.',
            'description.required' => 'Record description is required.',
            'description.max' => 'Record description must not exceed 5000 characters.',
            'record_date.required' => 'Record date is required.',
            'record_date.date' => 'Record date must be a valid date.',
            'record_date.before_or_equal' => 'Record date cannot be in the future.',
            'document.file' => 'Document must be a valid file.',
            'document.mimes' => 'Document must be a PDF, image, or document file.',
            'document.max' => 'Document must not exceed 10MB.',
            'confidentiality_level.in' => 'Invalid confidentiality level selected.',
            'status.in' => 'Invalid status selected.',
            'notes.max' => 'Notes must not exceed 2000 characters.',
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
            'record_type' => 'record type',
            'record_date' => 'record date',
            'confidentiality_level' => 'confidentiality level',
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
            'notes' => $this->filled('notes') ? trim($this->notes) : null,
            'confidentiality_level' => empty($this->confidentiality_level) ? null : strtolower(trim($this->confidentiality_level)),
        ]);
    }
}
