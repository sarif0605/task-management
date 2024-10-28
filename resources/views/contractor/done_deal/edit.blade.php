//resources/views/products/edit.blade.php
@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
    <h1 class="mb-0">Edit Deal Project</h1>
    <hr />
    <form action="{{ route('deal_projects.update', $dealProject->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Nama Produk</label>
                <select name="prospect_id" class="form-control" required>
                    @foreach ($prospect as $prospect)
                        <option value="{{ $prospect->id }}" {{ $prospect->id == $survey->prospect_id ? 'selected' : '' }}>
                            {{ $prospect->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" placeholder="Tanggal" value="{{ $dealProject->tanggal }}" >
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Penawaran harga</label>
                <input type="number" name="price_quotation" class="form-control" placeholder="Penawaran Harga" value="{{ $dealProject->price_quotation }}" >
            </div>
            <div class="col mb-3">
                <label class="form-label">Nominal</label>
                <input type="number" name="nominal" class="form-control" placeholder="Nominal" value="{{ $dealProject->nominal }}" >
            </div>
        </div>
        <div class="row">
            <div class="d-grid">
                <button class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
@endsection
