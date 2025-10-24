<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lab Result Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-flask mr-2 text-green-600"></i>
                            {{ $labResult->test_name }}
                        </h3>
                        <a href="{{ route('patient.lab-results.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Lab Results
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Test Information</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Test Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $labResult->test_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($labResult->category) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Test Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $labResult->test_date->format('M d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $labResult->status == 'normal' ? 'bg-green-100 text-green-800' : ($labResult->status == 'abnormal' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($labResult->status) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Result Details</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Result Value</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $labResult->result_value }} {{ $labResult->unit }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Reference Range</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $labResult->reference_range }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Interpretation</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $labResult->interpretation ?: 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $labResult->notes ?: 'No additional notes' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
