@extends('layouts.admin')

@section('title', 'Show Prospect')

@section('content')
    <h1 class="mb-0">Detail Prospect</h1>
    <hr />
    <div class="row"> 'name','tanggal','pemilik','lokasi','keterangan'
        <div class="col mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" placeholder="Nama" value="{{ $prospect->name }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Pemi;ik</label>
            <input type="text" name="pemilik" class="form-control" placeholder="Pemilik" value="{{ $prospect->pemilik }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" placeholder="Tanggal" value="{{ $prospect->tanggal }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Lokasi</label>
            <textarea class="form-control" name="lokasi" placeholder="Lokasi" readonly>{{ $prospect->lokasi }}</textarea>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control" name="keterangan" placeholder="Keterangan" readonly>{{ $prospect->keterangan }}</textarea>
        </div>
    </div>
    </div>
@endsection
