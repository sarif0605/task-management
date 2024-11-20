@extends('auth.header')
@section('content')
@include('components.loading')

<div class="container">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg px-5 py-4 border-0 w-100"
             style="max-width: 800px; background: linear-gradient(135deg, #ffffff, #f8f9fc); border-radius: 15px;">
                <div class="text-center mb-4">
                    <h1 class="h4 text-gray-900">Register User!</h1>
                </div>
                <div class="card-body">
                <p class="text-gray-600">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on
                    the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </p>
                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success" role="alert">
                        A new verification link has been sent to the email address you provided during registration.
                    </div>
                @endif
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <!-- Resend Verification Form -->
                    <form id="verification-form" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-envelope"></i> Resend Verification Email
                        </button>
                    </form>
                    <!-- Logout Form -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Log Out
                        </button>
                    </form>
                </div>
        </div>
    </div>
</div>

@push('scripts')
    @include('auth.script')
@endpush
@endsection
