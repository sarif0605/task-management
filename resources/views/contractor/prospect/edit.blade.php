@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
    <h1 class="mb-0">Edit Prospect</h1>
    <hr /> 'name','tanggal','pemilik','lokasi','keterangan'
    <form action="{{ route('prospects.update', $prospect->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $prospect->name }}" >
            </div>
            <div class="col mb-3">
                <label class="form-label">Pemilik</label>
                <input type="text" name="pemilik" class="form-control" placeholder="Pemilik" value="{{ $prospect->pemilik }}" >
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" placeholder="Tanggal" value="{{ $prospect->tanggal }}" >
            </div>
            <div class="col mb-3">
                <label class="form-label">Lokasi</label>
                <textarea class="form-control" name="lokasi" placeholder="Lokasi" >{{ $prospect->lokasi }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Keterangan</label>
                <textarea class="form-control" name="keterangan" placeholder="Keterangan" >{{ $prospect->keterangan }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="d-grid">
                <button class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
@endsection
