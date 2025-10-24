@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Add Referral for {{ $patient->name }}</div>

                    <div class="card-body">
                        <form action="{{ route('doctor.patient.referrals.store', $patient) }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="referred_to">Referred To</label>
                                <input type="text" name="referred_to" id="referred_to" class="form-control @error('referred_to') is-invalid @enderror" value="{{ old('referred_to') }}" required>
                                @error('referred_to')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="reason">Reason</label>
                                <input type="text" name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" value="{{ old('reason') }}" required>
                                @error('reason')
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

                            <button type="submit" class="btn btn-primary">Add Referral</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
