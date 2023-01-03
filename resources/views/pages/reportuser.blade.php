@extends('layouts.centered-form')

@section('title', 'Report a User')


@section('form')
   
    <div class="form-floating">
        <input aria-describedby="report-feedback" placeholder=""
            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
            id="reason" type="text" name="reason" value="{{ $user->reason }}"
            required autofocus>
        <label for="reason" class="form-label">Reason of report</label>
        <div class="invalid-feedback" id="reason-feedback">
            @error('report')
                {{ $message }}
            @else
                Invalid report
            @enderror
        </div>
    </div>

    <div class="form-floating">
        <input aria-describedby="content-feedback" placeholder=""
            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
            id="content" type="text" name="content" value="{{ $user->content }}"
            required autofocus>
        <label for="content" class="form-label">Description</label>
        <div class="invalid-feedback" id="content-feedback">
            @error('content')
                {{ $message }}
            @else
                Invalid content
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        Report
    </button>
@endsection
