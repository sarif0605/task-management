@extends('layouts.contractor')

@section('content')
    <hr />
    @include('components.loading')
    <div class="container">
        <form id="prospect-form-edit" class="p-4 card shadow-sm" action="{{ route('prospects.update', $prospect->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header bg-primary text-white">
                <h4>Edit Prospect</h4>
            </div>
            <div class="card-body">
                <!-- Nama Produk & Pemilik -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nama_produk" class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control form-control-underline" placeholder="Nama Produk" value="{{ $prospect->nama_produk }}">
                        @if ($errors->has('nama_produk'))
                            <span class="text-danger small">{{ $errors->first('nama_produk') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="pemilik" class="form-label">Pemilik</label>
                        <input type="text" name="pemilik" class="form-control form-control-underline" placeholder="Pemilik" value="{{ $prospect->pemilik }}">
                        @if ($errors->has('pemilik'))
                            <span class="text-danger small">{{ $errors->first('pemilik') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Tanggal & No Telp -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control form-control-underline" value="{{ $prospect->tanggal }}">
                        @if ($errors->has('tanggal'))
                            <span class="text-danger small">{{ $errors->first('tanggal') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="no_telp" class="form-label">No Telp</label>
                        <input type="number" name="no_telp" class="form-control form-control-underline" placeholder="No Telp" value="{{ $prospect->no_telp }}">
                        @if ($errors->has('no_telp'))
                            <span class="text-danger small">{{ $errors->first('no_telp') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Keterangan & Lokasi -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control form-control-underline" name="keterangan" placeholder="Keterangan">{{ $prospect->keterangan }}</textarea>
                        @if ($errors->has('keterangan'))
                            <span class="text-danger small">{{ $errors->first('keterangan') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <textarea class="form-control form-control-underline" name="lokasi" placeholder="Lokasi">{{ $prospect->lokasi }}</textarea>
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
                        <button type="submit" class="btn btn-warning w-100"><i class="fas fa-paper-plane"></i> Update</button>
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


{{-- @extends('layouts.contractor')
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
@endsection --}}
