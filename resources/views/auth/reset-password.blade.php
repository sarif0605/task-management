@extends('auth.header')
@section('title', 'Reset Password')
@section('head', 'Reset Password')
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

    <div class="form-group mt-3 position-relative">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <div class="position-relative">
            <input id="password" type="password" name="password" class="form-control pe-5" placeholder="New Password" required autocomplete="new-password">
            <span id="togglePassword" class="position-absolute d-flex align-items-center justify-content-center"
                  style="top: 0; bottom: 0; right: 10px; width: 40px; cursor: pointer;">
                <i class="fa fa-eye"></i>
            </span>
        </div>
        @if ($errors->has('password'))
            <span class="text-danger small">{{ $errors->first('password') }}</span>
        @endif
    </div>

    <div class="form-group mt-3 position-relative">
        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
        <div class="position-relative">
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control pe-5" placeholder="Confirm New Password" required autocomplete="new-password">
            <span id="togglePasswordConfirmation" class="position-absolute d-flex align-items-center justify-content-center"
                  style="top: 0; bottom: 0; right: 10px; width: 40px; cursor: pointer;">
                <i class="fa fa-eye"></i>
            </span>
        </div>
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

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Toggle visibility for password
            const togglePassword = document.getElementById("togglePassword");
            const passwordInput = document.getElementById("password");
            togglePassword.addEventListener("click", function () {
                const type = passwordInput.type === "password" ? "text" : "password";
                passwordInput.type = type;
                this.innerHTML = type === "password" ? "<i class='fa fa-eye'></i>" : "<i class='fa fa-eye-slash'></i>";
            });

            // Toggle visibility for confirm password
            const togglePasswordConfirmation = document.getElementById("togglePasswordConfirmation");
            const passwordConfirmationInput = document.getElementById("password_confirmation");
            togglePasswordConfirmation.addEventListener("click", function () {
                const type = passwordConfirmationInput.type === "password" ? "text" : "password";
                passwordConfirmationInput.type = type;
                this.innerHTML = type === "password" ? "<i class='fa fa-eye'></i>" : "<i class='fa fa-eye-slash'></i>";
            });
        });
    </script>
@endpush
@endsection
