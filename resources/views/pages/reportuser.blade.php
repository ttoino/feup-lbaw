@extends('layouts.centered-form')

@section('title', 'Report a User')


@section('form')
    <h2>Reporting {{ $user->name }}</h2>

    <div class="form-floating">
        <textarea aria-describedby="report-feedback" placeholder="" minlength="6"
            maxlength="512" id="reason" type="text" name="reason"
            class="form-control auto-resize {{ $errors->has('reason') ? 'is-invalid' : '' }}"
            required autofocus></textarea>
        <label for="reason" class="form-label">Reason of report</label>
        <div class="invalid-feedback" id="reason-feedback">
            @error('report')
                {{ $error['reason'] }}
            @else
                Invalid report
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        Report
    </button>
@endsection
