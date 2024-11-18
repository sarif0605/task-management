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
                    <th>File PDF</th>
                    <th>File Excel</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Update Penawaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="penawaran_project_id" name="penawaran_project_id">

                    <div class="mb-3">
                        <label for="pembuat_penawaran" class="form-label">Pembuat Penawaran</label>
                        <input type="text" name="pembuat_penawaran" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="file_pdf" class="form-label">File PDF</label>
                        <input type="file" name="file_pdf" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="file_excel" class="form-label">File Excel</label>
                        <input type="file" name="file_excel" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Show -->
<div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
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
                    {{-- <button type="submit" class="btn btn-primary">Simpan Perubahan</button> --}}
                </div>
            </form>
        </div>
    </div>
</div>
    @push('js')
        @include('contractor.penawaran_project.script')
    @endpush
@endsection
