<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create {{ $typeLabels[$type] ?? 'Maternal Care Record' }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('midwife.maternal.store') }}">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="mb-4">
                    <label for="patient_id" class="block text-gray-700 font-bold mb-2">Patient</label>
                    <select name="patient_id" id="patient_id" class="block w-full border border-gray-300 rounded py-2 px-3">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="visit_date" class="block text-gray-700 font-bold mb-2">Visit Date</label>
                    <input type="date" name="visit_date" id="visit_date" value="{{ old('visit_date') }}" class="block w-full border border-gray-300 rounded py-2 px-3">
                    @error('visit_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="visit_time" class="block text-gray-700 font-bold mb-2">Visit Time</label>
                    <input type="time" name="visit_time" id="visit_time" value="{{ old('visit_time') }}" class="block w-full border border-gray-300 rounded py-2 px-3">
                    @error('visit_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-gray-700 font-bold mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="block w-full border border-gray-300 rounded py-2 px-3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if(in_array($type, ['prenatal', 'postnatal', 'maternal_health', 'vitals', 'checkup', 'followup']))
                    <div class="mb-4">
                        <label for="blood_pressure_systolic" class="block text-gray-700 font-bold mb-2">Blood Pressure Systolic</label>
                        <input type="number" step="0.01" name="blood_pressure_systolic" id="blood_pressure_systolic" value="{{ old('blood_pressure_systolic') }}" class="block w-full border border-gray-300 rounded py-2 px-3">
                        @error('blood_pressure_systolic')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="blood_pressure_diastolic" class="block text-gray-700 font-bold mb-2">Blood Pressure Diastolic</label>
                        <input type="number" step="0.01" name="blood_pressure_diastolic" id="blood_pressure_diastolic" value="{{ old('blood_pressure_diastolic') }}" class="block w-full border border-gray-300 rounded py-2 px-3">
                        @error('blood_pressure_diastolic')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="weight" class="block text-gray-700 font-bold mb-2">Weight (kg)</label>
                        <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight') }}" class="block w-full border border-gray-300 rounded py-2 px-3">
                        @error('weight')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="height" class="block text-gray-700 font-bold mb-2">Height (cm)</label>
                        <input type="number" step="0.01" name="height" id="height" value="{{ old('height') }}" class="block w-full border border-gray-300 rounded py-2 px-3">
                        @error('height')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="heart_rate" class="block text-gray-700 font-bold mb-2">Heart Rate (bpm)</label>
                        <input type="number" name="heart_rate" id="heart_rate" value="{{ old('heart_rate') }}" class="block w-full border border-gray-300 rounded py-2 px-3">
                        @error('heart_rate')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="temperature" class="block text-gray-700 font-bold mb-2">Temperature (°C)</label>
                        <input type="number" step="0.01" name="temperature" id="temperature" value="{{ old('temperature') }}" class="block w-full border border-gray-300 rounded py-2 px-3">
                        @error('temperature')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div class="mb-4">
                    <label for="additional_findings" class="block text-gray-700 font-bold mb-2">Additional Findings</label>
                    <textarea name="additional_findings" id="additional_findings" rows="3" class="block w-full border border-gray-300 rounded py-2 px-3">{{ old('additional_findings') }}</textarea>
                    @error('additional_findings')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="next_visit_date" class="block text-gray-700 font-bold mb-2">Next Visit Date</label>
                    <input type="date" name="next_visit_date" id="next_visit_date" value="{{ old('next_visit_date') }}" class="block w-full border border-gray-300 rounded py-2 px-3">
                    @error('next_visit_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('midwife.maternal.index', ['type' => $type]) }}" class="mr-4 inline-block bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
