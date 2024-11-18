@extends('layouts.contractor')

@section('title', 'Home Prospect')

@section('content')
    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ route('attendance.create') }}" class="btn btn-primary mb-2"><i class="fa-solid fa-square-plus"></i> Masuk</a>
    </div>
    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ route('attendance.edit') }}" class="btn btn-primary mb-2"><i class="fa-solid fa-square-plus"></i> Keluar</a>
    </div>
    <hr class="mb-1" />
    <table
        id="table-attendance"
        class="table table-hover table-striped"
      >
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Foto</th>
            <th>Waktu</th>
            <th>Info</th>
            <th>Foto</th>
            <th>Waktu</th>
            <th>Info</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    @push('js')
        @include('contractor.attendance.script')
    @endpush
@endsection
