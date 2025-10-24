<div class="bg-white shadow-sm rounded-lg">
    <div class="p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h3 class="text-lg font-semibold">Allergies</h3>
            <button onclick="openAllergyModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Allergy
            </button>
        </div>

        @if($patient->allergies->count() > 0)
            <div class="space-y-4">
                @foreach($patient->allergies as $allergy)
                    <div class="border border-gray-200 rounded-lg p-4 {{ $allergy->severity === 'severe' ? 'border-red-300 bg-red-50' : ($allergy->severity === 'moderate' ? 'border-yellow-300 bg-yellow-50' : 'border-green-300 bg-green-50') }}">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <h4 class="font-semibold text-gray-900">{{ $allergy->allergen_name }}</h4>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($allergy->severity === 'severe') bg-red-100 text-red-800
                                        @elseif($allergy->severity === 'moderate') bg-orange-100 text-orange-800
                                        @elseif($allergy->severity === 'mild') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($allergy->severity ?? 'unknown') }}
                                    </span>
                                    @if($allergy->status === 'active')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                    @endif
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 text-sm text-gray-600 mb-3">
                                    <div>
                                        <span class="font-medium">Type:</span> {{ ucfirst($allergy->allergen_type) }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Onset:</span> {{ $allergy->first_occurrence?->format('M d, Y') ?? 'Unknown' }}
                                    </div>
                                    <div class="col-span-2">
                                        <span class="font-medium">Reaction:</span> {{ $allergy->reaction_description ?? 'Not specified' }}
                                    </div>
                                </div>
                                @if($allergy->notes)
                                    <p class="text-sm text-gray-700"><span class="font-medium">Notes:</span> {{ $allergy->notes }}</p>
                                @endif
                                @if($allergy->file_path)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($allergy->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            {{ $allergy->file_name ?? 'Attachment' }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 sm:shrink-0">
                                <button onclick="editAllergy({{ $allergy->id }})" class="inline-flex justify-center items-center px-3 py-2 border border-blue-600 shadow-sm text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('doctor.patient.allergies.destroy', [$patient, $allergy]) }}" onsubmit="return confirm('Are you sure you want to delete this allergy?')" class="inline">
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
                <h3 class="mt-4 text-sm font-medium text-gray-900">No allergies recorded</h3>
                <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Add allergies to track the patient's allergic reactions and sensitivities.</p>
                <div class="mt-6">
                    <button onclick="openAllergyModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add First Allergy
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Allergy Modal -->
<div id="allergyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-md sm:max-w-lg shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" id="modalTitle">Add Allergy</h3>
                <button onclick="closeAllergyModal()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="allergyForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="allergen_type" class="block text-sm font-medium text-gray-700 mb-2">Allergen Type *</label>
                        <select id="allergen_type" name="allergen_type" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Select allergen type</option>
                            <option value="medication">Medication</option>
                            <option value="food">Food</option>
                            <option value="environmental">Environmental</option>
                            <option value="insect">Insect</option>
                            <option value="latex">Latex</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="allergen_name" class="block text-sm font-medium text-gray-700 mb-2">Allergen Name *</label>
                        <input type="text" id="allergen_name" name="allergen_name" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="e.g., Penicillin, Peanuts, Dust">
                    </div>
                    <div>
                        <label for="severity" class="block text-sm font-medium text-gray-700 mb-2">Severity *</label>
                        <select id="severity" name="severity" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="mild">Mild</option>
                            <option value="moderate">Moderate</option>
                            <option value="severe">Severe</option>
                            <option value="life_threatening">Life Threatening</option>
                        </select>
                    </div>
                    <div>
                        <label for="reaction_description" class="block text-sm font-medium text-gray-700 mb-2">Reaction Description *</label>
                        <textarea id="reaction_description" name="reaction_description" rows="3" required
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Describe the allergic reaction"></textarea>
                    </div>
                    <div>
                        <label for="first_occurrence" class="block text-sm font-medium text-gray-700 mb-2">First Occurrence</label>
                        <input type="date" id="first_occurrence" name="first_occurrence"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Additional notes about the allergy"></textarea>
                    </div>
                    <div>
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">Attachment</label>
                        <input type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Upload allergy-related document or image (max 10MB)</p>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="status" name="status" value="active" checked
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="status" class="ml-2 block text-sm text-gray-900">Active allergy</label>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAllergyModal()" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Allergy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let editingAllergyId = null;

    function openAllergyModal(allergyId = null) {
        editingAllergyId = allergyId;
        const modal = document.getElementById('allergyModal');
        const form = document.getElementById('allergyForm');
        const title = document.getElementById('modalTitle');

        if (allergyId) {
            title.textContent = 'Edit Allergy';
            form.action = `/doctor/patients/{{ $patient->id }}/allergies/${allergyId}`;
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
            title.textContent = 'Add Allergy';
            form.action = `/doctor/patients/{{ $patient->id }}/allergies`;
            form.method = 'POST';
            form.reset();
            // Remove method field if exists
            const methodField = form.querySelector('input[name="_method"]');
            if (methodField) methodField.remove();
        }

        modal.classList.remove('hidden');
    }

    function closeAllergyModal() {
        document.getElementById('allergyModal').classList.add('hidden');
        editingAllergyId = null;
    }

    function editAllergy(allergyId) {
        openAllergyModal(allergyId);
        // In a real implementation, you would fetch the allergy data via AJAX
        // For now, this just opens the modal
    }
</script>
