@extends('layouts.contractor')
@section('title', 'Home Survey')
@section('content')
    <hr class="mb-1" />
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table-survey" width="100%" cellspacing="0">
                    <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Pemilik</th>
                    <th>Maps</th>
                    <th>Kontak</th>
                    <th>Tanggal</th>
                    <th>Survey</th>
                    <th>Gambar</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Update Survey</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="survey_id" name="survey_id">
                    <input type="hidden" id="prospect_id" name="prospect_id">
                    <input type="hidden" id="deleted_images" name="deleted_images" value="[]">

                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal Survey</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="survey_results" class="form-label">Hasil Survey</label>
                        <textarea class="form-control" name="survey_results" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar yang Ada</label>
                        <div id="existing-images" class="d-flex flex-wrap gap-2">
                            <!-- Existing images will be loaded here -->
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Gambar Baru</label>
                        <input type="file" name="image[]" class="form-control" multiple accept="image/*">
                        <small class="text-muted">Dapat memilih multiple gambar</small>
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
                    <h5 class="modal-title" id="exampleModalLabel">Show Survey</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="survey_id" name="survey_id">
                    <input type="hidden" id="prospect_id" name="prospect_id">
                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal Survey</label>
                        <input type="date" name="date" class="form-control" required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="survey_results" class="form-label">Hasil Survey</label>
                        <textarea class="form-control" name="survey_results" rows="3" required readonly></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar yang Ada</label>
                        <div id="existing-images" class="d-flex flex-wrap gap-2">

                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Gambar Baru</label>
                        <input type="file" name="image[]" class="form-control" multiple accept="image/*" readonly>
                        <small class="text-muted">Dapat memilih multiple gambar</small>
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
        @include('contractor.survey.script')
    @endpush
@endsection
