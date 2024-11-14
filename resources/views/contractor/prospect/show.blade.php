@extends('layouts.contractor')

@section('title', 'Show Prospect')

@section('content')
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama_produk" class="form-control form-control-underline" placeholder="Nama" value="{{ $prospect->nama_produk }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Pemilik</label>
            <input type="text" name="pemilik" class="form-control form-control-underline" placeholder="Pemilik" value="{{ $prospect->pemilik }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control form-control-underline" placeholder="Tanggal" value="{{ $prospect->tanggal }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Lokasi</label>
            <textarea class="form-control form-control-underline" name="lokasi" placeholder="Lokasi" readonly>{{ $prospect->lokasi }}</textarea>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control form-control-underline" name="keterangan" placeholder="Keterangan" readonly>{{ $prospect->keterangan }}</textarea>
        </div>
    </div>
    <a href="{{ route('prospects') }}" class="btn btn-primary">Kembali</a>

    <style>
        .form-control-underline {
            border: none;
            border-bottom: 1px solid #ced4da;
            border-radius: 0;
            background-color: transparent;
            box-shadow: none;
        }

        .form-control-underline:focus {
            border-bottom: 2px solid #007bff;
            outline: none;
            box-shadow: none;
        }
    </style>
@endsection
