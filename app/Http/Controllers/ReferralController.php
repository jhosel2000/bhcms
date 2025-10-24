<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Patient $patient)
    {
        $referrals = $patient->referrals()->latest()->paginate(10);
        return view('doctor.patient.referrals.index', compact('patient', 'referrals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Patient $patient)
    {
        return view('doctor.patient.referrals.create', compact('patient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'referred_to' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $patient->referrals()->create([
            'doctor_id' => auth()->id(),
            'referred_to' => $request->referred_to,
            'reason' => $request->reason,
            'notes' => $request->notes,
        ]);

        return redirect()->route('doctor.patient.referrals.index', $patient)
            ->with('success', 'Referral added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient, Referral $referral)
    {
        return view('doctor.patient.referrals.show', compact('patient', 'referral'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient, Referral $referral)
    {
        return view('doctor.patient.referrals.edit', compact('patient', 'referral'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient, Referral $referral)
    {
        $request->validate([
            'referred_to' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $referral->update([
            'referred_to' => $request->referred_to,
            'reason' => $request->reason,
            'notes' => $request->notes,
        ]);

        return redirect()->route('doctor.patient.referrals.index', $patient)
            ->with('success', 'Referral updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient, Referral $referral)
    {
        $referral->delete();

        return redirect()->route('doctor.patient.referrals.index', $patient)
            ->with('success', 'Referral deleted successfully.');
    }
}
