@extends('layouts.contractor')
@section('title', 'Edit Penawaran Projects')
@section('content')
@include('components.loading')
<hr />
    <div class="container bg-white p-4 rounded shadow">
        <form id="penawaran-form-edit" action="{{ route('penawaran_projects.update', $penawaran->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="penawaran_project_id" id="penawaran_project_id">

            <!-- Nama Penawar -->
            <div class="mb-3">
                <label for="pembuat_penawaran" class="form-label">Nama Penawar</label>
                <input type="text" name="pembuat_penawaran" class="form-control" placeholder="Nama Penawaran" value="{{ $penawaran->pembuat_penawaran }}">
                @if ($errors->has('pembuat_penawaran'))
                    <span class="text-danger small">{{ $errors->first('pembuat_penawaran') }}</span>
                @endif
            </div>

            <!-- File PDF -->
            <div class="mb-3">
                <label for="image" class="form-label">File</label>
                <input type="file" id="file" name="file[]" class="form-control" multiple>
                @error('file')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tombol -->
            <div class="row">
                <div class="col-md-6 d-grid">
                    <button class="btn btn-warning">Update</button>
                </div>
                <div class="col-md-6 d-grid">
                    <a href="{{ route('penawaran_projects') }}" class="btn btn-primary">Kembali</a>
                </div>
            </div>
        </form>
    </div>
    @push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("penawaran-form-edit");
            const loadingOverlay = document.getElementById("loading");
            const submitButton = form.querySelector("button[type='submit']");

            if (form && loadingOverlay && submitButton) {
                form.addEventListener("submit", function () {
                    loadingOverlay.style.display = "flex"; // Tampilkan loading overlay
                    submitButton.disabled = true; // Nonaktifkan tombol submit
                    submitButton.textContent = "Loading..."; // Ubah teks tombol
                });
            } else {
                console.error("Form, loading overlay, atau tombol submit tidak ditemukan!");
            }
        });
    </script>
    @endpush
@endsection
