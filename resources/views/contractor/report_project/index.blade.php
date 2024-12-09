@extends('layouts.contractor')
@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Project Progress Card -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Project Progress</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="chartPekerjaan"></canvas>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p><strong>Completed Tasks:</strong> <span id="completed-tasks">{{ $progress['completedTasks'] ?? 0 }}</span>/<span id="total-tasks">{{ $progress['totalTasks'] ?? 0 }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Form for Projects -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Project Filter</h6>
                </div>
                <div class="card-body">
                    <form id="filter-form">
                        <div class="form-group">
                            <label for="deal_project">Select Project:</label>
                            <select id="deal_project" name="deal_project_id" class="form-control">
                                <option value="">-- Select Deal Project --</option>
                                @foreach ($deals as $deal)
                                    <option value="{{ $deal->id }}">{{ $deal->prospect->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Tasks Table -->
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Project Tasks</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-report" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Produk</th>
                            <th>Pekerjaan</th>
                            <th>Status</th>
                            <th>Mulai</th>
                            <th>Akhir</th>
                            <th>Bobot</th>
                            <th>Progress</th>
                            <th>Durasi</th>
                            <th>Harian</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Report Modal -->
<!-- Modal -->
<div class="modal fade" id="editReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-report-form">
                <input type="hidden" id="edit-report-id" name="id" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" id="edit-report-id" name="id" value="">
                            <label for="status">Status:</label>
                            <select id="status" name="status" class="form-control">
                                <option value="plan">Plan</option>
                                <option value="belum">Belum</option>
                                <option value="mulai">Mulai</option>
                                <option value="selesai">Selesai</option>
                            </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
@include('contractor.report_project.script')
@endpush
@endsection
