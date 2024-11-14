@extends('auth.header')
@section('title', 'OTP User')
@section('content')
<hr />
@include('components.loading')
<form id="form-verifikasi" method="POST" action="{{ route('verifikasi') }}">
    @csrf
        <div class="form-group">
            <input type="text" name="otp" class="form-control form-control-user" placeholder="Masukkan Kode OTP" required autofocus>
            @if ($errors->has('otp'))
                <span class="text-danger">{{ $errors->first('otp') }}</span>
            @endif
        </div>
        <button type="submit" class="btn btn-primary btn-user btn-block">Verifikasi OTP</button>
    </form>
    <hr>
<form method="POST" action="{{ route('generate') }}">
    @csrf
        <button type="submit" class="btn btn-secondary btn-user btn-block">Kirim Ulang OTP</button>
</form>
@push('scripts')
    @include('auth.script')
@endpush
@endsection
