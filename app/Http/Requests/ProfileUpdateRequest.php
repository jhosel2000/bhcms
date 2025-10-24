<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];

        // Add patient-specific validation rules if user is a patient
        if ($this->user()->hasRole('patient')) {
            $rules = array_merge($rules, [
                'full_name' => ['required', 'string', 'max:255'],
                'date_of_birth' => ['required', 'date', 'before:today'],
                'gender' => ['required', 'in:male,female,other'],
                'full_address' => ['required', 'string', 'max:500'],
                'barangay' => ['nullable', 'string', 'max:255'],
                'contact_number' => ['required', 'string', 'max:20'],
                'emergency_contact_name' => ['nullable', 'string', 'max:255'],
                'emergency_contact_number' => ['nullable', 'string', 'max:20'],
                'civil_status' => ['nullable', 'in:single,married,divorced,widowed'],
                'occupation' => ['nullable', 'string', 'max:255'],
                'religion' => ['nullable', 'string', 'max:255'],
            ]);
        } else {
            $rules['name'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }
}
