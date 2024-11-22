@extends('layouts.contractor')

@section('title', 'Home Report Project')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-2">
    <form id="filter-form">
        <label for="deal_project">Filter by Deal Project:</label>
        <select id="deal_project" name="deal_project_id" class="form-control">
            <option value="">-- Select Deal Project --</option>
            @foreach ($deals as $deal)
                <option value="{{ $deal->id }}" style="color: black;">{{ $deal->prospect->nama_produk }}</option>
            @endforeach
        </select>
    </form>
</div>

<hr class="mb-1" />

<table id="table-report" class="table table-hover">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Nama Produk</th>
            <th>Pekerjaan</th>
            <th>Status</th>
            <th>Mulai</th>
            <th>Akhir</th>
            <th>Bobot</th>
            <th>Progress</th>
            <th>Durasi</th>
            <th>Harian</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <!-- Data rows will be injected by JavaScript -->
    </tbody>
</table>

@push('js')
    @include('contractor.report_project.script')
@endpush
@endsection
