@extends('layouts.contractor')
@section('title', 'Home Survey')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-2">
        @if (Auth::user()->position == 'sales' || Auth::user()->position == 'admin')
            <a href="{{ route('surveys.create') }}" class="btn btn-primary"><i class="fa-solid fa-square-plus"></i> Add</a>
        @endif
    </div>
    <hr class="mb-1" />
    <table
        id="table-survey"
        class="table table-hover mt-3 table-striped table-hover"
      >
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Hasil Survey</th>
            <th>Gambar</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>

    @push('scripts')
        @include('contractor.survey.script')
    @endpush
@endsection
