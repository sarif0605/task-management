@extends('auth.header')
@section('head', 'Login User')
@section('content')
@include('components.loading')

<div class="container">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg px-4 py-4 border-0 w-100"
             style="max-width: 400px; background: linear-gradient(135deg, #ffffff, #f8f9fc); border-radius: 10px;">
            <div class="text-center mb-3">
                <h1 class="h5 text-gray-900 font-weight-bold">Login User</h1>
            </div>

            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group mb-3">
                    <input type="email" name="email" class="form-control form-control-sm" placeholder="Enter Email Address..." value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                        <span class="text-danger small">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group mb-3 position-relative">
                    <input type="password" id="password" name="password" class="form-control form-control-sm" placeholder="Password" required>
                    <span id="togglePassword" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                    @if ($errors->has('password'))
                        <span class="text-danger small">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="form-group form-check mb-3">
                    <input type="checkbox" name="remember" class="form-check-input" id="customCheck">
                    <label class="form-check-label small" for="customCheck">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-sm">Login</button>
                <hr>
            </form>
            <div class="text-center">
                @if (Route::has('password.request'))
                    <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                @endif
            </div>
            <div class="text-center mt-2">
                <a class="small" href="{{ route('register') }}">Create an Account!</a>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");

        togglePassword.addEventListener("click", function () {
            const type = passwordInput.type === "password" ? "text" : "password";
            passwordInput.type = type;

            this.innerHTML = type === "password"
                ? "<i class='fas fa-eye'></i>"
                : "<i class='fas fa-eye-slash'></i>";
        });

        const form = document.getElementById("login-form");
        const loadingOverlay = document.getElementById("loading");
        const submitButton = form.querySelector("button[type='submit']");
        form.addEventListener("submit", function () {
            loadingOverlay.style.display = "flex";
            submitButton.disabled = true;
            submitButton.textContent = "Loading...";
        });
    });
    </script>
@endpush
@endsection
