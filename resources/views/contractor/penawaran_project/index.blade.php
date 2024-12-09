@extends('layouts.contractor')
@section('content')
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Penawaran Project</h5>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Table Responsive -->
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="table-penawaran" width="100%" cellspacing="0">
                    <thead class="thead-light">
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
                    <tbody>
                        <!-- Data rows will be dynamically added here by JavaScript or backend -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('js')
        @include('contractor.penawaran_project.script')
    @endpush
@endsection
