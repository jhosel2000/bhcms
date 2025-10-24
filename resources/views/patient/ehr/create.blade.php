@extends('layouts.app')

@section('title', 'Add New Medical Record')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New Medical Record</h1>
                    <p class="mt-2 text-lg text-gray-600">Add a new entry to your electronic health records</p>
                </div>
                <a href="{{ route('patient.ehr.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    ← Back to Records
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('patient.ehr.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Record Type -->
                <div class="mb-6">
                    <label for="record_type" class="block text-sm font-medium text-gray-700 mb-2">Record Type</label>
                    <select name="record_type" id="record_type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Select a record type</option>
                        <option value="file_upload">File Upload</option>
                        <option value="personal_note">Personal Note</option>
                        <option value="other">Other</option>
                    </select>
                    @error('record_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Enter a descriptive title" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="content" id="content" rows="6"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Provide details about this medical record">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="mb-6" id="file-upload-section" style="display: none;">
                    <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Upload Files</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <div class="mt-4">
                            <label for="attachments" class="cursor-pointer">
                                <span class="mt-2 block text-sm font-medium text-gray-900">Upload files</span>
                                <span class="mt-1 block text-sm text-gray-500">PDF, DOC, DOCX, JPG, JPEG, PNG up to 10MB each</span>
                            </label>
                            <input type="file" name="attachments[]" id="attachments" multiple
                                   class="sr-only" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <div id="file-list" class="mt-4 space-y-2"></div>
                    @error('attachments')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('attachments.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('patient.ehr.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-md transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Save Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('record_type').addEventListener('change', function() {
    const fileUploadSection = document.getElementById('file-upload-section');
    if (this.value === 'file_upload') {
        fileUploadSection.style.display = 'block';
    } else {
        fileUploadSection.style.display = 'none';
    }
});

document.getElementById('attachments').addEventListener('change', function(e) {
    const fileList = document.getElementById('file-list');
    fileList.innerHTML = '';

    Array.from(e.target.files).forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
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

    Array.from(input.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });

    input.files = dt.files;

    // Trigger change event to update file list
    const event = new Event('change');
    input.dispatchEvent(event);
}
</script>
@endsection
