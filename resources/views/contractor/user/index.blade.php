{{-- @extends('layouts.contractor')

@section('title', 'Home User')

@section('content')
    <hr class="mb-1" />
    <table
        id="table-user"
        class="table table-hover table-striped table-hover text-center"
      >
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Posisi</th>
            <th>Email</th>
            <th>Verifikasi Email</th>
            <th>Status</th>
            <th>Gambar</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>

    @push('js')
        @include('contractor.user.script')
    @endpush
@endsection --}}
@extends('layouts.contractor')
@section('title', 'Home User')
@section('content')
        <!-- Attendance Table -->
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="m-0">User Records</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-user" class="table table-striped table-bordered table-hover text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Email</th>
                                <th>Verifikasi Email</th>
                                <th>Status</th>
                                <th>Gambar</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

@push('js')
@include('contractor.user.script')
@endpush
@endsection
