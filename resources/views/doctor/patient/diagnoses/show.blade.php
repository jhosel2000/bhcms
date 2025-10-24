@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Diagnosis for {{ $patient->name }}</div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="diagnosis">Diagnosis</label>
                            <input type="text" name="diagnosis" id="diagnosis" class="form-control" value="{{ $diagnosis->diagnosis }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" readonly>{{ $diagnosis->notes }}</textarea>
                        </div>

                        <a href="{{ route('doctor.patient.diagnoses.index', $patient) }}" class="btn btn-secondary">Back to Diagnoses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
