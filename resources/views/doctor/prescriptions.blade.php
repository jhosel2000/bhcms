{{--
    Doctor Prescriptions Management - Modular Version
    Full-featured prescription management with AJAX search and filtering
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Prescription Management') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage patient prescriptions and medication tracking</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('doctor.prescriptions.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Prescription
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Main Content Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Page Header with Statistics --}}
                <div class="border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-5">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                Prescription Management
                            </h3>
                            <p class="text-sm text-gray-600 leading-relaxed max-w-3xl">
                                Create, manage, and track patient prescriptions. Monitor active medications and refill requests.
                            </p>
                        </div>
                    </div>

                    {{-- Statistics Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        {{-- Total Prescriptions --}}
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Prescriptions</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                                    <p class="text-xs text-gray-400 mt-1">All time</p>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Active Prescriptions --}}
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Active</p>
                                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['active'] }}</p>
                                    <p class="text-xs text-green-500 mt-1">Currently prescribed</p>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- This Month --}}
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">This Month</p>
                                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['this_month'] }}</p>
                                    <p class="text-xs text-purple-500 mt-1">Recent activity</p>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Pending Refills --}}
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:scale-105">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Pending Refills</p>
                                    <p class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['pending_refill'] }}</p>
                                    <p class="text-xs text-orange-500 mt-1">Needs attention</p>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Info --}}
                    @if($stats['most_common'])
                        <div class="mt-4 flex items-center text-sm text-green-700 bg-green-100 rounded-lg px-4 py-2.5 border border-green-200">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span>Most prescribed medication: <strong>{{ $stats['most_common']->medication_name }}</strong> ({{ $stats['most_common']->count }} times)</span>
                        </div>
                    @endif
                </div>

                {{-- Search Bar Component --}}
                <x-prescription-filters :status="$status" />

                {{-- Status Tabs --}}
                <div class="px-6 pt-6 pb-4 border-b border-gray-200 bg-gray-50">
                    <nav class="flex flex-wrap gap-3" aria-label="Prescription Status Tabs">
                        <a href="{{ route('doctor.prescriptions.index', array_merge(request()->except('status'), ['status' => 'all'])) }}"
                           class="tab-link {{ $status === 'all' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg transform scale-105' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}
                                  px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-300 ease-in-out hover:shadow-md">
                            <span class="flex items-center gap-3">
                                <svg class="w-5 h-5 {{ $status === 'all' ? 'text-green-100' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                <span>All Prescriptions</span>
                                <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full {{ $status === 'all' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }} min-w-[2rem]">
                                    {{ $stats['total'] }}
                                </span>
                            </span>
                        </a>
                        <a href="{{ route('doctor.prescriptions.index', array_merge(request()->except('status'), ['status' => 'active'])) }}"
                           class="tab-link {{ $status === 'active' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg transform scale-105' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}
                                  px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-300 ease-in-out hover:shadow-md">
                            <span class="flex items-center gap-3">
                                <svg class="w-5 h-5 {{ $status === 'active' ? 'text-green-100' : 'text-green-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Active</span>
                                <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full {{ $status === 'active' ? 'bg-green-500 text-white' : 'bg-green-100 text-green-700' }} min-w-[2rem]">
                                    {{ $stats['active'] }}
                                </span>
                            </span>
                        </a>
                        <a href="{{ route('doctor.prescriptions.index', array_merge(request()->except('status'), ['status' => 'completed'])) }}"
                           class="tab-link {{ $status === 'completed' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg transform scale-105' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}
                                  px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-300 ease-in-out hover:shadow-md">
                            <span class="flex items-center gap-3">
                                <svg class="w-5 h-5 {{ $status === 'completed' ? 'text-green-100' : 'text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Completed</span>
                                <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full {{ $status === 'completed' ? 'bg-green-500 text-white' : 'bg-blue-100 text-blue-700' }} min-w-[2rem]">
                                    {{ $stats['completed'] }}
                                </span>
                            </span>
                        </a>
                        <a href="{{ route('doctor.prescriptions.index', array_merge(request()->except('status'), ['status' => 'pending_refill'])) }}"
                           class="tab-link {{ $status === 'pending_refill' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg transform scale-105' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' }}
                                  px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-300 ease-in-out hover:shadow-md">
                            <span class="flex items-center gap-3">
                                <svg class="w-5 h-5 {{ $status === 'pending_refill' ? 'text-green-100' : 'text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Pending Refill</span>
                                <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full {{ $status === 'pending_refill' ? 'bg-green-500 text-white' : 'bg-orange-100 text-orange-700' }} min-w-[2rem]">
                                    {{ $stats['pending_refill'] }}
                                </span>
                            </span>
                        </a>
                    </nav>
                </div>

                {{-- Prescriptions List --}}
                <div class="px-6 py-6">
                    {{-- Include the prescription list partial --}}
                    <div id="prescriptionsList">
                        @include('doctor.partials.prescription-list', ['prescriptions' => $prescriptions])
                    </div>

                    {{-- Pagination --}}
                    <div id="paginationContainer" class="mt-8 flex justify-center">
                        @if($prescriptions->hasPages())
                            {{ $prescriptions->appends(request()->except('page'))->links() }}
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modals --}}
    @php
        $patients = \App\Models\Patient::orderBy('full_name')->get();
    @endphp
    <x-prescription-create-modal :patients="$patients" />
    <x-prescription-edit-modal :patients="$patients" />
    {{-- Enhanced AJAX Search and Modal Management Script --}}
    <script>
        // AJAX Search Configuration
        const searchForm = document.querySelector('form[action*="prescriptions.index"]');
        const searchInput = document.getElementById('search');
        const prescriptionsList = document.getElementById('prescriptionsList');
        const paginationContainer = document.getElementById('paginationContainer');
        let searchTimeout = null;

        // Focus search bar with Ctrl+K or Cmd+K
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
            }
        });

        // AJAX Search Function
        function performSearch(url = null) {
            const formData = new FormData(searchForm);
            const searchParams = new URLSearchParams(formData);
            const searchUrl = url || searchForm.action + '?' + searchParams.toString();

            // Show loading state
            const searchBtn = searchForm.querySelector('button[type="submit"]');
            const originalBtnContent = searchBtn.innerHTML;
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

            // Add loading overlay to list
            prescriptionsList.style.opacity = '0.5';
            prescriptionsList.style.pointerEvents = 'none';

            // Perform AJAX request
            fetch(searchUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update prescriptions list
                    prescriptionsList.innerHTML = data.html;

                    // Update pagination
                    paginationContainer.innerHTML = data.pagination;

                    // Update URL without page reload
                    if (!url) {
                        window.history.pushState({}, '', searchUrl);
                    }

                    // Smooth scroll to top of list
                    prescriptionsList.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                // Show error message
                prescriptionsList.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                        <svg class="w-12 h-12 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-red-900 mb-2">Search Failed</h3>
                        <p class="text-red-700">Unable to perform search. Please try again.</p>
                    </div>
                `;
            })
            .finally(() => {
                // Restore button state
                searchBtn.disabled = false;
                searchBtn.innerHTML = originalBtnContent;

                // Remove loading overlay
                prescriptionsList.style.opacity = '1';
                prescriptionsList.style.pointerEvents = 'auto';
            });
        }

        // Handle form submission
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });

        // Real-time search with debounce
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);

            // Only auto-search if there's text or if clearing the search
            if (this.value.length >= 2 || this.value.length === 0) {
                searchTimeout = setTimeout(() => {
                    performSearch();
                }, 500); // Wait 500ms after user stops typing
            }
        });

        // Handle pagination clicks and clear button
        document.addEventListener('click', function(e) {
            // Check if clicked element is a pagination link
            const paginationLink = e.target.closest('a[href*="page="]');
            if (paginationLink && paginationLink.closest('#paginationContainer')) {
                e.preventDefault();
                const url = paginationLink.href;
                performSearch(url);
            }

            // Handle clear search button clicks
            if (e.target.closest('button[onclick*="getElementById"]')) {
                e.preventDefault();
                searchInput.value = '';
                performSearch();
            }
        });

        // Handle tab clicks with AJAX
        document.querySelectorAll('.tab-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = this.href;
            });
        });

        // Modal Management Functions
        function changeStatus(prescriptionId, status) {
            if (!confirm(`Are you sure you want to change the status to "${status.replace('_', ' ')}"?`)) {
                return;
            }

            // Show loading state
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

            fetch(`/doctor/prescriptions/${prescriptionId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the current page to show updated status
                    window.location.reload();
                } else {
                    alert('Failed to update prescription status. Please try again.');
                }
            })
            .catch(error => {
                console.error('Status update error:', error);
                alert('An error occurred while updating the prescription status.');
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = originalText;
            });
        }

        function deletePrescription(prescriptionId) {
            if (!confirm('Are you sure you want to delete this prescription? This action cannot be undone.')) {
                return;
            }

            // Show loading state
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

            fetch(`/doctor/prescriptions/${prescriptionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the prescription card from the list
                    const card = button.closest('.group');
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        card.remove();
                        // Reload if no prescriptions left
                        if (document.querySelectorAll('.group').length === 0) {
                            window.location.reload();
                        }
                    }, 300);
                } else {
                    alert('Failed to delete prescription. Please try again.');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('An error occurred while deleting the prescription.');
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = originalText;
            });
        }

        // Handle Create Prescription form submission with AJAX
        const createForm = document.getElementById('prescription-create-form');
        if (createForm) {
            createForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitButton = createForm.querySelector('button[type="submit"]');
                const originalButtonContent = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating...';

                const formData = new FormData(createForm);

                fetch(createForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal
                        window.dispatchEvent(new CustomEvent('close-modal', { detail: { name: 'prescription-create-modal' } }));

                        // Show success toast
                        showToast('Prescription created successfully!', 'success');

                        // Reset the form for next time
                        createForm.reset();

                        // Refresh the list to show the new prescription
                        // A simple reload is used here, but you could also just refresh the list content
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000); // Reload after 1 second to allow toast to be seen

                    } else {
                        // Handle validation errors or other failures
                        showToast(data.message || 'Failed to create prescription.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Create prescription error:', error);
                    showToast('An unexpected error occurred.', 'error');
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonContent;
                });
            });
        }

        // Toast notification system
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'success' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'}"></path>
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);

            // Remove after 3 seconds
            setTimeout(() => {
                toast.style.transform = 'translateX(full)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
</x-app-layout>
