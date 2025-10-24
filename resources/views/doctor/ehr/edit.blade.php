@extends('layouts.app')

@section('title', 'Edit EHR Record - ' . $patient->full_name)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit EHR Record</h1>
                    <p class="mt-2 text-lg text-gray-600">For {{ $patient->full_name }}</p>
                </div>
                <a href="{{ route('doctor.ehr.show', $patient) }}" class="text-indigo-600 hover:text-indigo-900">
                    ← Back to Records
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('doctor.ehr.update', [$patient, $ehrRecord]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Record Type (Read-only for existing records) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Record Type</label>
                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700">
                        {{ $ehrRecord->record_type_display }}
                    </div>
                </div>

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" id="title" required value="{{ old('title', $ehrRecord->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Brief description of the record">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Brief description of the record">{{ old('description', $ehrRecord->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Additional clinical notes or observations">{{ old('notes', $ehrRecord->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Add More Attachments (Optional)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="attachments" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload additional files</span>
                                    <input id="attachments" name="attachments[]" type="file" multiple class="sr-only" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG up to 10MB each</p>
                        </div>
                    </div>
                    <div id="file-list" class="mt-3 space-y-2"></div>
                    @error('attachments')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('attachments.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Existing Attachments -->
                @if($ehrRecord->uploaded_files && count($ehrRecord->uploaded_files) > 0)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Existing Attachments</label>
                        <div class="space-y-2">
                            @foreach($ehrRecord->uploaded_files as $index => $file)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $file['original_name'] ?? 'Unknown file' }}</p>
                                            <p class="text-xs text-gray-500">{{ number_format(($file['size'] ?? 0) / 1024, 1) }} KB</p>
                                        </div>
                                    </div>
                                    @if(isset($file['path']))
                                        <a href="{{ Storage::url($file['path']) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                    @else
                                        <span class="text-gray-400 cursor-not-allowed">
                                    @endif
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Existing attachments will be preserved. New files will be added to the record.</p>
                    </div>
                @endif

                <!-- Review Controls -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Status</h3>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Current Status:</p>
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($ehrRecord->status === \App\Models\EhrRecord::STATUS_APPROVED)
                                    bg-green-100 text-green-800
                                @elseif($ehrRecord->status === \App\Models\EhrRecord::STATUS_FLAGGED)
                                    bg-red-100 text-red-800
                                @else
                                    bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $ehrRecord->status_display }}
                            </div>
                            @if($ehrRecord->reviewed_at)
                                <p class="mt-2 text-xs text-gray-500">Reviewed {{ $ehrRecord->reviewed_at->diffForHumans() }} by {{ optional($ehrRecord->reviewer)->full_name ?? 'Unknown' }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="review_notes" class="block text-sm font-medium text-gray-700 mb-2">Review Notes</label>
                            <textarea
                                id="review_notes"
                                name="review_notes"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Add your assessment, follow-up instructions, or reasons for flagging." >{{ old('review_notes', $ehrRecord->review_notes) }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">Notes are visible to midwives and BHWs to coordinate next steps.</p>
                        </div>

                        @error('review_notes')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="flex flex-wrap gap-3">
                            <button
                                type="submit"
                                name="status_action"
                                value="{{ \App\Models\EhrRecord::STATUS_APPROVED }}"
                                class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                Approve Record
                            </button>

                            <button
                                type="submit"
                                name="status_action"
                                value="{{ \App\Models\EhrRecord::STATUS_FLAGGED }}"
                                class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                Flag for Follow-up
                            </button>

                            <a href="{{ route('doctor.ehr.show', $patient) }}"
                               class="inline-flex items-center bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('attachments').addEventListener('change', function(e) {
    const fileList = document.getElementById('file-list');
    fileList.innerHTML = '';

    Array.from(e.target.files).forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded-md';
        fileItem.innerHTML = `
            <div class="flex items-center">
                <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm text-gray-700">${file.name}</span>
                <span class="text-xs text-gray-500 ml-2">(${Math.round(file.size / 1024)} KB)</span>
            </div>
            <button type="button" onclick="removeFile(${index})" class="text-red-600 hover:text-red-900">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
        fileList.appendChild(fileItem);
    });
});

function removeFile(index) {
    const input = document.getElementById('attachments');
    const dt = new DataTransfer();
    const files = Array.from(input.files);

    files.splice(index, 1);

    files.forEach(file => dt.items.add(file));
    input.files = dt.files;

    // Trigger change event to update display
    input.dispatchEvent(new Event('change'));
}
</script>
@endsection
