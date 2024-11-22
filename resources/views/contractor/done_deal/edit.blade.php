@extends('layouts.contractor')
@section('title', 'Edit Product')
@section('content')
@include('components.loading')
<div class="container bg-white p-4 rounded shadow">
    <hr />
    <form id="deal-form-update" action="{{ route('deal_projects.update', $deal->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="row mb-3">
                <div class="col">
                    <select name="users[]" multiple class="form-control" required>
                        <option value="" disabled selected>Nama User</option>
                        @foreach($users as $user)
                            @foreach($user->position as $position)
                                <option value="{{ $user->id }}">{{ $position->name }} - {{ $user->profile->name ?? 'N/A' }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    @if ($errors->has('users'))
                        <span class="text-danger small">{{ $errors->first('users') }}</span>
                    @endif
                </div>
            </div>
            <div class="col mb-3">
                <label class="form-label">Tanggal</label>
                <input
                    type="date"
                    name="date"
                    class="form-control"
                    placeholder="Tanggal"
                    value="{{ old('date', $deal->date) }}">
                    @if ($errors->has('date'))
                        <span class="text-danger small">{{ $errors->first('date') }}</span>
                    @endif
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label class="form-label">Harga Deal</label>
                <input
                    type="number"
                    name="harga_deal"
                    class="form-control"
                    placeholder="Nominal"
                    value="{{ old('nominal', $deal->harga_deal) }}">
                    @if ($errors->has('harga_deal'))
                        <span class="text-danger small">{{ $errors->first('harga_deal') }}</span>
                    @endif
            </div>
        </div>
        <div class="mb-3">
            <label for="rab" class="form-label">RAB</label>
            <input type="file" name="rab" class="form-control">
            @if ($errors->has('rab'))
                <span class="text-danger small">{{ $errors->first('rab') }}</span>
            @endif
            <!-- Tampilkan File PDF yang Sudah Disimpan -->
            @if ($deal->rab)
                <div class="mt-2">
                    <a href="{{ asset('storage/rab/' . $deal->rab) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                        Lihat File
                    </a>
                </div>
            @endif
        </div>

        <!-- File Excel -->
        <div class="mb-3">
            <label for="rap" class="form-label">RAP</label>
            <input type="file" name="rap" class="form-control">
            @if ($errors->has('rap'))
                <span class="text-danger small">{{ $errors->first('rap') }}</span>
            @endif
            <!-- Tampilkan File Excel yang Sudah Disimpan -->
            @if ($deal->rap)
                <div class="mt-2">
                    <a href="{{ asset('storage/rap/' . $deal->rap) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                        Lihat File
                    </a>
                </div>
            @endif
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="form-control" >{{ $deal->keterangan }}</textarea>
                @if ($errors->has('keterangan'))
                    <span class="text-danger small">{{ $errors->first('keterangan') }}</span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 d-grid">
                <button class="btn btn-warning">Update</button>
            </div>
            <div class="col-md-6 d-grid">
                <a href="{{ route('deal_projects') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </form>
</div>

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("deal-form-update");
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
