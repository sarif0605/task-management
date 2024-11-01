@extends('layouts.contractor')

@section('title', 'Home Prospect')

@section('content')
    <div class="d-flex align-items-center justify-content-between">
        @if (Auth::user()->position == 'marketing')
        <a href="{{ route('prospects.create') }}" class="btn btn-primary mb-2"><i class="fa-solid fa-square-plus"></i> Add</a>
        <form action="/city-import" method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        </form>
        @endif
    </div>
    <hr class="mb-1" />
    <table
        id="table-prospect"
        class="table table-hover table-striped table-hover text-center"
      >
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Pemilik</th>
            <th>Lokasi</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>

    @push('scripts')
        @include('contractor.prospect.script')
    @endpush
@endsection
