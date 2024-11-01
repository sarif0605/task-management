@extends('layouts.contractor')

@section('title', 'Home Kendala')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-2">
        @if (Auth::user()->position == 'pengawas')
            {{-- <a href="{{ route('materials.create') }}" class="btn btn-primary"><i class="fa-solid fa-square-plus"></i> Add</a> --}}
        @endif
    </div>
    <hr class="mb-1" />
    <table id="table-kendala" class="table table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Tanggal</th>
                <th>Pekerjaan</th>
                <th>Kendala</th>
                <th>Progress</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    @push('scripts')
        @include('contractor.constraint.script')
    @endpush
@endsection
