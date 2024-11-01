@extends('layouts.contractor')
@section('title', 'Create Prospect')
@section('content')
    <hr />
    @include('components.loading')
    <form id="prospect-form" action="{{ route('prospects.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <input type="text" name="nama_produk" class="form-control" placeholder="Name">
            </div>
            <div class="col">
                <input type="text" name="pemilik" class="form-control" placeholder="Pemilik">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <input type="date" name="tanggal" class="form-control" placeholder="Tanggal">
            </div>
            <div class="col">
                <textarea class="form-control" name="lokasi" placeholder="Lokasi"></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <textarea class="form-control" name="keterangan" placeholder="Keterangan"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    @push('scripts')
        @include('contractor.prospect.script')
    @endpush
@endsection
