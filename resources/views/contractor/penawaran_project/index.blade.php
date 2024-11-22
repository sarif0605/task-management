@extends('layouts.contractor')
@section('title', 'Home Penawaran Project')
@section('content')
    <hr class="mb-1" />
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered" id="table-penawaran" width="100%" cellspacing="0">
                    <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Pemilik</th>
                    <th>Maps</th>
                    <th>Kontak</th>
                    <th>Nama Penawar</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
        </thead>
    </table>

</div>
</div>
</div>
    @push('js')
        @include('contractor.penawaran_project.script')
    @endpush
@endsection
