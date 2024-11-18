@extends('layouts.contractor')

@section('title', 'Home Material')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-2">
        {{-- @if (Auth::user()->position == 'pengawas')
            <a href="{{ route('materials.create') }}" class="btn btn-primary"><i class="fa-solid fa-square-plus"></i> Add</a>
        @endif --}}
    </div>
    <hr class="mb-1" />
    <table id="table-material" class="table table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Tanggal</th>
                <th>Pekerjaan</th>
                <th>Material</th>
                <th>Prioritas</th>
                <th>Waktu Peruntukan</th>
                <th>Keterangan</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>

    @push('js')
        @include('contractor.material.script')
    @endpush
@endsection
