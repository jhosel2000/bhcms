<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Announcement;

class MidwifeAnnouncementsController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index()
    {
        // For debugging, show all announcements including unpublished ones
        $announcements = Announcement::latest()->paginate(10);
        return view('midwife.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        return view('midwife.announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:health,vaccination,general,emergency',
            'priority' => 'required|in:low,medium,high',
            'is_active' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        // Debug: Log the request data
        Log::info('Announcement Store Request:', $request->all());

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'priority' => $request->priority,
            'is_active' => $request->has('is_active'),
            'published_at' => $request->published_at ?: now(),
            'created_by' => auth()->id(),
        ]);

        // Debug: Log the created announcement
        Log::info('Announcement Created:', $announcement->toArray());

        return redirect()->route('midwife.announcements.index')->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        return view('midwife.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        return view('midwife.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:health,vaccination,general,emergency',
            'priority' => 'required|in:low,medium,high',
            'is_active' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'priority' => $request->priority,
            'is_active' => $request->has('is_active'),
            'published_at' => $request->published_at ?: $announcement->published_at,
        ]);

        return redirect()->route('midwife.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('midwife.announcements.index')->with('success', 'Announcement deleted successfully.');
    }
}
