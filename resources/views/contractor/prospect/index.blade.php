@extends('layouts.contractor')

@section('title', 'Home Prospect')

@section('content')
    <div class="d-flex align-items-center justify-content-between">
        @if (Auth::user()->position->pluck('name')->intersect(['Marketing', 'Admin'])->isNotEmpty())
        <a href="{{ route('prospects.create') }}" class="btn btn-primary mb-2"><i class="fa-solid fa-square-plus"></i> Add</a>
        <form action="/city-import" method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        </form>
        @endif
    </div>
    <hr class="mb-1" />
    <table
        id="table-prospect"
        class="table table-hover table-striped"
      >
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Pemilik</th>
            <th>Lokasi</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Survey</th>
            <th>Penawaran</th>
            <th>Deal</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>

      <div class="modal fade" id="surveyModal" tabindex="-1" aria-labelledby="surveyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="surveyModalLabel">Add Survey</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('components.loading')
                    <form id="survey-form" action="{{ route('surveys.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="prospect_id" id="prospect_id"> <!-- Menyimpan prospect_id -->

                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal Survey</label>
                            <input type="date" name="date" class="form-control" placeholder="Tanggal">
                        </div>
                        <div class="mb-3">
                            <label for="survey_results" class="form-label">Keterangan</label>
                            <textarea class="form-control" name="survey_results" placeholder="Hasil Survey"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">Gambar</label>
                            <input type="file" name="image[]" class="form-control" multiple accept="image/*">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveSurveyBtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="penawaranModal" tabindex="-1" aria-labelledby="penawaranModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="penawaranModalLabel">Tambah Penawaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('components.loading')
                    <form id="penawaran-form" action="{{ route('penawaran_projects.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="prospect_id" name="prospect_id" value="">

                        <div class="mb-3">
                            <label for="pembuat_penawaran" class="form-label">Pembuat Penawaran <span class="text-danger">*</span></label>
                            <input type="text" name="pembuat_penawaran" id="pembuat_penawaran" class="form-control" placeholder="Masukkan nama pembuat penawaran">
                        </div>

                        <div class="mb-3">
                            <label for="file_pdf" class="form-label">File PDF <span class="text-danger">*</span></label>
                            <input type="file" name="file_pdf" id="file_pdf" class="form-control" accept=".pdf">
                            <small class="text-muted">Maksimal 10MB</small>
                        </div>

                        <div class="mb-3">
                            <label for="file_excel" class="form-label">File Excel <span class="text-danger">*</span></label>
                            <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx,.xls">
                            <small class="text-muted">Maksimal 10MB</small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" id="savePenawaranBtn">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dealModal" tabindex="-1" aria-labelledby="dealModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dealModalLabel">Add Deal Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('components.loading')
                    <form id="deal-form" action="{{ route('deal_projects.store') }}" method="POST">
                        @csrf
                        <!-- Perbaikan pada input hidden -->
                        <input type="hidden" id="prospect_id" name="prospect_id" value="">

                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal Deal</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_deal" class="form-label">Harga Deal</label>
                            <input type="number" name="harga_deal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="users" class="form-label">Pengawas</label>
                            <select name="users[]" multiple class="form-control" required>
                                @foreach($users as $user)
                                    @foreach($user->position as $position)
                                        @if($position->name !== 'Admin')
                                            <option value="{{ $user->id }}">
                                                {{ $position->name }} - {{ $user->profile->name ?? 'N/A' }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveDealBtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @include('contractor.prospect.script')
    @endpush
@endsection
