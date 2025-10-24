<div class="bg-white shadow-sm rounded-lg">
    <div class="p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h3 class="text-lg font-semibold">Lab Results</h3>
            <button onclick="openLabResultModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Lab Result
            </button>
        </div>

        @if($patient->labResults->count() > 0)
            <div class="space-y-4">
                @foreach($patient->labResults->take(5) as $result)
                    <div class="border border-gray-200 rounded-lg p-4 {{ $result->status === 'critical' ? 'border-red-300 bg-red-50' : ($result->status === 'abnormal' ? 'border-yellow-300 bg-yellow-50' : 'border-green-300 bg-green-50') }}">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <h4 class="font-semibold text-gray-900">{{ $result->test_name }}</h4>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($result->status === 'normal') bg-green-100 text-green-800
                                        @elseif($result->status === 'abnormal') bg-yellow-100 text-yellow-800
                                        @elseif($result->status === 'critical') bg-red-100 text-red-800
                                        @elseif($result->status === 'pending') bg-gray-100 text-gray-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst($result->status ?? 'unknown') }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($result->category ?? 'general') }}
                                    </span>
                                    @if($result->test_date)
                                        <span class="text-sm text-gray-500">{{ $result->test_date->format('M d, Y') }}</span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 text-sm text-gray-600 mb-3">
                                    <div>
                                        <span class="font-medium">Result:</span> {{ $result->result_value }} {{ $result->unit }}
                                    </div>
                                    @if($result->reference_range)
                                        <div>
                                            <span class="font-medium">Reference:</span> {{ $result->reference_range }}
                                        </div>
                                    @endif
                                    @if($result->test_code)
                                        <div>
                                            <span class="font-medium">Test Code:</span> {{ $result->test_code }}
                                        </div>
                                    @endif
                                    @if($result->result_date)
                                        <div>
                                            <span class="font-medium">Result Date:</span> {{ $result->result_date->format('M d, Y') }}
                                        </div>
                                    @endif
                                </div>

                                @if($result->interpretation)
                                    <div class="mb-3">
                                        <span class="font-medium text-gray-700">Interpretation:</span>
                                        <p class="text-sm text-gray-600 mt-1">{{ $result->interpretation }}</p>
                                    </div>
                                @endif

                                @if($result->notes)
                                    <p class="text-sm text-gray-700"><span class="font-medium">Notes:</span> {{ $result->notes }}</p>
                                @endif

                                @if($result->file_path)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($result->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            {{ $result->file_name ?? 'Attachment' }}
                                        </a>
                                    </div>
                                @endif

                                <div class="mt-3 text-xs text-gray-500">
                                    Ordered by {{ $result->ordered_by ?? 'Unknown' }} • Performed by {{ $result->performed_by ?? 'Unknown' }}
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2 sm:shrink-0">
                                <button onclick="editLabResult({{ $result->id }})" class="inline-flex justify-center items-center px-3 py-2 border border-blue-600 shadow-sm text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('doctor.patient.lab-results.destroy', [$patient, $result]) }}" onsubmit="return confirmDeleteLabResult(event, this)" class="inline">
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

                @if($patient->labResults->count() > 5)
                    <div class="text-center pt-4">
                        <a href="{{ route('doctor.patient.lab-results.index', $patient) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            View All Lab Results ({{ $patient->labResults->count() }})
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-8 sm:py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4"></path>
                </svg>
                <h3 class="mt-4 text-sm font-medium text-gray-900">No lab results recorded</h3>
                <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Add lab results to track the patient's test results and monitor their health status.</p>
                <div class="mt-6">
                    <button onclick="openLabResultModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add First Lab Result
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Lab Result Modal -->
<div id="labResultModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" id="labResultModalTitle">Add Lab Result</h3>
                <button onclick="closeLabResultModal()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="labResultForm" method="POST" action="{{ route('doctor.patient.lab-results.store', $patient) }}" enctype="multipart/form-data" onsubmit="return submitLabResultForm(event)">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="test_name" class="block text-sm font-medium text-gray-700 mb-2">Test Name *</label>
                            <input type="text" id="test_name" name="test_name" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="e.g., Complete Blood Count">
                        </div>
                        <div>
                            <label for="test_code" class="block text-sm font-medium text-gray-700 mb-2">Test Code</label>
                            <input type="text" id="test_code" name="test_code"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="e.g., CBC">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select id="category" name="category" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Select category</option>
                                <option value="hematology">Hematology</option>
                                <option value="chemistry">Chemistry</option>
                                <option value="microbiology">Microbiology</option>
                                <option value="immunology">Immunology</option>
                                <option value="urinalysis">Urinalysis</option>
                                <option value="parasitology">Parasitology</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="pending">Pending</option>
                                <option value="normal">Normal</option>
                                <option value="abnormal">Abnormal</option>
                                <option value="critical">Critical</option>
                                <option value="reviewed">Reviewed</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="result_value" class="block text-sm font-medium text-gray-700 mb-2">Result Value *</label>
                            <input type="text" id="result_value" name="result_value" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="e.g., 12.5">
                        </div>
                        <div>
                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                            <input type="text" id="unit" name="unit"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="e.g., mg/dL">
                        </div>
                        <div>
                            <label for="reference_range" class="block text-sm font-medium text-gray-700 mb-2">Reference Range</label>
                            <input type="text" id="reference_range" name="reference_range"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="e.g., 10-15">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="test_date" class="block text-sm font-medium text-gray-700 mb-2">Test Date *</label>
                            <input type="date" id="test_date" name="test_date" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="result_date" class="block text-sm font-medium text-gray-700 mb-2">Result Date</label>
                            <input type="date" id="result_date" name="result_date"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="ordered_by" class="block text-sm font-medium text-gray-700 mb-2">Ordered By</label>
                            <input type="text" id="ordered_by" name="ordered_by"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Doctor's name">
                        </div>
                        <div>
                            <label for="performed_by" class="block text-sm font-medium text-gray-700 mb-2">Performed By</label>
                            <input type="text" id="performed_by" name="performed_by"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Lab technician">
                        </div>
                    </div>

                    <div>
                        <label for="interpretation" class="block text-sm font-medium text-gray-700 mb-2">Interpretation</label>
                        <textarea id="interpretation" name="interpretation" rows="2"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Clinical interpretation of the result"></textarea>
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
                        <p class="mt-1 text-sm text-gray-500">Upload lab result document (max 10MB)</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeLabResultModal()" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Lab Result
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let editingLabResultId = null;

    function openLabResultModal(labResultId = null) {
        editingLabResultId = labResultId;
        const modal = document.getElementById('labResultModal');
        const form = document.getElementById('labResultForm');
        const title = document.getElementById('labResultModalTitle');

        if (labResultId) {
            title.textContent = 'Edit Lab Result';
            form.action = `{{ route('doctor.patient.lab-results.index', $patient) }}/${labResultId}`;
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
            title.textContent = 'Add Lab Result';
            form.action = `{{ route('doctor.patient.lab-results.store', $patient) }}`;
            form.method = 'POST';
            // Reset form but preserve CSRF token
            const csrfToken = form.querySelector('input[name="_token"]').value;
            form.reset();
            form.querySelector('input[name="_token"]').value = csrfToken;
            // Set default test date to today
            document.getElementById('test_date').valueAsDate = new Date();
            // Remove method field if exists
            const methodField = form.querySelector('input[name="_method"]');
            if (methodField) methodField.remove();
        }

        modal.classList.remove('hidden');
    }

    function closeLabResultModal() {
        document.getElementById('labResultModal').classList.add('hidden');
        editingLabResultId = null;
    }

    async function editLabResult(labResultId) {
        openLabResultModal(labResultId);
        const form = document.getElementById('labResultForm');
        const title = document.getElementById('labResultModalTitle');
        title.textContent = 'Edit Lab Result';

        try {
            const response = await fetch(`{{ url('doctor/patients/' . $patient->id . '/lab-results') }}/${labResultId}`);
            if (!response.ok) throw new Error('Failed to fetch lab result data');
            const data = await response.json();

            // Populate form fields with fetched data
            form.action = `{{ url('doctor/patients/' . $patient->id . '/lab-results') }}/${labResultId}`;
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
            form.test_name.value = data.test_name || '';
            form.test_code.value = data.test_code || '';
            form.category.value = data.category || '';
            form.status.value = data.status || 'pending';
            form.result_value.value = data.result_value || '';
            form.unit.value = data.unit || '';
            form.reference_range.value = data.reference_range || '';
            form.test_date.value = data.test_date || '';
            form.result_date.value = data.result_date || '';
            form.ordered_by.value = data.ordered_by || '';
            form.performed_by.value = data.performed_by || '';
            form.interpretation.value = data.interpretation || '';
            form.notes.value = data.notes || '';
            // Attachment input cannot be prefilled for security reasons

        } catch (error) {
            alert('Error loading lab result data for editing: ' + error.message);
            closeLabResultModal();
        }
    }

    async function submitLabResultForm(event) {
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
                alert('Failed to save lab result: ' + (errorData.message || 'Unknown error'));
                return false;
            }

            const data = await response.json();
            closeLabResultModal();
            // Optionally, refresh the page or update the lab results list dynamically
            location.reload();

        } catch (error) {
            alert('Error submitting form: ' + error.message);
        }

        return false;
    }
</script>

<script>
    async function confirmDeleteLabResult(event, form) {
        event.preventDefault();
        if (!confirm('Are you sure you want to delete this lab result?')) {
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
                alert('Failed to delete lab result: ' + (errorData.message || 'Unknown error'));
                return false;
            }

            // Optionally, remove the deleted lab result from the DOM or reload the page
            location.reload();

        } catch (error) {
            alert('Error deleting lab result: ' + error.message);
        }

        return false;
    }
</script>
