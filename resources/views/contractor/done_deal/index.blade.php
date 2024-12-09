@extends('layouts.contractor')
@section('title', 'Home Deal Project')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0">Deal Project Data</h5>
        </div>
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table-deal" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Pemilik</th>
                            <th>Alamat</th>
                            <th>Telp</th>
                            <th>Tanggal</th>
                            <th>Harga</th>
                            <th>RAB</th>
                            <th>RAP</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic rows will be here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('js')
        @include('contractor.done_deal.script')
    @endpush
@endsection
