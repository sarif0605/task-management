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
                @if ($errors->has('nama_produk'))
                    <span class="text-danger small">{{ $errors->first('nama_produk') }}</span>
                @endif
            </div>
            <div class="col mb-3">
                <label class="form-label">Pemilik</label>
                <input type="text" name="pemilik" class="form-control form-control-underline" placeholder="Pemilik" value="{{ $prospect->pemilik }}" >
                @if ($errors->has('pemilik'))
                    <span class="text-danger small">{{ $errors->first('pemilik') }}</span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control form-control-underline" placeholder="Tanggal" value="{{ $prospect->tanggal }}" >
                @if ($errors->has('tanggal'))
                    <span class="text-danger small">{{ $errors->first('tanggal') }}</span>
                @endif
            </div>
            <div class="col">
                <label for="no_telp">No Telp</label>
                <input type="number" name="no_telp" class="form-control" placeholder="No Telp" value="{{ $prospect->no_telp }}">
                @if ($errors->has('no_telp'))
                    <span class="text-danger small">{{ $errors->first('no_telp') }}</span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Keterangan</label>
                <textarea class="form-control form-control-underline" name="keterangan" placeholder="Keterangan" >{{ $prospect->keterangan }}</textarea>
                @if ($errors->has('keterangan'))
                    <span class="text-danger small">{{ $errors->first('keterangan') }}</span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Lokasi</label>
                <textarea class="form-control form-control-underline" name="lokasi" placeholder="Lokasi" >{{ $prospect->lokasi }}</textarea>
                @if ($errors->has('lokasi'))
                    <span class="text-danger small">{{ $errors->first('lokasi') }}</span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 d-grid">
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
            <div class="col-md-6 d-grid">
                <a href="{{ route('surveys') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </form>
    @push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("prospect-form-edit");
            const loadingOverlay = document.getElementById("loading");
            const submitButton = form.querySelector("button[type='submit']");
            form.addEventListener("submit", function (e) {
                loadingOverlay.style.display = "flex";
                submitButton.disabled = true;
                submitButton.textContent = "Loading...";
            });
        });
    </script>
    @endpush
@endsection
