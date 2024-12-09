{{-- @extends('layouts.contractor')

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
@endsection --}}

@extends('layouts.contractor')
@section('content')
        <!-- Buttons for Attendance -->
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('attendance.create') }}" class="btn btn-success"><i class="fa-solid fa-square-plus"></i> Masuk</a>
            <a href="{{ route('attendance.edit') }}" class="btn btn-danger"><i class="fa-solid fa-square-minus"></i> Keluar</a>
        </div>

        <hr class="mb-3" />

        <!-- Attendance Table -->
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="m-0">Attendances Data</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-attendance" class="table table-striped table-bordered table-hover text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Foto</th>
                                <th>Waktu Masuk</th>
                                <th>Info Masuk</th>
                                <th>Foto Keluar</th>
                                <th>Waktu Keluar</th>
                                <th>Info Keluar</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

@push('js')
    @include('contractor.attendance.script')
@endpush

@endsection
