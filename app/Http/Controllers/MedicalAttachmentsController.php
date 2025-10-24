<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\MedicalAttachment;
use App\Services\MedicalAttachmentService;
use App\Http\Requests\StoreMedicalAttachmentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MedicalAttachmentsController extends Controller
{
    protected MedicalAttachmentService $medicalAttachmentService;

    public function __construct(MedicalAttachmentService $medicalAttachmentService)
    {
        $this->medicalAttachmentService = $medicalAttachmentService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of attachments for a patient
     */
    public function index(Request $request, Patient $patient): View|JsonResponse
    {
        $this->authorize('viewAny', MedicalAttachment::class);

        $search = $request->input('search');
        $category = $request->input('category');

        $query = $patient->medicalAttachments();

        if ($search) {
            $query->where('description', 'like', "%{$search}%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        $attachments = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $attachments,
            ]);
        }

        return view('doctor.patients.attachments.index', compact('attachments', 'patient', 'search', 'category'));
    }

    /**
     * Show the form for creating a new attachment
     */
    public function create(Patient $patient): View
    {
        $this->authorize('create', MedicalAttachment::class);

        $categories = $this->medicalAttachmentService->getCategories();

        return view('doctor.patients.attachments.create', compact('patient', 'categories'));
    }

    /**
     * Store a newly created attachment
     */
    public function store(StoreMedicalAttachmentRequest $request, Patient $patient): RedirectResponse|JsonResponse
    {
        try {
            $attachment = $this->medicalAttachmentService->create($request->validated(), $patient);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Attachment uploaded successfully',
                    'data' => $attachment
                ], 201);
            }

            return redirect()->route('doctor.patients.attachments.index', $patient)
                           ->with('success', 'Attachment uploaded successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload attachment',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to upload attachment');
        }
    }

    /**
     * Display the specified attachment
     */
    public function show(Request $request, Patient $patient, MedicalAttachment $attachment): View|JsonResponse
    {
        $this->authorize('view', $attachment);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $attachment->load('patient')
            ]);
        }

        return view('doctor.patients.attachments.show', compact('patient', 'attachment'));
    }

    /**
     * Show the form for editing the specified attachment
     */
    public function edit(Patient $patient, MedicalAttachment $attachment): View
    {
        $this->authorize('update', $attachment);

        $categories = $this->medicalAttachmentService->getCategories();

        return view('doctor.patients.attachments.edit', compact('patient', 'attachment', 'categories'));
    }

    /**
     * Update the specified attachment
     */
    public function update(StoreMedicalAttachmentRequest $request, Patient $patient, MedicalAttachment $attachment): RedirectResponse|JsonResponse
    {
        try {
            $updatedAttachment = $this->medicalAttachmentService->update($attachment, $request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Attachment updated successfully',
                    'data' => $updatedAttachment
                ]);
            }

            return redirect()->route('doctor.patients.attachments.show', [$patient, $updatedAttachment])
                           ->with('success', 'Attachment updated successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update attachment',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to update attachment');
        }
    }

    /**
     * Remove the specified attachment
     */
    public function destroy(Request $request, Patient $patient, MedicalAttachment $attachment): RedirectResponse|JsonResponse
    {
        try {
            $this->authorize('delete', $attachment);

            $this->medicalAttachmentService->delete($attachment);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Attachment deleted successfully'
                ]);
            }

            return redirect()->route('doctor.patients.attachments.index', $patient)
                           ->with('success', 'Attachment deleted successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete attachment',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to delete attachment');
        }
    }
}
