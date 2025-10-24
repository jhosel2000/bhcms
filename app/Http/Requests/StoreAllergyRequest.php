<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAllergyRequest extends FormRequest
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
            'allergen_type' => ['required', 'string', 'max:100', 'in:medication,food,environmental,other'],
            'allergen_name' => ['required', 'string', 'max:255'],
            'severity' => ['required', 'string', 'in:mild,moderate,severe,life_threatening'],
            'reaction_description' => ['required', 'string', 'max:1000'],
            'status' => ['sometimes', 'string', 'in:active,inactive'],
            'first_occurrence' => ['nullable', 'date', 'before_or_equal:today'],
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
            'allergen_type.required' => 'Allergen type is required.',
            'allergen_type.in' => 'Invalid allergen type selected.',
            'allergen_name.required' => 'Allergen name is required.',
            'allergen_name.max' => 'Allergen name must not exceed 255 characters.',
            'severity.required' => 'Severity level is required.',
            'severity.in' => 'Invalid severity level selected.',
            'reaction_description.required' => 'Reaction description is required.',
            'reaction_description.max' => 'Reaction description must not exceed 1000 characters.',
            'status.in' => 'Invalid status selected.',
            'first_occurrence.date' => 'First occurrence must be a valid date.',
            'first_occurrence.before_or_equal' => 'First occurrence cannot be in the future.',
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
            'allergen_type' => 'allergen type',
            'allergen_name' => 'allergen name',
            'reaction_description' => 'reaction description',
            'first_occurrence' => 'first occurrence',
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
            'allergen_name' => $this->filled('allergen_name') ? trim($this->allergen_name) : null,
            'reaction_description' => $this->filled('reaction_description') ? trim($this->reaction_description) : null,
            'notes' => $this->filled('notes') ? trim($this->notes) : null,
        ]);
    }
}
