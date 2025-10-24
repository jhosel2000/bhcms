@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Add Diagnosis for {{ $patient->name }}</div>

                    <div class="card-body">
                        <form action="{{ route('doctor.patient.diagnoses.store', $patient) }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="diagnosis">Diagnosis</label>
                                <input type="text" name="diagnosis" id="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" value="{{ old('diagnosis') }}" required>
                                @error('diagnosis')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Add Diagnosis</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
