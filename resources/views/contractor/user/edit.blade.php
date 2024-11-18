@extends('layouts.contractor')

@section('title', 'Edit User')

@section('content')
    <hr />
    @include('components.loading')
    <form id="user-form-edit" action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="position.nama" class="form-control" placeholder="Nama" value="{{ $user->profile->nama ?? 'nama' }}" readonly>
            </div>
            <div class="col mb-3">
                <label class="form-label">Posisi</label>
                <input type="text" name="position" class="form-control" placeholder="Pemilik" value="{{ $user->position }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Tanggal" value="{{ $user->email }}" readonly>
            </div>
            {{-- <div class="col mb-3">
                <label class="form-label">Verifikasi</label>
                <input type="date" name="email_verified_at" class="form-control" placeholder="Tanggal" value="{{ $user->email_verified_at }}" readonly>
            </div> --}}
            <div class="col mb-3">
                <label class="form-label">Status</label>
                <select name="status_account" class="form-control" required>
                    <option value="" disabled selected>Select Status</option>
                    <option value="active">Aktif</option>
                    <option value="unactive">Tidak Aktif</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Gambar</label>
                <input type="text" name="foto" class="form-control" placeholder="Tanggal" value="{{ $user->profile->foto ?? 'foto' }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="d-grid">
                <button class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
    @push('js')
        @include('contractor.prospect.script')
    @endpush
@endsection
