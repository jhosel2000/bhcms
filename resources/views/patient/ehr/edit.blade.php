@extends('layouts.app')

@section('title', 'Edit Medical Record')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Medical Record</h1>
                    <p class="mt-2 text-lg text-gray-600">Update your electronic health record</p>
                </div>
                <a href="{{ route('patient.ehr.show', $ehrRecord) }}" class="text-indigo-600 hover:text-indigo-900">
                    ← Back to Record
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('patient.ehr.update', $ehrRecord) }}" enctype="multipart/form-data">
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
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $ehrRecord->title) }}"
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
                              placeholder="Provide details about this medical record">{{ old('content', $ehrRecord->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload (only for file_upload type) -->
                @if($ehrRecord->record_type === 'file_upload')
                    <div class="mb-6">
                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Add More Files (Optional)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <div class="mt-4">
                                <label for="attachments" class="cursor-pointer">
                                    <span class="mt-2 block text-sm font-medium text-gray-900">Upload additional files</span>
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

                    <!-- Existing Attachments -->
                    @if($ehrRecord->attachments && count($ehrRecord->attachments) > 0)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Current Attachments</h4>
                            <div class="space-y-2">
                                @foreach($ehrRecord->attachments as $index => $file)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $file['original_name'] ?? 'Unknown file' }}</p>
                                                <p class="text-xs text-gray-500">{{ number_format(($file['size'] ?? 0) / 1024, 1) }} KB • Uploaded {{ isset($file['uploaded_at']) ? \Carbon\Carbon::parse($file['uploaded_at'])->format('M d, Y') : 'Unknown date' }}</p>
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
                        </div>
                    @endif
                @endif

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('patient.ehr.show', $ehrRecord) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-md transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Update Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
@if($ehrRecord->record_type === 'file_upload')
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
@endif
</script>
@endsection
