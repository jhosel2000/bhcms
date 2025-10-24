@props(['status' => 'all'])

<div class="bg-white border-b border-gray-200">
    <form method="GET" action="{{ route('doctor.appointments.index') }}" class="relative">
        <input type="hidden" name="status" value="{{ $status }}">

        <div class="relative">
            <!-- Search Icon -->
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <!-- Search Input -->
            <input type="text"
                   name="search"
                   id="search"
                   value="{{ request('search') }}"
                   placeholder="Search appointments by patient name, date, or reason... (Press Ctrl+K to focus)"
                   class="block w-full pl-11 pr-32 py-3.5 text-gray-900 placeholder-gray-500 border-0 focus:ring-0 focus:outline-none text-base"
                   autocomplete="off">

            <!-- Action Buttons -->
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 gap-1">
                @if(request('search'))
                    <button type="button"
                            onclick="document.getElementById('search').value=''; this.closest('form').submit();"
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors"
                            title="Clear search">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif

                <button type="submit"
                        class="inline-flex items-center px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search
                </button>
            </div>
        </div>

        <!-- Search hint -->
        @if(request('search'))
            <div class="px-4 py-2 bg-blue-50 border-t border-blue-100">
                <p class="text-sm text-blue-700">
                    <span class="font-medium">Searching for:</span> "{{ request('search') }}"
                    <span class="text-blue-600 ml-2">•</span>
                    <button type="button"
                            onclick="document.getElementById('search').value=''; this.closest('form').submit();"
                            class="ml-2 text-blue-600 hover:text-blue-800 font-medium underline">
                        Clear search
                    </button>
                </p>
            </div>
        @endif
    </form>
</div>
