@extends('layouts.contractor')

@section('title', 'Show Users')

@section('content')
    <hr />
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" placeholder="Nama" value="{{ $user->profile->nama ?? 'nama' }}" readonly>
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
        <div class="col mb-3">
            <label class="form-label">Verifikasi</label>
            <input type="text" name="email_verified_at" class="form-control" placeholder="Tanggal" value="{{ $user->email_verified_at }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Status</label>
            <input type="text" name="status_account" class="form-control" placeholder="Tanggal" value="{{ $user->status_account }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Gambar</label>
            <input type="text" name="foto" class="form-control" placeholder="Tanggal" value="{{ $user->profile->foto ?? 'foto' }}" readonly>
        </div>
    </div>
    </div>
@endsection
