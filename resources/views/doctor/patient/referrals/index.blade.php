@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Referrals for {{ $patient->name }}</div>

                    <div class="card-body">
                        <a href="{{ route('doctor.patient.referrals.create', $patient) }}" class="btn btn-primary mb-3">Add Referral</a>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Referred To</th>
                                    <th>Reason</th>
                                    <th>Doctor</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($referrals as $referral)
                                    <tr>
                                        <td>{{ $referral->referred_to }}</td>
                                        <td>{{ $referral->reason }}</td>
                                        <td>{{ $referral->doctor->name }}</td>
                                        <td>{{ $referral->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('doctor.patient.referrals.show', [$patient, $referral]) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('doctor.patient.referrals.edit', [$patient, $referral]) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('doctor.patient.referrals.destroy', [$patient, $referral]) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this referral?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $referrals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
