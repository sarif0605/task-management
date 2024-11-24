@extends('layouts.contractor')
@section('title', 'Show Deal Project')
@section('content')

<div class="container bg-white p-4 rounded shadow">
    <h2 class="mb-4">Detail Deal Project</h2>

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
        <h5>RAB</h5>
        @if ($dealProject->rab)
            <a href="{{ Storage::url('public/rab/'.$dealProject->rab) }}" target="_blank" class="btn btn-link">
                Lihat File RAB RAP
            </a>
        @else
            <p class="text-muted">Tidak ada data RAB RAP untuk project ini.</p>
        @endif
    </div>

    <div class="mb-4">
        <h5>RAP</h5>
        @if ($dealProject->rap)
            <a href="{{ Storage::url('public/rap/'.$dealProject->rap) }}" target="_blank" class="btn btn-link">
                Lihat File RAB RAP
            </a>
        @else
            <p class="text-muted">Tidak ada data RAB RAP untuk project ini.</p>
        @endif
    </div>

    <!-- Pengawas -->
    <div class="mb-4">
        <h5>Pengawas</h5>
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
        <div class="col-md-6 d-grid">
            <a href="{{ route('report_projects.create', ['deal_project_id' => $dealProject->id]) }}" class="btn btn-primary">Report Project</a>
        </div>
        @endif
        <div class="col-md-6 d-grid">
            <a href="{{ route('deal_projects') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
    @if (Auth::check() && (Auth::user()->hasPosition('Sales') || Auth::user()->hasPosition('Admin')))
        <form action="{{ route('report_projects.export', $dealProject->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Import Data Report Project</label>
                <input type="file" name="file" id="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Upload</button>
        </form>
    @endif
    <!-- Form Import -->
</div>

@endsection


{{-- @extends('layouts.contractor')
@section('title', 'Show Deal Project')
@section('content')
    <hr />
    <div class="row">
        <!-- Detail Fields -->
        <div class="col mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="prospect_id" class="form-control" placeholder="Nama" value="{{ $dealProject->prospect->nama_produk }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="date" class="form-control" placeholder="Date" value="{{ $dealProject->date }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="harga_deal" class="form-control" placeholder="Penawaran Harga" value="{{ $dealProject->harga_deal }}" readonly>
        </div>
    </div>

    <!-- Tombol Navigasi -->
    <a href="{{ route('report_projects.create', ['deal_project_id' => $dealProject->id]) }}" class="btn btn-primary">Report Project</a>
    <a href="{{route('deal_projects')}}" class="btn btn-warning">Kembali</a>

    <!-- Form Import -->
    <form action="{{ route('report_projects.export', $dealProject->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Import Data Report Project</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Upload</button>
    </form>
@endsection --}}
