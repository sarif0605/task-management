@extends('layouts.contractor')

@section('title', 'Edit Product')

@section('content')
    <hr />
    @include('components.loading')
    <form id="prospect-form-edit" action="{{ route('prospects.update', $prospect->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama_produk" class="form-control form-control-underline" placeholder="Name" value="{{ $prospect->nama_produk }}" >
            </div>
            <div class="col mb-3">
                <label class="form-label">Pemilik</label>
                <input type="text" name="pemilik" class="form-control form-control-underline" placeholder="Pemilik" value="{{ $prospect->pemilik }}" >
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control form-control-underline" placeholder="Tanggal" value="{{ $prospect->tanggal }}" >
            </div>
            <div class="col">
                <label for="no_telp">No Telp</label>
                <input type="number" name="no_telp" class="form-control" placeholder="No Telp" value="{{ $prospect->no_telp }}">
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Keterangan</label>
                <textarea class="form-control form-control-underline" name="keterangan" placeholder="Keterangan" >{{ $prospect->keterangan }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <div class="col mb-3">
                    <label class="form-label">Lokasi</label>
                    <textarea class="form-control form-control-underline" name="lokasi" placeholder="Lokasi" >{{ $prospect->lokasi }}</textarea>
                </div>
            </div>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('prospects') }}" class="btn btn-warning">Kembali</a>
    </form>
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
    @push('js')
        @include('contractor.prospect.script')
    @endpush
@endsection
