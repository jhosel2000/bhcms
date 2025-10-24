<?php

namespace App\Http\Controllers;

use App\Models\DoctorLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaves = DoctorLeave::where('doctor_id', Auth::user()->doctor->id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('doctor.leaves.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('doctor.leaves.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:leave,sick_leave',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500',
        ]);

        DoctorLeave::create([
            'doctor_id' => Auth::user()->doctor->id,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);

        return redirect()->route('doctor.leaves.index')->with('success', 'Leave created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $leave = DoctorLeave::where('doctor_id', Auth::user()->doctor->id)->findOrFail($id);

        return view('doctor.leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $leave = DoctorLeave::where('doctor_id', Auth::user()->doctor->id)->findOrFail($id);

        return view('doctor.leaves.edit', compact('leave'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $leave = DoctorLeave::where('doctor_id', Auth::user()->doctor->id)->findOrFail($id);

        $request->validate([
            'type' => 'required|in:leave,sick_leave',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500',
        ]);

        $leave->update([
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);

        return redirect()->route('doctor.leaves.index')->with('success', 'Leave updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leave = DoctorLeave::where('doctor_id', Auth::user()->doctor->id)->findOrFail($id);
        $leave->delete();

        return redirect()->route('doctor.leaves.index')->with('success', 'Leave deleted successfully.');
    }
}
