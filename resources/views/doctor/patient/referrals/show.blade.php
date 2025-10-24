@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Referral for {{ $patient->name }}</div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="referred_to">Referred To</label>
                            <input type="text" name="referred_to" id="referred_to" class="form-control" value="{{ $referral->referred_to }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="reason">Reason</label>
                            <input type="text" name="reason" id="reason" class="form-control" value="{{ $referral->reason }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" readonly>{{ $referral->notes }}</textarea>
                        </div>

                        <a href="{{ route('doctor.patient.referrals.index', $patient) }}" class="btn btn-secondary">Back to Referrals</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
