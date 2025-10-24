<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLabResultRequest extends FormRequest
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
            'test_name' => ['required', 'string', 'max:255'],
            'test_code' => ['nullable', 'string', 'max:50'],
            'category' => ['required', 'string', 'max:100', 'in:hematology,chemistry,microbiology,immunology,urinalysis,parasitology,other'],
            'test_date' => ['required', 'date', 'before_or_equal:today'],
            'result_date' => ['nullable', 'date', 'before_or_equal:today'],
            'result_value' => ['nullable', 'string', 'max:100'],
            'unit' => ['nullable', 'string', 'max:50'],
            'reference_range' => ['nullable', 'string', 'max:100'],
            'status' => ['sometimes', 'string', 'in:pending,normal,abnormal,critical,reviewed'],
            'interpretation' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
            'ordered_by' => ['nullable', 'string', 'max:255'],
            'performed_by' => ['nullable', 'string', 'max:255'],
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
            'test_name.required' => 'Test name is required.',
            'test_name.max' => 'Test name must not exceed 255 characters.',
            'test_code.max' => 'Test code must not exceed 50 characters.',
            'category.required' => 'Test category is required.',
            'category.in' => 'Invalid test category selected.',
            'test_date.required' => 'Test date is required.',
            'test_date.date' => 'Test date must be a valid date.',
            'test_date.before_or_equal' => 'Test date cannot be in the future.',
            'result_date.date' => 'Result date must be a valid date.',
            'result_date.before_or_equal' => 'Result date cannot be in the future.',
            'result_value.max' => 'Result value must not exceed 100 characters.',
            'unit.max' => 'Unit must not exceed 50 characters.',
            'reference_range.max' => 'Reference range must not exceed 100 characters.',
            'status.in' => 'Invalid status selected.',
            'interpretation.max' => 'Interpretation must not exceed 2000 characters.',
            'notes.max' => 'Notes must not exceed 2000 characters.',
            'attachment.file' => 'Attachment must be a valid file.',
            'attachment.mimes' => 'Attachment must be a PDF, image, or document file.',
            'attachment.max' => 'Attachment must not exceed 10MB.',
            'ordered_by.max' => 'Ordered by must not exceed 255 characters.',
            'performed_by.max' => 'Performed by must not exceed 255 characters.',
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
            'test_name' => 'test name',
            'test_code' => 'test code',
            'category' => 'test category',
            'test_date' => 'test date',
            'result_date' => 'result date',
            'result_value' => 'result value',
            'reference_range' => 'reference range',
            'attachment' => 'attachment',
            'ordered_by' => 'ordered by',
            'performed_by' => 'performed by',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize input data
        $this->merge([
            'test_name' => $this->filled('test_name') ? trim($this->test_name) : null,
            'test_code' => $this->filled('test_code') ? trim($this->test_code) : null,
            'result_value' => $this->filled('result_value') ? trim($this->result_value) : null,
            'interpretation' => $this->filled('interpretation') ? trim($this->interpretation) : null,
            'notes' => $this->filled('notes') ? trim($this->notes) : null,
            'performed_by' => $this->filled('performed_by') ? trim($this->performed_by) : null,
            'ordered_by' => $this->filled('ordered_by') ? trim($this->ordered_by) : null,
        ]);
    }
}
