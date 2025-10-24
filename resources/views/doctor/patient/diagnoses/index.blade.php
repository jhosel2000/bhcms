@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Diagnoses for {{ $patient->name }}</div>

                    <div class="card-body">
                        <a href="{{ route('doctor.patient.diagnoses.create', $patient) }}" class="btn btn-primary mb-3">Add Diagnosis</a>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Diagnosis</th>
                                    <th>Doctor</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($diagnoses as $diagnosis)
                                    <tr>
                                        <td>{{ $diagnosis->diagnosis }}</td>
                                        <td>{{ $diagnosis->doctor->name }}</td>
                                        <td>{{ $diagnosis->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('doctor.patient.diagnoses.show', [$patient, $diagnosis]) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('doctor.patient.diagnoses.edit', [$patient, $diagnosis]) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('doctor.patient.diagnoses.destroy', [$patient, $diagnosis]) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this diagnosis?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $diagnoses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
