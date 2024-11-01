@extends('layouts.contractor')
@section('title', 'CreateProject Deal')
@section('content')
<div class="container">
    <form action="{{ route('deal_projects.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <select name="prospect_id" class="form-control" required>
                    <option value="" disabled selected>Nama Produk</option>
                    @foreach ($prospect as $prospect)
                        <option value="{{ $prospect->id }}">{{ $prospect->nama_produk }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="date" name="date" class="form-control" placeholder="Tanggal">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <input type="number" type="number" name="nominal" step="0.01" class="form-control" placeholder="Nominal">
            </div>
            <div class="col">
                <input type="number" type="number" name="price_quotation" step="0.01" class="form-control" placeholder="Price Quotation">
            </div>
        </div>

        {{-- <div>
            <label for="nominal">Nominal:</label>
            <input  value="{{ old('nominal') }}" required>
            @error('nominal')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div> --}}

        <!-- Operational Project Fields -->

        <div class="row mb-3">
            <div class="col">
                <textarea class="form-control" name="keterangan" placeholder="Keterangan"></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <select name="users[]" multiple class="form-control" required>
                    <option value="" disabled selected>Nama User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->position }}</option>
                    @endforeach
                </select>
            </div>
            </div>

        <button type="submit">Submit</button>
    </form>
</div>
@endsection


{{-- @extends('layouts.contractor')
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
@endsection --}}
