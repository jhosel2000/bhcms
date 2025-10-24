<div class="bg-white shadow-sm rounded-lg">
    <div class="p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h3 class="text-lg font-semibold">Medications</h3>
            <button onclick="openMedicationModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Prescribe Medication
            </button>
        </div>

        @if($patient->medications->count() > 0)
            <div class="space-y-4">
                @foreach($patient->medications as $medication)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex flex-col sm:flex-row sm:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <h4 class="font-semibold text-gray-900">{{ $medication->medication_name }}</h4>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($medication->status === 'active') bg-green-100 text-green-800
                                        @elseif($medication->status === 'discontinued') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($medication->status) }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-600 mb-3">
                                    <div>
                                        <span class="font-medium">Dosage:</span> {{ $medication->dosage }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Frequency:</span> {{ $medication->frequency }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Route:</span> {{ ucfirst($medication->route ?? 'oral') }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Start Date:</span> {{ $medication->start_date?->format('M d, Y') ?? 'Unknown' }}
                                    </div>
                                    @if($medication->end_date)
                                        <div>
                                            <span class="font-medium">End Date:</span> {{ $medication->end_date->format('M d, Y') }}
                                        </div>
                                    @endif
                                    @if($medication->prescribed_by)
                                        <div>
                                            <span class="font-medium">Prescribed By:</span> {{ $medication->prescribed_by }}
                                        </div>
                                    @endif
                                    @if($medication->indication)
                                        <div class="col-span-2">
                                            <span class="font-medium">Indication:</span> {{ $medication->indication }}
                                        </div>
                                    @endif
                                </div>
                                @if($medication->instructions)
                                    <p class="text-sm text-gray-700 mb-2"><span class="font-medium">Instructions:</span> {{ $medication->instructions }}</p>
                                @endif
                                @if($medication->notes)
                                    <p class="text-sm text-gray-500"><span class="font-medium">Notes:</span> {{ $medication->notes }}</p>
                                @endif
                                @if($medication->file_path)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($medication->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            {{ $medication->file_name ?? 'Attachment' }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 sm:shrink-0">
                                <button onclick="editMedication({{ $medication->id }})" class="inline-flex justify-center items-center px-3 py-2 border border-blue-600 shadow-sm text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-2 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                @if($medication->status === 'active')
                                    <form method="POST" action="{{ route('doctor.patient.medications.toggle', [$patient, $medication]) }}" onsubmit="return confirm('Are you sure you want to discontinue this medication?')" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="discontinued">
                                        <button type="submit" class="inline-flex justify-center items-center px-3 py-2 border border-orange-600 shadow-sm text-sm font-medium rounded-md text-orange-600 bg-white hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Discontinue
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('doctor.patient.medications.destroy', [$patient, $medication]) }}" onsubmit="return confirmDeleteMedication(event, this)" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex justify-center items-center px-3 py-2 border border-red-600 shadow-sm text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 sm:py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                </svg>
                <h3 class="mt-4 text-sm font-medium text-gray-900">No medications recorded</h3>
                <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Add medications to track the patient's current prescriptions.</p>
                <div class="mt-6">
                    <button onclick="openMedicationModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Prescribe First Medication
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Medication Modal -->
<div id="medicationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" id="medicationModalTitle">Prescribe Medication</h3>
                <button onclick="closeMedicationModal()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="medicationForm" method="POST" action="{{ route('doctor.patient.medications.store', $patient) }}" enctype="multipart/form-data" onsubmit="return submitMedicationForm(event)">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="medication_name" class="block text-sm font-medium text-gray-700 mb-2">Medication Name *</label>
                        <input type="text" id="medication_name" name="medication_name" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="dosage" class="block text-sm font-medium text-gray-700 mb-2">Dosage *</label>
                            <input type="text" id="dosage" name="dosage" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="e.g., 500mg">
                        </div>
                        <div>
                            <label for="frequency" class="block text-sm font-medium text-gray-700 mb-2">Frequency *</label>
                            <input type="text" id="frequency" name="frequency" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="e.g., twice daily">
                        </div>
                    </div>
                    <div>
                        <label for="route" class="block text-sm font-medium text-gray-700 mb-2">Route</label>
                        <select id="route" name="route"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Select route</option>
                            <option value="oral">Oral</option>
                            <option value="topical">Topical</option>
                            <option value="intravenous">Intravenous</option>
                            <option value="intramuscular">Intramuscular</option>
                            <option value="subcutaneous">Subcutaneous</option>
                            <option value="inhaled">Inhaled</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                            <input type="date" id="start_date" name="start_date" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                            <input type="date" id="end_date" name="end_date"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="prescribed_by" class="block text-sm font-medium text-gray-700 mb-2">Prescribed By</label>
                        <input type="text" id="prescribed_by" name="prescribed_by"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Doctor's name">
                    </div>
                    <div>
                        <label for="indication" class="block text-sm font-medium text-gray-700 mb-2">Indication</label>
                        <input type="text" id="indication" name="indication"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Reason for prescription">
                    </div>
                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                        <textarea id="instructions" name="instructions" rows="2"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="How to take the medication"></textarea>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea id="notes" name="notes" rows="2"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Additional notes"></textarea>
                    </div>
                    <div>
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">Attachment</label>
                        <input type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Upload prescription or medication-related document (max 10MB)</p>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="active">Active</option>
                            <option value="discontinued">Discontinued</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeMedicationModal()" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Medication
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let editingMedicationId = null;

    function openMedicationModal(medicationId = null) {
        editingMedicationId = medicationId;
        const modal = document.getElementById('medicationModal');
        const form = document.getElementById('medicationForm');
        const title = document.getElementById('medicationModalTitle');

        if (medicationId) {
            title.textContent = 'Edit Medication';
            form.action = `{{ route('doctor.patient.medications.index', $patient) }}/${medicationId}`;
            form.method = 'POST';
            // Add hidden method field for PUT
            let methodField = form.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);
            }
        } else {
            title.textContent = 'Prescribe Medication';
            form.action = `{{ route('doctor.patient.medications.store', $patient) }}`;
            form.method = 'POST';
            // Reset form but preserve CSRF token
            const csrfToken = form.querySelector('input[name="_token"]').value;
            form.reset();
            form.querySelector('input[name="_token"]').value = csrfToken;
            // Set default start date to today
            document.getElementById('start_date').valueAsDate = new Date();
            // Remove method field if exists
            const methodField = form.querySelector('input[name="_method"]');
            if (methodField) methodField.remove();
        }

        modal.classList.remove('hidden');
    }

    function closeMedicationModal() {
        document.getElementById('medicationModal').classList.add('hidden');
        editingMedicationId = null;
    }

    async function editMedication(medicationId) {
        openMedicationModal(medicationId);
        const form = document.getElementById('medicationForm');
        const title = document.getElementById('medicationModalTitle');
        title.textContent = 'Edit Medication';

        try {
            const url = `{{ route('doctor.patient.medications.show', [$patient, ':id']) }}`.replace(':id', medicationId);
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!response.ok) throw new Error('Failed to fetch medication data');
            const data = await response.json();

            // Populate form fields with fetched data
            form.action = `{{ url('doctor/patients/' . $patient->id . '/medications') }}/${medicationId}`;
            form.method = 'POST';

            // Add hidden method field for PUT if not present
            let methodField = form.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);
            }

            // Fill inputs
            form.medication_name.value = data.medication_name || '';
            form.dosage.value = data.dosage || '';
            form.frequency.value = data.frequency || '';
            form.route.value = data.route || '';
            form.start_date.value = data.start_date || '';
            form.end_date.value = data.end_date || '';
            form.prescribed_by.value = data.prescribed_by || '';
            form.indication.value = data.indication || '';
            form.instructions.value = data.instructions || '';
            form.notes.value = data.notes || '';
            form.status.value = data.status || 'active';
            // Attachment input cannot be prefilled for security reasons

        } catch (error) {
            alert('Error loading medication data for editing: ' + error.message);
            closeMedicationModal();
        }
    }

    async function submitMedicationForm(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                alert('Failed to save medication: ' + (errorData.message || 'Unknown error'));
                return false;
            }

            const data = await response.json();
            closeMedicationModal();
            // Optionally, refresh the page or update the medications list dynamically
            location.reload();

        } catch (error) {
            alert('Error submitting form: ' + error.message);
        }

        return false;
    }
</script>

<script>
    async function confirmDeleteMedication(event, form) {
        event.preventDefault();
        if (!confirm('Are you sure you want to delete this medication record?')) {
            return false;
        }

        try {
            // Use POST method with _method=DELETE for Laravel compatibility
            const formData = new FormData(form);
            formData.append('_method', 'DELETE');

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                alert('Failed to delete medication: ' + (errorData.message || 'Unknown error'));
                return false;
            }

            // Optionally, remove the deleted medication from the DOM or reload the page
            location.reload();

        } catch (error) {
            alert('Error deleting medication: ' + error.message);
        }

        return false;
    }
</script>
