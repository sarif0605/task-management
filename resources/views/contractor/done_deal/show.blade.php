@extends('layouts.contractor')
@section('content')

<div class="container bg-white p-4 rounded shadow">
    <div class="card-header bg-primary text-white mb-4">
        <h4>Detail Deal Project</h4>
    </div>

    <!-- Informasi Umum -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" class="form-control" value="{{ $dealProject->prospect->nama_produk }}" readonly>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control" value="{{ $dealProject->date }}" readonly>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Harga</label>
            <input type="number" class="form-control" value="{{ $dealProject->harga_deal }}" readonly>
        </div>
    </div>

    <!-- RAB RAP -->
    <div class="mb-4">
        <h5 class="font-weight-bold">RAB</h5>
        @if ($dealProject->rab)
            <a href="{{ Storage::url('public/rab/'.$dealProject->rab) }}" target="_blank" class="btn btn-link">
                <i class="fas fa-file-pdf"></i> Lihat File RAB
            </a>
        @else
            <p class="text-muted">Tidak ada data RAB untuk project ini.</p>
        @endif
    </div>

    <div class="mb-4">
        <h5 class="font-weight-bold">RAP</h5>
        @if ($dealProject->rap)
            <a href="{{ Storage::url('public/rap/'.$dealProject->rap) }}" target="_blank" class="btn btn-link">
                <i class="fas fa-file-pdf"></i> Lihat File RAP
            </a>
        @else
            <p class="text-muted">Tidak ada data RAP untuk project ini.</p>
        @endif
    </div>

    <!-- Pengawas -->
    <div class="mb-4">
        <h5 class="font-weight-bold">Pengawas</h5>
        @if ($dealProject->deal_project_users && $dealProject->deal_project_users->isNotEmpty())
            <ul class="list-group">
                @foreach ($dealProject->deal_project_users as $userRelation)
                    @php
                        $user = $userRelation->user;
                        $profile = $user->profile;
                    @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $profile->name ?? 'Tidak ada nama' }}</span>
                        <span class="text-muted">{{ $user->email }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Tidak ada pengawas untuk project ini.</p>
        @endif
    </div>

    <!-- Tombol Navigasi -->
    <div class="row mt-4">
        @if (Auth::check() && (Auth::user()->hasPosition('Sales') || Auth::user()->hasPosition('Admin')))
            <div class="col-md-6 d-grid mb-3">
                <a href="{{ route('report_projects.create', ['deal_project_id' => $dealProject->id]) }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Report Project
                </a>
            </div>
        @endif
        <div class="col-md-6 d-grid mb-3">
            <a href="{{ route('deal_projects') }}" class="btn btn-warning">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if (Auth::check() && (Auth::user()->hasPosition('Sales') || Auth::user()->hasPosition('Admin')))
        <form action="{{ route('report_projects.export', $dealProject->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Import Data Report Project</label>
                <input type="file" name="file" id="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-upload"></i> Upload
            </button>
        </form>
    @endif
</div>

@endsection
