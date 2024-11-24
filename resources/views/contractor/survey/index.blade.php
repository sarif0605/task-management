@extends('layouts.contractor')
@section('title', 'Home Survey')
@section('content')
    <hr class="mb-1" />
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <table class="table table-bordered" id="table-survey" width="100%" cellspacing="0">
                    <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Pemilik</th>
                    <th>Maps</th>
                    <th>Kontak</th>
                    <th>Keterangan</th>
                    <th>Tgl Prospect</th>
                    <th>Survey</th>
                    <th>Tgl Survey</th>
                    <th>Gambar</th>
                    <th>Action</th>
                </tr>
        </thead>
      </table>
    </div>
</div>
</div>
    @push('js')
        @include('contractor.survey.script')
    @endpush
@endsection
