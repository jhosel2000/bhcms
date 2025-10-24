<div class="bg-white shadow-sm rounded-lg">
    <div class="p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h3 class="text-lg font-semibold">Medical Attachments</h3>
            <button onclick="openAttachmentModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Upload Attachment
            </button>
        </div>

        @if($patient->medicalAttachments->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($patient->medicalAttachments as $attachment)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if(in_array(strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION)), ['pdf']))
                                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    @elseif(in_array(strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    @elseif(in_array(strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION)), ['doc', 'docx']))
                                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $attachment->file_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $attachment->uploaded_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        @if($attachment->description)
                            <p class="text-sm text-gray-600 mb-3">{{ $attachment->description }}</p>
                        @endif

                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($attachment->category === 'lab-result') bg-blue-100 text-blue-800
                                @elseif($attachment->category === 'imaging') bg-green-100 text-green-800
                                @elseif($attachment->category === 'prescription') bg-purple-100 text-purple-800
                                @elseif($attachment->category === 'consent') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('-', ' ', $attachment->category ?? 'general')) }}
                            </span>
                            @if($attachment->is_confidential)
                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Confidential</span>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                               class="inline-flex justify-center items-center px-3 py-2 border border-blue-600 shadow-sm text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View
                            </a>
                            <a href="{{ Storage::url($attachment->file_path) }}" download="{{ $attachment->file_name }}"
                               class="inline-flex justify-center items-center px-3 py-2 border border-green-600 shadow-sm text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download
                            </a>
                            <button onclick="editAttachment({{ $attachment->id }})" class="inline-flex justify-center items-center px-3 py-2 border border-indigo-600 shadow-sm text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </button>
                            <form method="POST" action="{{ route('doctor.patients.attachments.destroy', [$patient, $attachment]) }}" onsubmit="return confirm('Are you sure you want to delete this attachment?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex justify-center items-center px-3 py-2 border border-red-600 shadow-sm text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 sm:py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-4 text-sm font-medium text-gray-900">No medical attachments</h3>
                <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Upload medical documents, lab reports, imaging files, and other patient-related attachments.</p>
                <div class="mt-6">
                    <button onclick="openAttachmentModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Upload First Attachment
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Attachment Modal -->
<div id="attachmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" id="attachmentModalTitle">Upload Medical Attachment</h3>
                <button onclick="closeAttachmentModal()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="attachmentForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" name="title" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="e.g., Chest X-Ray Report, Lab Results">
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="category" name="category"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="general">General</option>
                            <option value="lab-result">Lab Result</option>
                            <option value="imaging">Imaging</option>
                            <option value="prescription">Prescription</option>
                            <option value="consent">Consent Form</option>
                            <option value="discharge">Discharge Summary</option>
                            <option value="consultation">Consultation Report</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Brief description of the attachment"></textarea>
                    </div>
                    <div>
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">File *</label>
                        <input type="file" id="attachment" name="attachment" required accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx,.txt"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Supported formats: PDF, Images (JPG, PNG, GIF), Documents (DOC, DOCX, TXT). Max 10MB.</p>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_confidential" name="is_confidential"
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="is_confidential" class="ml-2 block text-sm text-gray-900">Mark as confidential</label>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAttachmentModal()" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Upload Attachment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let editingAttachmentId = null;

    function openAttachmentModal(attachmentId = null) {
        editingAttachmentId = attachmentId;
        const modal = document.getElementById('attachmentModal');
        const form = document.getElementById('attachmentForm');
        const title = document.getElementById('attachmentModalTitle');

        if (attachmentId) {
            title.textContent = 'Edit Medical Attachment';
            form.action = `/doctor/patients/{{ $patient->id }}/attachments/${attachmentId}`;
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
            // Remove required from file input when editing
            document.getElementById('attachment').removeAttribute('required');
        } else {
            title.textContent = 'Upload Medical Attachment';
            form.action = `/doctor/patients/{{ $patient->id }}/attachments`;
            form.method = 'POST';
            form.reset();
            // Remove method field if exists
            const methodField = form.querySelector('input[name="_method"]');
            if (methodField) methodField.remove();
            // Add required to file input
            document.getElementById('attachment').setAttribute('required', 'required');
        }

        modal.classList.remove('hidden');
    }

    function closeAttachmentModal() {
        document.getElementById('attachmentModal').classList.add('hidden');
        editingAttachmentId = null;
    }

    function editAttachment(attachmentId) {
        openAttachmentModal(attachmentId);
        // In a real implementation, you would fetch the attachment data via AJAX
        // For now, this just opens the modal
    }
</script>
