@extends('auth.header')
@section('title', 'Login User')
@section('head', 'Login User')
@section('content')
<hr />
@include('components.loading')
<div class="container">
    <div class="d-flex justify-content-center align-items-center min-vh-100 w-80">
        <div class="card shadow-lg p-4 border-0" style="max-width: 400px; width: 100%; background: linear-gradient(135deg, #ffffff, #f8f9fc); border-radius: 15px;">
            <div class="text-center mb-4">
                <h1 class="h4 text-gray-900">@yield('title')</h1>
            </div>
            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Enter Email Address..." value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                        <span class="text-danger small">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    @if ($errors->has('password'))
                        <span class="text-danger small">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="customCheck">
                    <label class="form-check-label" for="customCheck">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
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
            const form = document.getElementById("login-form");
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
