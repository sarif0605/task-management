@extends('layouts.contractor')
@section('title', 'Home Prospect')
@section('content')
    <div class="d-flex align-items-center justify-content-between">
        @if (Auth::user()->position->pluck('name')->intersect(['Marketing', 'Admin'])->isNotEmpty())
        <a href="{{ route('prospects.create') }}" class="btn btn-primary mb-2"><i class="fa-solid fa-square-plus"></i> Add</a>
        <form action="{{route('prospects.export')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file">
            <input type="submit" class="btn btn-primary" value="import">
        </form>
        @endif
    </div>
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
                <table class="table table-bordered" id="table-prospect" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Pemilik</th>
                        <th>Lokasi</th>
                        <th>Telp</th>
                        <th>Keterangan</th>
                        {{-- <th>Status</th> --}}
                        <th>Survey</th>
                        <th>Tawar</th>
                        <th>Deal</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
    @push('js')
        @include('contractor.prospect.script')
    @endpush
@endsection
