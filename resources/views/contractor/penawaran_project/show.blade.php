@extends('layouts.contractor')

@section('content')
    <hr />

    <!-- Survey Form Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Penawaran Project Details</h5>
        </div>
        <div class="card-body">

            <!-- Name and Date Row -->
            <div class="mb-4">
                <h6 class="text-primary">Informasi Penawaran</h6>
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

            <!-- File Penawaran Section -->
            <div class="mb-4">
                <h6 class="text-primary">File Penawaran</h6>
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
                    <p class="text-muted mt-3">Tidak ada file yang diunggah untuk penawaran ini.</p>
                @endif
            </div>

            <!-- Buttons Section -->
            <div class="row mb-4">
                <div class="col-md-6 d-grid">
                    <a href="{{ route('penawaran_projects.edit', $penawaran->id) }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-edit"></i> Edit Penawaran
                    </a>
                </div>
                <div class="col-md-6 d-grid">
                    <a href="{{ route('penawaran_projects') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
