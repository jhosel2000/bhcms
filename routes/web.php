<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('dashboard.' . auth()->user()->role);
})->middleware(['auth', 'verified'])->name('dashboard');

// Public EHR summary demo
Route::get('/ehr-summary', function () {
    $patient = (object)['full_name' => 'Jane Doe'];
    $vitalSigns = [
        ['label' => 'BP', 'value' => '142/90 mmHg', 'status' => 'moderate'],
        ['label' => 'HR', 'value' => '72 bpm', 'status' => 'normal'],
        ['label' => 'Temp', 'value' => '98.6 °F', 'status' => 'normal'],
    ];
    $problemList = [
        'Type 2 Diabetes, Uncontrolled',
        'Hypertension, Uncontrolled',
        'Chronic Low Back Pain',
    ];
    $medications = [
        ['name' => 'Metformin 500mg BID'],
        ['name' => 'Amlodipine 5mg Daily'],
        ['name' => 'Aspirin 81mg Daily'],
    ];
    $labResults = [
        ['name' => 'A1C', 'value' => '7.8', 'trend' => 'up', 'status' => 'amber'],
        ['name' => 'Creatinine', 'value' => '0.9', 'trend' => 'down', 'status' => 'green'],
        ['name' => 'CBC w/diff', 'value' => 'Normal', 'trend' => 'normal', 'status' => 'green'],
    ];
    return view('doctor.ehr.summary', compact('patient', 'vitalSigns', 'problemList', 'medications', 'labResults'));
})->name('ehr.summary');

// Role-specific dashboard routes
Route::get('/dashboard/doctor', [\App\Http\Controllers\DoctorAppointmentsController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard.doctor');

Route::get('/dashboard/midwife', [\App\Http\Controllers\MidwifeAppointmentsController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard.midwife');

Route::get('/dashboard/bhw', [App\Http\Controllers\BHWController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard.bhw');

use App\Http\Controllers\PatientController;

Route::get('/dashboard/patient', [PatientController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.patient');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Doctor routes
    Route::resource('doctor/patients', \App\Http\Controllers\DoctorPatientsController::class)->names([
        'index' => 'doctor.patients.index',
        'create' => 'doctor.patients.create',
        'store' => 'doctor.patients.store',
        'show' => 'doctor.patients.show',
        'edit' => 'doctor.patients.edit',
        'update' => 'doctor.patients.update',
        'destroy' => 'doctor.patients.destroy',
    ]);

    // Patient Risks Management
    Route::resource('doctor/patients/{patient}/risks', \App\Http\Controllers\PatientRisksController::class)->names([
        'index' => 'doctor.patient.risks.index',
        'create' => 'doctor.patient.risks.create',
        'store' => 'doctor.patient.risks.store',
        'show' => 'doctor.patient.risks.show',
        'edit' => 'doctor.patient.risks.edit',
        'update' => 'doctor.patient.risks.update',
        'destroy' => 'doctor.patient.risks.destroy',
    ]);

    // Doctor Medical Attachments routes
    Route::resource('doctor/patients.attachments', \App\Http\Controllers\MedicalAttachmentsController::class)->shallow()->names([
        'index' => 'doctor.patients.attachments.index',
        'create' => 'doctor.patients.attachments.create',
        'store' => 'doctor.patients.attachments.store',
        'show' => 'doctor.patients.attachments.show',
        'edit' => 'doctor.patients.attachments.edit',
        'update' => 'doctor.patients.attachments.update',
        'destroy' => 'doctor.patients.attachments.destroy',
    ]);

    Route::resource('doctor/appointments', \App\Http\Controllers\DoctorAppointmentsController::class)->only([
        'index', 'show'
    ])->names([
        'index' => 'doctor.appointments.index',
        'show' => 'doctor.appointments.show',
    ]);

    // Dashboard-specific appointment status update
    Route::patch('doctor/appointments/{appointment}/status', [\App\Http\Controllers\DoctorAppointmentsController::class, 'updateStatus'])->name('doctor.appointments.status.update');

    // Bulk appointment status update
    Route::post('doctor/appointments/bulk-update-status', [\App\Http\Controllers\DoctorAppointmentsController::class, 'bulkUpdateStatus'])->name('doctor.appointments.bulk-update-status');



    // New approval workflow routes
    Route::post('doctor/appointments/{id}/approve', [\App\Http\Controllers\DoctorAppointmentsController::class, 'approve'])->name('doctor.appointments.approve');
    Route::post('doctor/appointments/{id}/decline', [\App\Http\Controllers\DoctorAppointmentsController::class, 'decline'])->name('doctor.appointments.decline');
    Route::post('doctor/appointments/{id}/complete', [\App\Http\Controllers\DoctorAppointmentsController::class, 'complete'])->name('doctor.appointments.complete');

    Route::resource('doctor/prescriptions', \App\Http\Controllers\DoctorPrescriptionsController::class)->names([
        'index' => 'doctor.prescriptions.index',
        'create' => 'doctor.prescriptions.create',
        'store' => 'doctor.prescriptions.store',
        'show' => 'doctor.prescriptions.show',
        'edit' => 'doctor.prescriptions.edit',
        'update' => 'doctor.prescriptions.update',
        'destroy' => 'doctor.prescriptions.destroy',
    ]);

    // Additional prescription routes
    Route::post('doctor/prescriptions/{prescription}/status', [\App\Http\Controllers\DoctorPrescriptionsController::class, 'updateStatus'])->name('doctor.prescriptions.updateStatus');
    Route::get('doctor/prescriptions/{prescription}/print', [\App\Http\Controllers\DoctorPrescriptionsController::class, 'print'])->name('doctor.prescriptions.print');

    // Doctor EHR routes
    Route::get('doctor/ehr', [\App\Http\Controllers\DoctorEhrController::class, 'index'])->name('doctor.ehr.index');
    Route::get('doctor/ehr/create', [\App\Http\Controllers\DoctorEhrController::class, 'create'])->name('doctor.ehr.create');
    Route::post('doctor/ehr', [\App\Http\Controllers\DoctorEhrController::class, 'store'])->name('doctor.ehr.store');
    Route::get('doctor/ehr/{patient}', [\App\Http\Controllers\DoctorEhrController::class, 'show'])->name('doctor.ehr.show');
    Route::get('doctor/ehr/{patient}/edit', [\App\Http\Controllers\DoctorEhrController::class, 'edit'])->name('doctor.ehr.edit');
    Route::put('doctor/ehr/{patient}', [\App\Http\Controllers\DoctorEhrController::class, 'update'])->name('doctor.ehr.update');
    Route::delete('doctor/ehr/{patient}', [\App\Http\Controllers\DoctorEhrController::class, 'destroy'])->name('doctor.ehr.destroy');

    // Additional EHR routes
    Route::post('doctor/ehr/sync-appointments', [\App\Http\Controllers\DoctorEhrController::class, 'syncCompletedAppointments'])->name('doctor.ehr.sync-appointments');
    Route::get('doctor/ehr/{patient}/download-pdf', [\App\Http\Controllers\DoctorEhrController::class, 'downloadEhrPdf'])->name('doctor.ehr.download-pdf');
    Route::post('doctor/ehr/{ehrRecord}/approve', [\App\Http\Controllers\DoctorEhrController::class, 'approveRecord'])->name('doctor.ehr.approve');
    Route::post('doctor/ehr/{ehrRecord}/flag', [\App\Http\Controllers\DoctorEhrController::class, 'flagRecord'])->name('doctor.ehr.flag');

    // EHR-based prescription and referral creation routes
    Route::post('doctor/ehr/{ehrRecord}/create-prescription', [\App\Http\Controllers\DoctorPrescriptionsController::class, 'storeFromEhr'])->name('doctor.ehr.create-prescription');
    Route::post('doctor/ehr/{ehrRecord}/create-referral', [\App\Http\Controllers\ReferralController::class, 'storeFromEhr'])->name('doctor.ehr.create-referral');


    // Doctor-specific routes for patient diagnoses
    Route::resource('doctor/patients/{patient}/diagnoses', \App\Http\Controllers\DiagnosisController::class)->names([
        'index' => 'doctor.patient.diagnoses.index',
        'create' => 'doctor.patient.diagnoses.create',
        'store' => 'doctor.patient.diagnoses.store',
        'show' => 'doctor.patient.diagnoses.show',
        'edit' => 'doctor.patient.diagnoses.edit',
        'update' => 'doctor.patient.diagnoses.update',
        'destroy' => 'doctor.patient.diagnoses.destroy',
    ]);

    // Doctor-specific routes for patient referrals
    Route::resource('doctor/patients/{patient}/referrals', \App\Http\Controllers\ReferralController::class)->names([
        'index' => 'doctor.patient.referrals.index',
        'create' => 'doctor.patient.referrals.create',
        'store' => 'doctor.patient.referrals.store',
        'show' => 'doctor.patient.referrals.show',
        'edit' => 'doctor.patient.referrals.edit',
        'update' => 'doctor.patient.referrals.update',
        'destroy' => 'doctor.patient.referrals.destroy',
    ]);


    // Allergies Management
    Route::resource('doctor/patients/{patient}/allergies', \App\Http\Controllers\AllergiesController::class)->names([
        'index' => 'doctor.patient.allergies.index',
        'create' => 'doctor.patient.allergies.create',
        'store' => 'doctor.patient.allergies.store',
        'show' => 'doctor.patient.allergies.show',
        'edit' => 'doctor.patient.allergies.edit',
        'update' => 'doctor.patient.allergies.update',
        'destroy' => 'doctor.patient.allergies.destroy',
    ]);

    // Allergies - Additional routes
    Route::get('doctor/patients/{patient}/allergies/severity/{severity}', [\App\Http\Controllers\AllergiesController::class, 'getBySeverity'])->name('doctor.patient.allergies.severity');
    Route::get('doctor/patients/{patient}/allergies/critical', [\App\Http\Controllers\AllergiesController::class, 'getCritical'])->name('doctor.patient.allergies.critical');
    Route::patch('doctor/patients/{patient}/allergies/{allergy}/toggle', [\App\Http\Controllers\AllergiesController::class, 'toggleStatus'])->name('doctor.patient.allergies.toggle');

    // Medications Management
    Route::resource('doctor/patients/{patient}/medications', \App\Http\Controllers\MedicationsController::class)->names([
        'index' => 'doctor.patient.medications.index',
        'create' => 'doctor.patient.medications.create',
        'store' => 'doctor.patient.medications.store',
        'show' => 'doctor.patient.medications.show',
        'edit' => 'doctor.patient.medications.edit',
        'update' => 'doctor.patient.medications.update',
        'destroy' => 'doctor.patient.medications.destroy',
    ]);

    // Medications - Additional routes
    Route::get('doctor/patients/{patient}/medications/current', [\App\Http\Controllers\MedicationsController::class, 'getCurrent'])->name('doctor.patient.medications.current');
    Route::get('doctor/patients/{patient}/medications/status/{status}', [\App\Http\Controllers\MedicationsController::class, 'getByStatus'])->name('doctor.patient.medications.status');
    Route::get('doctor/patients/{patient}/medications/expiring', [\App\Http\Controllers\MedicationsController::class, 'getExpiringSoon'])->name('doctor.patient.medications.expiring');
    Route::post('doctor/patients/{patient}/medications/check-interactions', [\App\Http\Controllers\MedicationsController::class, 'checkInteractions'])->name('doctor.patient.medications.interactions');
    Route::patch('doctor/patients/{patient}/medications/{medication}/toggle', [\App\Http\Controllers\MedicationsController::class, 'toggleStatus'])->name('doctor.patient.medications.toggle');

    // Lab Results Management
    Route::resource('doctor/patients/{patient}/lab-results', \App\Http\Controllers\LabResultsController::class)->names([
        'index' => 'doctor.patient.lab-results.index',
        'create' => 'doctor.patient.lab-results.create',
        'store' => 'doctor.patient.lab-results.store',
        'show' => 'doctor.patient.lab-results.show',
        'edit' => 'doctor.patient.lab-results.edit',
        'update' => 'doctor.patient.lab-results.update',
        'destroy' => 'doctor.patient.lab-results.destroy',
    ]);

    // Lab Results - Additional routes
    Route::get('doctor/patients/{patient}/lab-results/recent', [\App\Http\Controllers\LabResultsController::class, 'getRecent'])->name('doctor.patient.lab-results.recent');
    Route::get('doctor/patients/{patient}/lab-results/status/{status}', [\App\Http\Controllers\LabResultsController::class, 'getByStatus'])->name('doctor.patient.lab-results.status');
    Route::get('doctor/patients/{patient}/lab-results/critical', [\App\Http\Controllers\LabResultsController::class, 'getCritical'])->name('doctor.patient.lab-results.critical');
    Route::get('doctor/patients/{patient}/lab-results/trends', [\App\Http\Controllers\LabResultsController::class, 'getTrends'])->name('doctor.patient.lab-results.trends');
    Route::get('doctor/patients/{patient}/lab-results/{labResult}/download', [\App\Http\Controllers\LabResultsController::class, 'download'])->name('doctor.patient.lab-results.download');
    Route::patch('doctor/patients/{patient}/lab-results/{labResult}/toggle', [\App\Http\Controllers\LabResultsController::class, 'toggleStatus'])->name('doctor.patient.lab-results.toggle');



    // Patient Risks Management
    Route::resource('doctor/patients/{patient}/risks', \App\Http\Controllers\PatientRisksController::class)->names([
        'index' => 'doctor.patient.risks.index',
        'create' => 'doctor.patient.risks.create',
        'store' => 'doctor.patient.risks.store',
        'show' => 'doctor.patient.risks.show',
        'edit' => 'doctor.patient.risks.edit',
        'update' => 'doctor.patient.risks.update',
        'destroy' => 'doctor.patient.risks.destroy',
    ]);

    // Patient Risks - Additional routes
    Route::get('doctor/patients/{patient}/risks/severity/{severity}', [\App\Http\Controllers\PatientRisksController::class, 'getBySeverity'])->name('doctor.patient.risks.severity');
    Route::get('doctor/patients/{patient}/risks/critical', [\App\Http\Controllers\PatientRisksController::class, 'getCritical'])->name('doctor.patient.risks.critical');
    Route::get('doctor/patients/{patient}/risks/alerts', [\App\Http\Controllers\PatientRisksController::class, 'getAlertRisks'])->name('doctor.patient.risks.alerts');
    Route::get('doctor/patients/{patient}/risks/assess', [\App\Http\Controllers\PatientRisksController::class, 'assessOverallRisk'])->name('doctor.patient.risks.assess');
    Route::patch('doctor/patients/{patient}/risks/{patientRisk}/toggle', [\App\Http\Controllers\PatientRisksController::class, 'toggleStatus'])->name('doctor.patient.risks.toggle');

    Route::resource('doctor/leaves', \App\Http\Controllers\DoctorLeaveController::class)->names([
        'index' => 'doctor.leaves.index',
        'create' => 'doctor.leaves.create',
        'store' => 'doctor.leaves.store',
        'show' => 'doctor.leaves.show',
        'edit' => 'doctor.leaves.edit',
        'update' => 'doctor.leaves.update',
        'destroy' => 'doctor.leaves.destroy',
    ]);

    Route::get('doctor/analytics', [\App\Http\Controllers\DoctorAnalyticsController::class, 'index'])->name('doctor.analytics.index');

    // Doctor Reports routes
    Route::get('doctor/reports/printable', [\App\Http\Controllers\DoctorReportsController::class, 'printable'])->name('doctor.reports.printable');
    Route::get('doctor/reports/printable/generate', [\App\Http\Controllers\DoctorReportsController::class, 'generatePrintable'])->name('doctor.reports.printable.generate');

    // Doctor Messages routes

    // Midwife routes
    Route::resource('midwife/patients', \App\Http\Controllers\MidwifePatientsController::class)->names([
        'index' => 'midwife.patients.index',
        'create' => 'midwife.patients.create',
        'store' => 'midwife.patients.store',
        'show' => 'midwife.patients.show',
        'edit' => 'midwife.patients.edit',
        'update' => 'midwife.patients.update',
        'destroy' => 'midwife.patients.destroy',
    ]);

    Route::resource('midwife/appointments', \App\Http\Controllers\MidwifeAppointmentsController::class)->names([
        'index' => 'midwife.appointments.index',
        'create' => 'midwife.appointments.create',
        'store' => 'midwife.appointments.store',
        'show' => 'midwife.appointments.show',
        'edit' => 'midwife.appointments.edit',
        'update' => 'midwife.appointments.update',
        'destroy' => 'midwife.appointments.destroy',
    ]);

    // Midwife appointment status update
    Route::patch('midwife/appointments/{appointment}/status', [\App\Http\Controllers\MidwifeAppointmentsController::class, 'updateStatus'])->name('midwife.appointments.status.update');

    Route::resource('midwife/announcements', \App\Http\Controllers\MidwifeAnnouncementsController::class)->names([
        'index' => 'midwife.announcements.index',
        'create' => 'midwife.announcements.create',
        'store' => 'midwife.announcements.store',
        'show' => 'midwife.announcements.show',
        'edit' => 'midwife.announcements.edit',
        'update' => 'midwife.announcements.update',
        'destroy' => 'midwife.announcements.destroy',
    ]);

    // Maternal Care Records routes
Route::resource('midwife/maternal', \App\Http\Controllers\MidwifeMaternalController::class)->names('midwife.maternal');

    // Midwife Reports routes
    Route::get('midwife/reports/statistics', [\App\Http\Controllers\MidwifeReportsController::class, 'statistics'])->name('midwife.reports.statistics');
    Route::get('midwife/reports/printable', [\App\Http\Controllers\MidwifeReportsController::class, 'printable'])->name('midwife.reports.printable');
    Route::get('midwife/reports/printable/generate', [\App\Http\Controllers\MidwifeReportsController::class, 'generatePrintable'])->name('midwife.reports.printable.generate');

    // Midwife EHR routes
    Route::resource('midwife/ehr', \App\Http\Controllers\MidwifeEhrController::class)->names([
        'index' => 'midwife.ehr.index',
        'create' => 'midwife.ehr.create',
        'store' => 'midwife.ehr.store',
        'show' => 'midwife.ehr.show',
        'edit' => 'midwife.ehr.edit',
        'update' => 'midwife.ehr.update',
        'destroy' => 'midwife.ehr.destroy',
    ])->parameters([
        'ehr' => 'patient'
    ]);

    // Midwife Messages routes

    // BHW routes
    Route::resource('bhw/patients', \App\Http\Controllers\BHWPatientsController::class)->names([
        'index' => 'bhw.patients.index',
        'create' => 'bhw.patients.create',
        'store' => 'bhw.patients.store',
        'show' => 'bhw.patients.show',
        'edit' => 'bhw.patients.edit',
        'update' => 'bhw.patients.update',
        'destroy' => 'bhw.patients.destroy',
    ]);

    Route::resource('bhw/announcements', \App\Http\Controllers\BHWAnnouncementsController::class)->names([
        'index' => 'bhw.announcements.index',
        'create' => 'bhw.announcements.create',
        'store' => 'bhw.announcements.store',
        'show' => 'bhw.announcements.show',
        'edit' => 'bhw.announcements.edit',
        'update' => 'bhw.announcements.update',
        'destroy' => 'bhw.announcements.destroy',
    ]);

    Route::get('bhw/reports', [\App\Http\Controllers\BHWReportsController::class, 'index'])->name('bhw.reports.index');
    Route::get('bhw/reports/download-csv', [\App\Http\Controllers\BHWReportsController::class, 'downloadCsv'])->name('bhw.reports.download-csv');
    Route::get('bhw/reports/download-pdf', [\App\Http\Controllers\BHWReportsController::class, 'downloadPdf'])->name('bhw.reports.download-pdf');

    Route::resource('bhw/appointments', \App\Http\Controllers\BHWAppointmentsController::class)->names([
        'index' => 'bhw.appointments.index',
        'create' => 'bhw.appointments.create',
        'store' => 'bhw.appointments.store',
        'show' => 'bhw.appointments.show',
        'edit' => 'bhw.appointments.edit',
        'update' => 'bhw.appointments.update',
        'destroy' => 'bhw.appointments.destroy',
    ]);

    // BHW EHR routes
    Route::resource('bhw/ehr', \App\Http\Controllers\BhwEhrController::class)->names([
        'index' => 'bhw.ehr.index',
        'create' => 'bhw.ehr.create',
        'store' => 'bhw.ehr.store',
        'show' => 'bhw.ehr.show',
        'edit' => 'bhw.ehr.edit',
        'update' => 'bhw.ehr.update',
        'destroy' => 'bhw.ehr.destroy',
    ])->parameters([
        'ehr' => 'patient'
    ]);

    // BHW Messages routes

    // Patient routes
    // AJAX route for available slots (must be BEFORE resource route to avoid conflict)
    Route::get('patient/appointments/get-available-slots', [\App\Http\Controllers\PatientAppointmentsController::class, 'getAvailableSlots'])->name('patient.appointments.get-available-slots');

    Route::resource('patient/appointments', \App\Http\Controllers\PatientAppointmentsController::class)->names([
        'index' => 'patient.appointments.index',
        'create' => 'patient.appointments.create',
        'store' => 'patient.appointments.store',
        'show' => 'patient.appointments.show',
        'edit' => 'patient.appointments.edit',
        'update' => 'patient.appointments.update',
        'destroy' => 'patient.appointments.destroy',
    ]);



    Route::resource('patient/prescriptions', \App\Http\Controllers\PatientPrescriptionsController::class)->names([
        'index' => 'patient.prescriptions.index',
        'show' => 'patient.prescriptions.show',
    ])->only(['index', 'show']);

    Route::resource('patient/announcements', \App\Http\Controllers\PatientAnnouncementsController::class)->names([
        'index' => 'patient.announcements.index',
        'show' => 'patient.announcements.show',
    ])->only(['index', 'show']);

    // Patient Medical Data Access (READ-ONLY)
    Route::resource('patient/allergies', \App\Http\Controllers\PatientAllergiesController::class)->names([
        'index' => 'patient.allergies.index',
        'show' => 'patient.allergies.show',
    ])->only(['index', 'show']);

    Route::resource('patient/medications', \App\Http\Controllers\PatientMedicationsController::class)->names([
        'index' => 'patient.medications.index',
        'show' => 'patient.medications.show',
    ])->only(['index', 'show']);

    Route::resource('patient/lab-results', \App\Http\Controllers\PatientLabResultsController::class)->names([
        'index' => 'patient.lab-results.index',
        'show' => 'patient.lab-results.show',
    ])->only(['index', 'show']);



    Route::resource('patient/risks', \App\Http\Controllers\PatientRisksController::class)->names([
        'index' => 'patient.risks.index',
        'show' => 'patient.risks.show',
    ])->only(['index', 'show']);

    // Patient EHR routes
    Route::resource('patient/ehr', \App\Http\Controllers\PatientEhrController::class)->names([
        'index' => 'patient.ehr.index',
        'create' => 'patient.ehr.create',
        'store' => 'patient.ehr.store',
        'show' => 'patient.ehr.show',
        'edit' => 'patient.ehr.edit',
        'update' => 'patient.ehr.update',
        'destroy' => 'patient.ehr.destroy',
    ])->parameters([
        'ehr' => 'ehrRecord'
    ]);

    // Additional Patient EHR routes
    Route::get('patient/ehr/download-pdf', [\App\Http\Controllers\PatientEhrController::class, 'downloadPdf'])->name('patient.ehr.download-pdf');
    Route::get('patient/ehr/{ehrRecord}/download-pdf', [\App\Http\Controllers\PatientEhrController::class, 'downloadSinglePdf'])->name('patient.ehr.download-single-pdf');

    // Patient Maternal Care Records routes
    Route::get('patient/maternal', [\App\Http\Controllers\PatientMaternalController::class, 'index'])->name('patient.maternal.index');
    Route::get('patient/maternal/{maternalCareRecord}', [\App\Http\Controllers\PatientMaternalController::class, 'show'])->name('patient.maternal.show');

    // Patient Messages routes

    // Messages routes
    Route::get('messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::post('messages/{id}/reply', [\App\Http\Controllers\MessageController::class, 'reply'])->name('messages.reply');
    Route::get('messages/chat/{user}', [\App\Http\Controllers\MessageController::class, 'chat'])->name('messages.chat');
    Route::get('messages/create', [\App\Http\Controllers\MessageController::class, 'create'])->name('messages.create');
    Route::post('messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
});

Route::get('/storage/{path}', function ($path) {
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }
    return response()->file(storage_path('app/public/' . $path));
})->where('path', '.*');

require __DIR__.'/auth.php';
