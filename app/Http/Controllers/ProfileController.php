<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // If user is a patient, show patient-specific profile
        if ($user->hasRole('patient') && $user->patient) {
            return view('profile.edit-patient', [
                'user' => $user,
                'patient' => $user->patient,
            ]);
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Update user information (email and name if not patient)
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
        }

        if (isset($validated['name']) && !$user->hasRole('patient')) {
            $user->name = $validated['name'];
        }

        $user->save();

        // If user is a patient, update patient information
        if ($user->hasRole('patient') && $user->patient) {
            $patientData = collect($validated)->only([
                'full_name',
                'date_of_birth',
                'gender',
                'full_address',
                'barangay',
                'contact_number',
                'emergency_contact_name',
                'emergency_contact_number',
                'civil_status',
                'occupation',
                'religion',
            ])->toArray();

            $user->patient->update($patientData);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
