@extends('layouts.contractor')
@section('title', 'Show Deal Project')
@section('content')
    <hr />
    <div class="row">
        <!-- Detail Fields -->
        <div class="col mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="prospect_id" class="form-control" placeholder="Nama" value="{{ $dealProject->prospect->name }}" readonly>
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
@endsection
