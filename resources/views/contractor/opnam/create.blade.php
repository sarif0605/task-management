@extends('layouts.contractor')
@section('title', 'Create Prospect')
@section('content')
    <h1 class="mb-0">Add Deal Project</h1>
    <hr />
    <form action="{{ route('deal_projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <select name="prospect_id" class="form-control" required>
                <option value="" disabled selected>Nama Produk</option>
                @foreach ($prospect as $prospect)
                    <option value="{{ $prospect->id }}">{{ $prospect->name }}</option>
                @endforeach
            </select>
            <div class="col">
                <input type="date" name="tanggal" class="form-control" placeholder="Tanggal">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <input type="number" name="price_quotation" class="form-control" placeholder="Penawaran Harga">
            </div>
            <div class="col">
                <input type="number" name="nominal" class="form-control" placeholder="Nominal">
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
