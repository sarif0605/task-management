@extends('layouts.contractor')
@section('title', 'Create Prospect')
@section('content')
    @include('components.loading')
    <form id="prospect-form" class="p-10" action="{{ route('prospects.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control" placeholder="Name">
                @if ($errors->has('nama_produk'))
                        <span class="text-danger small">{{ $errors->first('nama_produk') }}</span>
                @endif
            </div>
            <div class="col">
                <label for="pemilik">Pemilik</label>
                <input type="text" name="pemilik" class="form-control" placeholder="Pemilik">
                @if ($errors->has('pemilik'))
                    <span class="text-danger small">{{ $errors->first('pemilik') }}</span>
                @endif
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" placeholder="Tanggal">
                @if ($errors->has('tanggal'))
                    <span class="text-danger small">{{ $errors->first('tanggal') }}</span>
                @endif
            </div>
            <div class="col">
                <label for="no_telp">No Telp</label>
                <input type="number" name="no_telp" class="form-control" placeholder="No Telp">
            </div>
            @if ($errors->has('no_telp'))
                <span class="text-danger small">{{ $errors->first('no_telp') }}</span>
            @endif
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="keterangan">Keterangan</label>
                <textarea class="form-control" name="keterangan" placeholder="Keterangan"></textarea>
                @if ($errors->has('keterangan'))
                    <span class="text-danger small">{{ $errors->first('keerangan') }}</span>
                @endif
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <div class="col">
                    <label for="lokasi">Lokasi</label>
                    <textarea class="form-control" name="lokasi" placeholder="Lokasi"></textarea>
                    @if ($errors->has('lokasi'))
                        <span class="text-danger small">{{ $errors->first('lokasi') }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('prospects') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>

    </form>
    @push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("prospect-form");
    const loadingOverlay = document.getElementById("loading");
    const submitButton = form.querySelector("button[type='submit']");

    if (form && loadingOverlay && submitButton) {
        form.addEventListener("submit", function (e) {
            loadingOverlay.style.display = "flex"; // Tampilkan overlay
            submitButton.disabled = true; // Nonaktifkan tombol
            submitButton.textContent = "Loading..."; // Ubah teks tombol
        });
    } else {
        console.error("Form, loading overlay, atau tombol submit tidak ditemukan!");
    }
});
</script>
@endpush
@endsection
