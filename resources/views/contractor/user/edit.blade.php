@extends('layouts.contractor')

@section('content')
    <hr />
    @include('components.loading')
    <div class="container">
        <form id="user-form-edit" class="p-4 card shadow-sm" action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header bg-primary text-white">
                <h4>Edit User</h4>
            </div>
            <div class="card-body">
                <!-- Nama Produk & Pemilik -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="position.nama" class="form-control" placeholder="Nama" value="{{ $user->profile->nama ?? 'nama' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Posisi</label>
                        <input type="text" name="position" class="form-control" placeholder="Pemilik" value="{{ $user->position }}" readonly>
                    </div>
                </div>

                <!-- Tanggal & No Telp -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Tanggal" value="{{ $user->email }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                <select name="status_account" class="form-control" required>
                    <option value="" disabled selected>Select Status</option>
                    <option value="active">Aktif</option>
                    <option value="unactive">Tidak Aktif</option>
                </select>
                    </div>
                </div>

                <!-- Keterangan & Lokasi -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Gambar</label>
                        <input type="text" name="foto" class="form-control" placeholder="Tanggal" value="{{ $user->profile->foto ?? 'foto' }}" readonly>
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
                        <a href="{{ route('users') }}" class="btn btn-secondary w-100"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("user-form-edit");
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
