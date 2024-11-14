@extends('layouts.contractor')
@section('title', 'Create Prospect')
@section('content')
    <hr />
    @include('components.loading')
    <form id="prospect-form" class="p-10" action="{{ route('prospects.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control" placeholder="Name">
            </div>
            <div class="col">
                <label for="pemilik">Pemilik</label>
                <input type="text" name="pemilik" class="form-control" placeholder="Pemilik">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" placeholder="Tanggal">
            </div>
            <div class="col">
                <label for="lokasi">Lokasi</label>
                <textarea class="form-control" name="lokasi" placeholder="Lokasi"></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="keterangan">Keterangan</label>
                <textarea class="form-control" name="keterangan" placeholder="Keterangan"></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('prospects') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>

    </form>
    @push('scripts')
        @include('contractor.prospect.script')
    @endpush
@endsection
