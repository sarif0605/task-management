@extends('layouts.contractor')
@section('content')
        <!-- Button Section -->
        <div class="d-flex justify-content-between mb-4">
            @if (Auth::user()->position->pluck('name')->intersect(['Marketing', 'Admin'])->isNotEmpty())
                <a href="{{ route('prospects.create') }}" class="btn btn-success"><i class="fas fa-plus-circle"></i> Add Prospect</a>
                <form action="{{ route('prospects.export') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                    @csrf
                    <div class="input-group">
                        <input type="file" name="file" class="form-control" aria-label="Upload File">
                        <input type="submit" class="btn btn-info" value="Import">
                    </div>
                </form>
            @endif
        </div>

        <hr class="mb-3" />

        <!-- Success Message -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Prospect Table -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="m-0">Prospects Data</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="table-opnam" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Produk</th>
                                <th>Tanggal</th>
                                <th>Pekerjaan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    @push('js')
        @include('contractor.opnam.script')
    @endpush

@endsection
