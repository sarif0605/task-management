<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet"> --}}

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-register-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                    </div>

                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf

                                        <!-- Role Selection -->
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select name="role" class="form-control" required>
                                                <option>Select Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('role'))
                                                <span class="text-danger">{{ $errors->first('role') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="position">Position</label>
                                            <select name="position" class="form-control" required>
                                                <option value="">Select Position</option>
                                                <option value="marketing" {{ old('position') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                                                <option value="hrd" {{ old('position') == 'hrd' ? 'selected' : '' }}>HRD</option>
                                                <option value="sales" {{ old('position') == 'sales' ? 'selected' : '' }}>Sales</option>
                                                <option value="operational" {{ old('position') == 'operational' ? 'selected' : '' }}>Operational</option>
                                                <option value="finance" {{ old('position') == 'finance' ? 'selected' : '' }}>Finance</option>
                                                <option value="pengawas" {{ old('position') == 'pengawas' ? 'selected' : '' }}>Pengawas</option>
                                                <option value="mandor" {{ old('position') == 'mandor' ? 'selected' : '' }}>Mandor</option>
                                            </select>
                                            @if ($errors->has('position'))
                                                <span class="text-danger">{{ $errors->first('position') }}</span>
                                            @endif
                                        </div>

                                        <!-- Email Address -->
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control" required
                                                value="{{ old('email') }}" placeholder="Enter email address">
                                            @if ($errors->has('email'))
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>

                                        <!-- Password -->
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" name="password" class="form-control" required
                                                placeholder="Password">
                                            @if ($errors->has('password'))
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                required placeholder="Confirm Password">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Register
                                        </button>
                                    </form>

                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                                    </div>
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
