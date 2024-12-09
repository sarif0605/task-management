@extends('layouts.contractor')

@section('content')
    @include('components.loading')
    <div class="container">
        <form id="prospect-form" class="p-4 card shadow-sm" action="{{ route('prospects.store') }}" method="POST">
            @csrf
            <div class="card-header bg-primary text-white">
                <h4>Tambah Prospect</h4>
            </div>
            <div class="card-body">
                <!-- Nama Produk & Pemilik -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" placeholder="Nama Produk" value="{{ old('nama_produk') }}">
                        @if ($errors->has('nama_produk'))
                            <span class="text-danger small">{{ $errors->first('nama_produk') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="pemilik">Pemilik</label>
                        <input type="text" name="pemilik" class="form-control" placeholder="Pemilik" value="{{ old('pemilik') }}">
                        @if ($errors->has('pemilik'))
                            <span class="text-danger small">{{ $errors->first('pemilik') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Tanggal & No Telp -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}">
                        @if ($errors->has('tanggal'))
                            <span class="text-danger small">{{ $errors->first('tanggal') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="no_telp">No Telp</label>
                        <span class="text-danger small">harus dimulai dengan 62</span>
                        <input type="number" name="no_telp" class="form-control" placeholder="No Telp" value="{{ old('no_telp') }}">
                        @if ($errors->has('no_telp'))
                            <span class="text-danger small">{{ $errors->first('no_telp') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Keterangan & Lokasi -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" name="keterangan" placeholder="Keterangan">{{ old('keterangan') }}</textarea>
                        @if ($errors->has('keterangan'))
                            <span class="text-danger small">{{ $errors->first('keterangan') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="lokasi">Lokasi</label>
                        <textarea class="form-control" name="lokasi" placeholder="Lokasi">{{ old('lokasi') }}</textarea>
                        @if ($errors->has('lokasi'))
                            <span class="text-danger small">{{ $errors->first('lokasi') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="card-footer text-center">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <button type="submit" class="btn btn-warning w-100"><i class="fas fa-paper-plane"></i> Kirim</button>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('prospects') }}" class="btn btn-secondary w-100"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("prospect-form");
            const loadingOverlay = document.getElementById("loading");
            const submitButton = form.querySelector("button[type='submit']");

            if (form && loadingOverlay && submitButton) {
                form.addEventListener("submit", function (e) {
                    loadingOverlay.style.display = "flex";
                    submitButton.disabled = true;
                    submitButton.textContent = "Loading...";
                    console.log("menjalankan submit");
                });
            } else {
                console.error("Form, loading overlay, atau tombol submit tidak ditemukan!");
            }
        });
    </script>
    @endpush
@endsection


{{-- @extends('layouts.contractor')
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
                <label for="no_telp">No Telp</label> <span class="text-danger small">harus dimulai dengan 62</span>
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
                <label for="lokasi">Lokasi</label>
                <textarea class="form-control" name="lokasi" placeholder="Lokasi"></textarea>
                @if ($errors->has('lokasi'))
                    <span class="text-danger small">{{ $errors->first('lokasi') }}</span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 d-grid">
                <button type="submit" class="btn btn-warning">Kirim</button>
            </div>
            <div class="col-md-6 d-grid">
                <a href="{{ route('prospects') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>

    </form>
@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("prospect-form");
    const loadingOverlay = document.getElementById("loading");
    const submitButton = form.querySelector("button[type='submit']");

    if (form && loadingOverlay && submitButton) {
        form.addEventListener("submit", function (e) {
            loadingOverlay.style.display = "flex";
            submitButton.disabled = true;
            submitButton.textContent = "Loading...";
            console.log("menjalankan submit");
        });
    } else {
        console.error("Form, loading overlay, atau tombol submit tidak ditemukan!");
    }
});
</script>
@endpush
@endsection --}}
