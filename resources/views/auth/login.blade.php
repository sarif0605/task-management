@extends('auth.header')
@section('title', 'Login User')
@section('content')
<hr />
@include('components.loading')
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
@push('scripts')
    @include('auth.script')
@endpush
@endsection
