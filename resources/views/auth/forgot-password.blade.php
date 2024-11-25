@extends('auth.header')
@section('head', 'Login User')
@section('content')
@include('components.loading')

<div class="container">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg px-5 py-4 border-0 w-100"
             style="max-width: 800px; background: linear-gradient(135deg, #ffffff, #f8f9fc); border-radius: 15px;">
            <div class="text-center mb-4">
                <h1 class="h4 text-gray-900">Forgot Password User!</h1>
            </div>

            @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
@include('components.loading')
    <form id="forgot-password-form" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <label for="email">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" class="form-control" placeholder="Enter Email Address..." value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="text-danger small">{{ $errors->first('email') }}</span>
            @endif
        </div>
        <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
            <button type="submit" class="btn btn-primary btn-block">{{ __('Email Password Reset Link') }}</button>
        </div>
    </form>
    <div class="card-footer text-center">
        <a class="small" href="{{ route('login') }}">Back to Login</a>
    </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("forgot-password-form");
            const loadingOverlay = document.getElementById("loading");
            const submitButton = form.querySelector("button[type='submit']");
            form.addEventListener("submit", function (e) {
                loadingOverlay.style.display = "flex";
                submitButton.disabled = true;
                submitButton.textContent = "Loading...";
            });
        });
    </script>
    @endpush
    @endsection
