@extends('layouts.contractor')
@section('title', 'Detail Penawaran Project')
@section('content')

<div class="container bg-white p-4 rounded shadow">
    <h2 class="mb-4">Detail Penawaran Project</h2>

    <!-- Informasi Penawaran -->
    <div class="mb-4">
        <h5>Informasi Penawaran</h5>
        <table class="table table-bordered">
            <tr>
                <th>Nama Penawar</th>
                <td>{{ $penawaran->pembuat_penawaran }}</td>
            </tr>
            <tr>
                <th>Prospek</th>
                <td>{{ $penawaran->prospect->nama_produk ?? '-' }}</td>
            </tr>
            <tr>
                <th>Dibuat Pada</th>
                <td>{{ $penawaran->created_at->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <th>Diperbarui Pada</th>
                <td>{{ $penawaran->updated_at->format('d M Y, H:i') }}</td>
            </tr>
        </table>
    </div>

    <!-- File Penawaran -->
    <div class="mb-4">
        <h5>File Penawaran</h5>
        @if ($penawaran->file_penawaran_project->isNotEmpty())
            <ul class="list-group">
                @foreach ($penawaran->file_penawaran_project as $file)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ Storage::url('penawaran/' . $file->file) }}" target="_blank" class="text-primary">
                            {{ $file->file }}
                        </a>
                        <span>{{ $file->created_at->format('d M Y, H:i') }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Tidak ada file yang diunggah untuk penawaran ini.</p>
        @endif
    </div>

    <!-- Tombol -->
    <div class="row mt-4">
        <div class="col-md-6 d-grid">
            <a href="{{ route('penawaran_projects.edit', $penawaran->id) }}" class="btn btn-warning">Edit Penawaran</a>
        </div>
        <div class="col-md-6 d-grid">
            <a href="{{ route('penawaran_projects') }}" class="btn btn-primary">Kembali ke Daftar</a>
        </div>
    </div>
</div>

@endsection
