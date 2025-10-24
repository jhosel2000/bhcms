<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{


    /**
     * Display the doctor registration view.
     */
    public function createDoctor(): View
    {
        return view('auth.register-doctor');
    }

    /**
     * Handle an incoming doctor registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeDoctor(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'professional_license_number' => 'required|string|unique:doctors,professional_license_number',
            'specialization' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email_address' => 'required|email|unique:users,email',
            'verification_code' => 'required|string',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->verification_code !== 'DOCTOR123') {
            return back()->withErrors(['verification_code' => 'Invalid verification code.'])->withInput();
        }

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email_address,
            'password' => Hash::make($request->password),
            'role' => 'doctor',
        ]);

        $user->doctor()->create([
            'full_name' => $request->full_name,
            'professional_license_number' => $request->professional_license_number,
            'specialization' => $request->specialization,
            'contact_number' => $request->contact_number,
            'email_address' => $request->email_address,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Display the midwife registration view.
     */
    public function createMidwife(): View
    {
        return view('auth.register-midwife');
    }

    /**
     * Handle an incoming midwife registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeMidwife(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'professional_license_number' => 'required|string|unique:midwives,professional_license_number',
            'contact_number' => 'required|string|max:20',
            'email_address' => 'required|email|unique:users,email',
            'area_of_assignment' => 'required|string|max:255',
            'verification_code' => 'required|string',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->verification_code !== 'MIDWIFE123') {
            return back()->withErrors(['verification_code' => 'Invalid verification code.'])->withInput();
        }

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email_address,
            'password' => Hash::make($request->password),
            'role' => 'midwife',
        ]);

        $user->midwife()->create([
            'full_name' => $request->full_name,
            'professional_license_number' => $request->professional_license_number,
            'contact_number' => $request->contact_number,
            'email_address' => $request->email_address,
            'area_of_assignment' => $request->area_of_assignment,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Display the BHW registration view.
     */
    public function createBHW(): View
    {
        return view('auth.register-bhw');
    }

    /**
     * Handle an incoming BHW registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeBHW(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'contact_number' => 'required|string|max:20',
            'email_address' => 'required|email|unique:users,email',
            'purok_zone_of_assignment' => 'required|string|max:255',
            'barangay_id_number' => 'required|string|unique:bhws,barangay_id_number',
            'verification_code' => 'required|string',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->verification_code !== 'BHW123') {
            return back()->withErrors(['verification_code' => 'Invalid verification code.'])->withInput();
        }

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email_address,
            'password' => Hash::make($request->password),
            'role' => 'bhw',
        ]);

        $user->bhw()->create([
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'contact_number' => $request->contact_number,
            'email_address' => $request->email_address,
            'purok_zone_of_assignment' => $request->purok_zone_of_assignment,
            'barangay_id_number' => $request->barangay_id_number,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Display the patient registration view.
     */
    public function createPatient(): View
    {
        return view('auth.register-patient');
    }

    /**
     * Handle an incoming patient registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storePatient(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'full_address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:20',
            'civil_status' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'email_address' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email_address,
            'password' => Hash::make($request->password),
            'role' => 'patient',
        ]);

        $user->patient()->create([
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'full_address' => $request->full_address,
            'contact_number' => $request->contact_number,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_number' => $request->emergency_contact_number,
            'civil_status' => $request->civil_status,
            'occupation' => $request->occupation,
            'religion' => $request->religion,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
