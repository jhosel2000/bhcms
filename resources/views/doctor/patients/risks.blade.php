<div class="bg-white shadow-sm rounded-lg">
    <div class="p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h3 class="text-lg font-semibold">Patient Risks</h3>
            <button onclick="openRiskModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                Add Risk Assessment
            </button>
        </div>

        @if($patient->risks->count() > 0)
            <div class="space-y-4">
                @foreach($patient->risks as $risk)
                    <div class="border border-gray-200 rounded-lg p-4 {{ $risk->severity === 'critical' ? 'border-red-300 bg-red-50' : ($risk->severity === 'high' ? 'border-orange-300 bg-orange-50' : ($risk->severity === 'medium' ? 'border-yellow-300 bg-yellow-50' : 'border-green-300 bg-green-50')) }}">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <h4 class="font-semibold text-gray-900">{{ $risk->title }}</h4>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($risk->severity === 'critical') bg-red-100 text-red-800
                                        @elseif($risk->severity === 'high') bg-orange-100 text-orange-800
                                        @elseif($risk->severity === 'medium') bg-yellow-100 text-yellow-800
                                        @elseif($risk->severity === 'low') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($risk->severity ?? 'unknown') }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($risk->status === 'active') bg-green-100 text-green-800
                                        @elseif($risk->status === 'resolved') bg-blue-100 text-blue-800
                                        @elseif($risk->status === 'monitoring') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($risk->status ?? 'unknown') }}
                                    </span>
                                    @if($risk->requires_alert)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Alert
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 text-sm text-gray-600 mb-3">
                                    <div>
                                        <span class="font-medium">Type:</span> {{ ucfirst($risk->risk_type ?? 'General') }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Identified:</span> {{ $risk->identified_date?->format('M d, Y') ?? 'Unknown' }}
                                    </div>
                                    @if($risk->review_date)
                                        <div>
                                            <span class="font-medium">Next Review:</span> {{ $risk->review_date->format('M d, Y') }}
                                        </div>
                                    @endif
                                    <div>
                                        <span class="font-medium">Identified by:</span> {{ $risk->doctor->name ?? 'Unknown' }}
                                    </div>
                                </div>

                                @if($risk->description)
                                    <div class="mb-3">
                                        <span class="font-medium text-gray-700">Description:</span>
                                        <p class="text-sm text-gray-600 mt-1">{{ $risk->description }}</p>
                                    </div>
                                @endif

                                @if($risk->management_plan)
                                    <div class="mb-3">
                                        <span class="font-medium text-gray-700">Management Plan:</span>
                                        <p class="text-sm text-gray-600 mt-1">{{ $risk->management_plan }}</p>
                                    </div>
                                @endif

                                @if($risk->notes)
                                    <p class="text-sm text-gray-700"><span class="font-medium">Notes:</span> {{ $risk->notes }}</p>
                                @endif

                                @if($risk->file_path)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($risk->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            {{ $risk->file_name ?? 'Attachment' }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2 sm:shrink-0">
                                <button onclick="editRisk({{ $risk->id }})" class="inline-flex justify-center items-center px-3 py-2 border border-blue-600 shadow-sm text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('doctor.patient.risks.destroy', [$patient, $risk]) }}" onsubmit="return confirmDeleteRisk(this);" class="inline">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h3 class="mt-4 text-sm font-medium text-gray-900">No risk assessments recorded</h3>
                <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Add risk assessments to identify and manage potential health risks for this patient.</p>
                <div class="mt-6">
                    <button onclick="openRiskModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Add First Risk Assessment
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Risk Modal -->
<div id="riskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" id="riskModalTitle">Add Risk Assessment</h3>
                <button onclick="closeRiskModal()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="riskForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="risk_type" class="block text-sm font-medium text-gray-700 mb-2">Risk Type *</label>
                        <select id="risk_type" name="risk_type" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Select risk type</option>
                            <option value="cardiovascular">Cardiovascular</option>
                            <option value="diabetes">Diabetes</option>
                            <option value="cancer">Cancer</option>
                            <option value="mental_health">Mental Health</option>
                            <option value="infection">Infection</option>
                            <option value="fall_risk">Fall Risk</option>
                            <option value="medication">Medication</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Risk Title *</label>
                        <input type="text" id="title" name="title" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="e.g., High Blood Pressure Risk">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="severity" class="block text-sm font-medium text-gray-700 mb-2">Severity *</label>
                            <select id="severity" name="severity" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="active">Active</option>
                                <option value="monitoring">Monitoring</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="identified_date" class="block text-sm font-medium text-gray-700 mb-2">Identified Date *</label>
                            <input type="date" id="identified_date" name="identified_date" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="review_date" class="block text-sm font-medium text-gray-700 mb-2">Next Review Date</label>
                            <input type="date" id="review_date" name="review_date"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea id="description" name="description" rows="3" required
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Detailed description of the risk"></textarea>
                    </div>

                    <div>
                        <label for="management_plan" class="block text-sm font-medium text-gray-700 mb-2">Management Plan</label>
                        <textarea id="management_plan" name="management_plan" rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Plan to manage or mitigate this risk"></textarea>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea id="notes" name="notes" rows="2"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Additional notes"></textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="requires_alert" name="requires_alert" value="1"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="requires_alert" class="ml-2 block text-sm text-gray-900">Requires immediate attention/alert</label>
                    </div>

                    <div>
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">Attachment</label>
                        <input type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Upload risk assessment document (max 10MB)</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeRiskModal()" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn" class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Risk Assessment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let editingRiskId = null;

    function openRiskModal(riskId = null) {
        editingRiskId = riskId;
        const modal = document.getElementById('riskModal');
        const form = document.getElementById('riskForm');
        const title = document.getElementById('riskModalTitle');

        if (riskId) {
            title.textContent = 'Edit Risk Assessment';
            form.action = `{{ route('doctor.patient.risks.update', [$patient, ':riskId']) }}`.replace(':riskId', riskId);
            // Add hidden method field for PUT
            let methodField = form.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);
            }
            // Fetch and populate risk data
            fetchRiskData(riskId);
        } else {
            title.textContent = 'Add Risk Assessment';
            form.action = `{{ route('doctor.patient.risks.store', $patient) }}`;
            form.method = 'POST';
            form.reset();
            // Set default identified date to today
            document.getElementById('identified_date').valueAsDate = new Date();
            // Remove method field if exists
            const methodField = form.querySelector('input[name="_method"]');
            if (methodField) methodField.remove();
        }

        modal.classList.remove('hidden');
    }

    function fetchRiskData(riskId) {
        // Fetch risk data from the server
        fetch(`{{ route('doctor.patient.risks.show', [$patient, ':riskId']) }}`.replace(':riskId', riskId), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data) {
                populateForm(data);
            }
        })
        .catch(error => {
            console.error('Error fetching risk data:', error);
        });
    }

    function populateForm(risk) {
        document.getElementById('risk_type').value = risk.risk_type || '';
        document.getElementById('title').value = risk.title || '';
        document.getElementById('severity').value = risk.severity || 'low';
        document.getElementById('status').value = risk.status || 'active';
        document.getElementById('identified_date').value = risk.identified_date ? risk.identified_date.split('T')[0] : '';
        document.getElementById('review_date').value = risk.review_date ? risk.review_date.split('T')[0] : '';
        document.getElementById('description').value = risk.description || '';
        document.getElementById('management_plan').value = risk.management_plan || '';
        document.getElementById('notes').value = risk.notes || '';
        document.getElementById('requires_alert').checked = risk.requires_alert || false;
    }

    function closeRiskModal() {
        document.getElementById('riskModal').classList.add('hidden');
        editingRiskId = null;
    }

    function editRisk(riskId) {
        openRiskModal(riskId);
    }

    // Form submission handler
    document.getElementById('riskForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;

        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';

        // Determine method
        const method = form.querySelector('input[name="_method"]') ? 'POST' : 'POST';

        fetch(form.action, {
            method: method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success !== false) {
                // Success - close modal and reload page
                closeRiskModal();
                location.reload();
            } else {
                // Handle validation errors
                if (data.errors) {
                    displayValidationErrors(data.errors);
                } else {
                    alert('An error occurred while saving the risk assessment.');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the risk assessment.');
        })
        .finally(() => {
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    function displayValidationErrors(errors) {
        // Clear previous errors
        document.querySelectorAll('.error-message').forEach(el => el.remove());

        // Display new errors
        for (const [field, messages] of Object.entries(errors)) {
            const input = document.getElementById(field);
            if (input) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message text-red-600 text-sm mt-1';
                errorDiv.textContent = messages[0];
                input.parentNode.appendChild(errorDiv);
            }
        }
    }

    function deleteRisk(riskId, buttonElement) {
        // This function is no longer used since deletion is handled by form submission
    }

    function confirmDeleteRisk(form) {
        if (!confirm('Are you sure you want to delete this risk assessment?')) {
            return false;
        }

        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;

        // Disable button and show loading
        submitButton.disabled = true;
        submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Deleting...';

        return true; // Allow form submission
    }
</script>
