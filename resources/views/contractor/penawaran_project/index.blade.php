@extends('layouts.contractor')
@section('title', 'Home Penawaran Project')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-2">
        {{-- @if (Auth::user()->position == 'sales' || Auth::user()->position == 'admin')
            <a href="{{ route('deal_projects.create') }}" class="btn btn-primary"><i class="fa-solid fa-square-plus"></i> Add</a>
        @endif --}}
    </div>
    <hr class="mb-1" />
    <table id="table-penawaran" class="table table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Nama Penawar</th>
                <th>File PDF</th>
                <th>File Excel</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    @push('scripts')
        @include('contractor.penawaran_project.script')
    @endpush
@endsection
