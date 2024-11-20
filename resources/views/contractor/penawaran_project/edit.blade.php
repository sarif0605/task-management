@extends('layouts.contractor')
@section('title', 'Edit Penawaran Projects')
@section('content')
    <hr />
    @include('components.loading')
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
                <label for="file_pdf" class="form-label">File PDF</label>
                <input type="file" name="file_pdf" class="form-control">
                @if ($errors->has('file_pdf'))
                    <span class="text-danger small">{{ $errors->first('file_pdf') }}</span>
                @endif
                <!-- Tampilkan File PDF yang Sudah Disimpan -->
                @if ($penawaran->file_pdf)
                    <div class="mt-2">
                        <a href="{{ asset('storage/pdf/' . $penawaran->file_pdf) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                            Lihat File PDF
                        </a>
                    </div>
                @endif
            </div>

            <!-- File Excel -->
            <div class="mb-3">
                <label for="file_excel" class="form-label">File Excel</label>
                <input type="file" name="file_excel" class="form-control">
                @if ($errors->has('file_excel'))
                    <span class="text-danger small">{{ $errors->first('file_excel') }}</span>
                @endif
                <!-- Tampilkan File Excel yang Sudah Disimpan -->
                @if ($penawaran->file_excel)
                    <div class="mt-2">
                        <a href="{{ asset('storage/excel/' . $penawaran->file_excel) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                            Lihat File Excel
                        </a>
                    </div>
                @endif
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
        @include('contractor.penawaran_project.script')
    @endpush
@endsection
