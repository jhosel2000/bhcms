<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class PatientAnnouncementsController extends Controller
{
    /**
     * Display a listing of announcements for the patient.
     */
    public function index()
    {
        // Get active and published announcements
        $announcements = Announcement::active()
            ->published()
            ->latest()
            ->paginate(10);

        return view('patient.announcements.index', compact('announcements'));
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        // Ensure the announcement is active and published
        if (!$announcement->is_active || ($announcement->published_at && $announcement->published_at->isFuture())) {
            abort(404);
        }

        return view('patient.announcements.show', compact('announcement'));
    }
}
