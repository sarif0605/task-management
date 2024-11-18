@extends('layouts.contractor')

@section('title', 'Home Opnam')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-2">
        {{-- @if (Auth::user()->position == 'pengawas')
            <a href="{{ route('opnams.create') }}" class="btn btn-primary"><i class="fa-solid fa-square-plus"></i> Add</a>
        @endif --}}
    </div>
    <hr class="mb-1" />
    <table id="table-opnam" class="table table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Tanggal</th>
                <th>Pekerjaan</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    @push('js')
        @include('contractor.opnam.script')
    @endpush
@endsection
