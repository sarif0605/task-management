<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta and title -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Verifikasi OTP</title>

    <!-- Styles -->
    <link href="{{ asset('admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        .bg-login-image {
            background-image: url('{{ asset('admin/img/undraw_posting_photo.svg') }}');
            background-size: cover;
            background-position: center;
        }
        .countdown {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Verifikasi OTP</h1>
                                    </div>

                                    <!-- Form Verifikasi OTP -->
                                    <form method="POST" action="{{ route('verifikasi') }}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" name="otp" class="form-control form-control-user"
                                                   placeholder="Masukkan Kode OTP" required autofocus>
                                            @if ($errors->has('otp'))
                                                <span class="text-danger">{{ $errors->first('otp') }}</span>
                                            @endif
                                        </div>

                                        <!-- Tombol Verifikasi OTP -->
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Verifikasi OTP
                                        </button>
                                    </form>

                                    <hr>
                                    <!-- Tombol Generate OTP Baru -->
                                    <form method="POST" action="{{ route('generate') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-user btn-block">
                                            Kirim Ulang OTP
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('admin/js/sb-admin-2.min.js') }}"></script>
</body>
</html>
