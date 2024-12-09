@extends('layouts.contractor')

@section('title', 'Show Prospect')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4>Prospect Details</h4>
            </div>
            <div class="card-body">
                <!-- Nama & Pemilik Fields -->
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control form-control-plaintext" value="{{ $prospect->nama_produk }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pemilik</label>
                        <input type="text" name="pemilik" class="form-control form-control-plaintext" value="{{ $prospect->pemilik }}" readonly>
                    </div>
                </div>

                <!-- Tanggal & No Telp Fields -->
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control form-control-plaintext" value="{{ $prospect->tanggal }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No Telp</label>
                        <input type="text" name="no_telp" class="form-control form-control-plaintext" value="{{ $prospect->no_telp }}" readonly>
                    </div>
                </div>

                <!-- Lokasi & Keterangan Fields -->
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Lokasi</label>
                        <textarea class="form-control form-control-plaintext" name="lokasi" readonly>{{ $prospect->lokasi }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control form-control-plaintext" name="keterangan" readonly>{{ $prospect->keterangan }}</textarea>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="d-grid mt-3">
                    <a href="{{ route('prospects') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
