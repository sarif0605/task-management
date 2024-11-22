@extends('layouts.contractor')
@section('title', 'Home Deal Project')
@section('content')
    <hr class="mb-1" />
    <div class="card shadow mb-4">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table-deal" width="100%" cellspacing="0">
                    <thead>
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
    </table>
</div>
</div>
</div>

<!-- Modal Update -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('components.loading')
            <form id="updateForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Project Deal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" id="deal_project_id" name="deal_project_id">
                    <input type="hidden" id="prospect_id" name="prospect_id">

                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" name="date" class="form-control" id="date" required>
                    </div>

                    <!-- Tambahkan id untuk setiap field -->
                    <div class="mb-3">
                        <label for="harga_deal" class="form-label">Harga</label>
                        <input type="number" name="harga_deal" id="harga_deal" class="form-control" required>
                    </div>

                    <!-- Perbaikan pada select multiple -->
                    <div class="mb-3">
                        <label for="users" class="form-label">Pengawas</label>
                        <select name="users[]" id="users" multiple class="form-control" required>
                            @foreach($users as $user)
                                @if($user->position->contains('name', '!=', 'Admin'))
                                    <option value="{{ $user->id }}">
                                        {{ $user->position->first()->name }} - {{ $user->profile->name ?? 'N/A' }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="rab" class="form-label">RAB</label>
                        <input type="file" name="rab" id="rab" class="form-control">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file</small>
                    </div>

                    <div class="mb-3">
                        <label for="rap" class="form-label">RAP</label>
                        <input type="file" name="rap" id="rap" class="form-control">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file</small>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">T
                    </button>
                    <button type="submit" class="btn btn-primary">submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Show -->
{{-- <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('components.loading')
            <form id="showForm" method="GET" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Show Penawaran Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="penawaran_project_id" name="penawaran_project_id">
                    <input type="hidden" id="prospect_id" name="prospect_id">
                    <div class="mb-3">
                        <label for="pembuat_penawaran" class="form-label">Pembuat Penawaran</label>
                        <input type="text" name="pembuat_penawaran" class="form-control" required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="file_pdf" class="form-label">File PDF</label>
                        <input type="file" name="file_pdf" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="file_excel" class="form-label">File Excel</label>
                        <input type="file" name="file_excel" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                    {{-- <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
    @push('js')
        @include('contractor.done_deal.script')
    @endpush
@endsection
