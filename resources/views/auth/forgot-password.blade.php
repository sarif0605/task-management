@extends('auth.header')
@section('title', 'Forgot Password')
@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
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
@push('scripts')
    @include('auth.script')
@endpush
@endsection
