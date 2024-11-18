@extends('layouts.contractor')

@section('title', 'Home Report Project')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-2">

    </div>
    <hr class="mb-1" />
    <table id="table-report" class="table table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Pekerjaan</th>
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
    @push('js')
        @include('contractor.report_project.script')
    @endpush
@endsection
