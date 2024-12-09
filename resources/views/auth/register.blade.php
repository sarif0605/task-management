@extends('auth.header')
@section('head', 'Registrasi User')
@section('content')
@include('components.loading')

<div class="container">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg px-5 py-4 border-0 w-100"
             style="max-width: 800px; background: linear-gradient(135deg, #ffffff, #f8f9fc); border-radius: 15px;">
            <div class="text-center mb-4">
                <h1 class="h4 text-gray-900">Register User!</h1>
            </div>

            <form id="register-form" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row g-4">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <!-- Input Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter Email Address..." value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="text-danger small">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <!-- Input Password -->
                        <div class="form-group mt-3 position-relative">
                            <label for="password">Password</label>
                            <div class="position-relative">
                                <input type="password" id="password" name="password" class="form-control pe-5" placeholder="Password" required>
                                <span id="togglePassword"
                                      class="position-absolute d-flex align-items-center justify-content-center"
                                      style="top: 0; bottom: 0; right: 10px; width: 40px; cursor: pointer;">
                                    👁️
                                </span>
                            </div>
                            @if ($errors->has('password'))
                                <span class="text-danger small">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <div class="form-group mt-3 position-relative">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="position-relative">
                                <input type="password" id="passwordConfirmation" name="password_confirmation" class="form-control pe-5" placeholder="Confirm Password" required>
                                <span id="togglePasswordConfirmation"
                                      class="position-absolute d-flex align-items-center justify-content-center"
                                      style="top: 0; bottom: 0; right: 10px; width: 40px; cursor: pointer;">
                                    👁️
                                </span>
                            </div>
                            @if ($errors->has('password_confirmation'))
                                <span class="text-danger small">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <!-- Input Role -->
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" class="form-control" required>
                                <option>Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('role'))
                                <span class="text-danger small">{{ $errors->first('role') }}</span>
                            @endif
                        </div>

                        <!-- Input Positions -->
                        <div class="form-group mt-3">
                            <label>Select Positions</label>
                            @foreach ($positions as $position)
                                <div>
                                    <label>
                                        <input type="checkbox" name="positions[]" value="{{ $position->name }}">
                                        {{ $position->name }}
                                    </label>
                                </div>
                            @endforeach
                            @if ($errors->has('positions'))
                                <span class="text-danger small">{{ $errors->first('positions') }}</span>
                            @endif
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="btn btn-primary mt-4 w-100">Register</button>
                        <div class="text-center mt-2">
                            <a class="small" href="{{ route('login') }}">Login!</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Toggle visibility for password
            const togglePassword = document.getElementById("togglePassword");
            const passwordInput = document.getElementById("password");
            togglePassword.addEventListener("click", function () {
                const type = passwordInput.type === "password" ? "text" : "password";
                passwordInput.type = type;
                this.textContent = type === "password" ? "👁️" : "🙈";
            });

            // Toggle visibility for confirm password
            const togglePasswordConfirmation = document.getElementById("togglePasswordConfirmation");
            const passwordConfirmationInput = document.getElementById("passwordConfirmation");
            togglePasswordConfirmation.addEventListener("click", function () {
                const type = passwordConfirmationInput.type === "password" ? "text" : "password";
                passwordConfirmationInput.type = type;
                this.textContent = type === "password" ? "👁️" : "🙈";
            });

            // Loading overlay
            const form = document.getElementById("register-form");
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

{{-- @extends('auth.header')
@section('head', 'Registrasi User')
@section('content')
@include('components.loading')

<div class="container">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg px-5 py-4 border-0 w-100"
             style="max-width: 800px; background: linear-gradient(135deg, #ffffff, #f8f9fc); border-radius: 15px;">
            <div class="text-center mb-4">
                <h1 class="h4 text-gray-900">Register User!</h1>
            </div>

            <form id="register-form" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row g-4">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <!-- Input Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter Email Address..." value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="text-danger small">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <!-- Input Password -->
                        <div class="form-group mt-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                            @if ($errors->has('password'))
                                <span class="text-danger small">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <!-- Input Confirm Password -->
                        <div class="form-group mt-3">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                            @if ($errors->has('password_confirmation'))
                                <span class="text-danger small">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <!-- Input Role -->
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" class="form-control" required>
                                <option>Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('role'))
                                <span class="text-danger small">{{ $errors->first('role') }}</span>
                            @endif
                        </div>

                        <!-- Input Positions -->
                        <div class="form-group mt-3">
                            <label>Select Positions</label>
                            @foreach ($positions as $position)
                                <div>
                                    <label>
                                        <input type="checkbox" name="positions[]" value="{{ $position->name }}">
                                        {{ $position->name }}
                                    </label>
                                </div>
                            @endforeach
                            @if ($errors->has('positions'))
                                <span class="text-danger small">{{ $errors->first('positions') }}</span>
                            @endif
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="btn btn-primary mt-4 w-100">Register</button>
                        <div class="text-center mt-2">
                            <a class="small" href="{{ route('login') }}">Login!</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("register-form");
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
    @endsection --}}
