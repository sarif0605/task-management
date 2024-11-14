@extends('auth.header')
@section('content')
@include('components.loading')
<div class="text-center mb-4">
    <h1 class="h4 text-gray-900">Register User!</h1>
</div>
<form id="register-form" method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Enter Email Address..." value="{{ old('email') }}" required autofocus>
        @if ($errors->has('email'))
            <span class="text-danger small">{{ $errors->first('email') }}</span>
        @endif
    </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            @if ($errors->has('password'))
                <span class="text-danger small">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <!-- Input untuk konfirmasi password -->
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
            @if ($errors->has('password_confirmation'))
                <span class="text-danger small">{{ $errors->first('password_confirmation') }}</span>
            @endif
        </div>

        <!-- Pilihan role -->
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

        <!-- Checkbox untuk memilih posisi -->
        <div class="form-group">
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
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    @push('scripts')
    @include('auth.script')
@endpush
@endsection


{{-- @extends('auth.header')
@section('content')
@include('components.loading')
<div class="text-center mb-4">
    <h1 class="h4 text-gray-900">Register User!</h1>
</div>
<form id="register-form" method="POST" action="{{ route('register') }}">
    @csrf
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
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required
                    value="{{ old('email') }}" placeholder="Enter email address">
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required
                    placeholder="Password">
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control"
                    required placeholder="Confirm Password">
            </div>
            <button type="submit" class="btn btn-primary btn-user btn-block">Register</button>
        </form>
            <div class="text-center">
                <div class="text-center">
                    <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                </div>
            </div>
@push('scripts')
    @include('auth.script')
@endpush
@endsection --}}
