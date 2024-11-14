@extends('auth.header')
@section('title', 'Reset Password')
@section('content')
<hr />
@include('components.loading')
<form method="POST" action="{{ route('password.store') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">
    <div class="form-group">
        <label for="email" class="form-label">{{ __('Email') }}</label>
        <input id="email" type="email" name="email" class="form-control" placeholder="Enter your email..." value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
        @if ($errors->has('email'))
            <span class="text-danger small">{{ $errors->first('email') }}</span>
        @endif
    </div>
    <div class="form-group mt-3">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <input id="password" type="password" name="password" class="form-control" placeholder="New Password" required autocomplete="new-password">
        @if ($errors->has('password'))
            <span class="text-danger small">{{ $errors->first('password') }}</span>
        @endif
    </div>
    <div class="form-group mt-3">
        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password" required autocomplete="new-password">
        @if ($errors->has('password_confirmation'))
            <span class="text-danger small">{{ $errors->first('password_confirmation') }}</span>
        @endif
    </div>

    <div class="d-flex justify-content-end mt-4">
        <button type="submit" class="btn btn-primary">
            {{ __('Reset Password') }}
        </button>
    </div>
</form>
@endsection
