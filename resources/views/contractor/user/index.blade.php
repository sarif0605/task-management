@extends('layouts.contractor')

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
@endsection
