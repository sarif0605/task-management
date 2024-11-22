@extends('auth.header')
@section('content')
@include('components.loading')

<div class="container">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg px-5 py-4 border-0 w-100"
             style="max-width: 800px; background: linear-gradient(135deg, #ffffff, #f8f9fc); border-radius: 15px;">
                <div class="text-center mb-4">
                    <h1 class="h4 text-gray-900">Verifikasi Email!</h1>
                </div>
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card-body">
                <p class="text-gray-600">
                    Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirim ke email Anda? Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan email lainnya kepada Anda.
                </p>
                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success" role="alert">
                        Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("verification-form");
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
