@extends('layouts.contractor')

@section('title', 'Show Deal Project')

@section('content')
    <h1 class="mb-0">Detail Prospect</h1>
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
            <label class="form-label">Penawaran Harga</label>
            <input type="number" name="price_quotation" class="form-control" placeholder="Penawaran Harga" value="{{ $dealProject->price_quotation }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Nominal</label>
            <input type="number" name="nominal" class="form-control" placeholder="Penawaran Harga" value="{{ $dealProject->nominal }}" readonly>
        </div>
    </div>
    <a href="{{ route('report_projects.create', ['deal_project_id' => $dealProject->id]) }}" class="btn btn-primary">Report Project</a>
@endsection
