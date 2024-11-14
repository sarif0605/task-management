@extends('layouts.contractor')

@section('title', 'Home Report Project')

@section('content')
    {{-- <div class="d-flex align-items-center justify-content-between mb-2">
        @if (Auth::user()->position == 'sales' || Auth::user()->position == 'admin')
            <a href="{{ route('report_projects.create') }}" class="btn btn-primary"><i class="fa-solid fa-square-plus"></i> Add</a>
        @endif
    </div> --}}
    <hr class="mb-1" />
    <table id="table-report" class="table table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>status</th>
                <th>Mulai</th>
                <th>Akhir</th>
                <th>Bobot</th>
                <th>Progress</th>
                <th>Durasi</th>
                <th>Harian</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    @push('scripts')
        @include('contractor.report_project.script')
    @endpush
@endsection
