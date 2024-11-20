@extends('layouts.contractor')
@section('title', 'Home Penawaran Project')
@section('content')
    <hr class="mb-1" />
    <div class="card shadow mb-4">
        <div class="card-body">
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
                    <th>PDF</th>
                    <th>Excel</th>
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
