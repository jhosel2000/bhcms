{{--
    Prescription Search Filter Component
    Reusable search bar for prescription filtering
--}}
@props(['status' => 'all'])

<div class="px-6 py-4 bg-white border-b border-gray-200">
    <form action="{{ route('doctor.prescriptions.index') }}" method="GET" class="w-full">
        {{-- Preserve status filter --}}
        <input type="hidden" name="status" value="{{ $status }}">
        
        <div class="relative">
            {{-- Search Icon --}}
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            {{-- Search Input --}}
            <input 
                type="text" 
                id="search" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Search by medication, patient name, dosage, or instructions... (Ctrl+K)"
                class="block w-full pl-12 pr-32 py-3.5 text-gray-900 border border-gray-300 rounded-lg 
                       focus:ring-2 focus:ring-green-500 focus:border-green-500 
                       placeholder-gray-400 transition-all duration-200"
                autocomplete="off"
            >

            {{-- Clear Button (shown when search is active) --}}
            @if(request('search'))
                <button 
                    type="button" 
                    onclick="document.getElementById('search').value = ''; this.closest('form').submit();"
                    class="absolute inset-y-0 right-24 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors"
                    title="Clear search"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif

            {{-- Search Button --}}
            <button 
                type="submit"
                class="absolute inset-y-0 right-0 flex items-center px-6 text-white bg-green-600 hover:bg-green-700 
                       rounded-r-lg transition-colors duration-200 font-medium"
            >
                Search
            </button>
        </div>

        {{-- Search Hint (shown when search is active) --}}
        @if(request('search'))
            <div class="mt-3 flex items-center justify-between text-sm">
                <span class="text-gray-600">
                    Showing results for: <strong class="text-gray-900">"{{ request('search') }}"</strong>
                </span>
                <button 
                    type="button"
                    onclick="document.getElementById('search').value = ''; this.closest('form').submit();"
                    class="text-green-600 hover:text-green-700 font-medium"
                >
                    Clear search
                </button>
            </div>
        @endif
    </form>
</div>