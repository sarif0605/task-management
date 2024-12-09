@extends('layouts.contractor')
@section('content')
    <div class="container-fluid">

        <div class="row">
            <!-- Card for Detail Fields -->
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary">
                        <h6 class="m-0 font-weight-bold text-white">Detail Project</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Nama Produk</label>
                                <input type="text" class="form-control" value="{{ $report_project->deal_project->prospect->nama_produk }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Status</label>
                                <input type="text" class="form-control" value="{{ $report_project->status }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Mulai</label>
                                <input type="date" class="form-control" value="{{ $report_project->start_date }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Berakhir</label>
                                <input type="date" class="form-control" value="{{ $report_project->end_date }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Bobot</label>
                                <input type="number" class="form-control" value="{{ $report_project->bobot }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Progress</label>
                                <input type="number" class="form-control" value="{{ $report_project->progress }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Durasi</label>
                                <input type="number" class="form-control" value="{{ $report_project->durasi }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Harian</label>
                                <input type="number" class="form-control" value="{{ $report_project->harian }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary">
                        <h6 class="m-0 font-weight-bold text-primary">Aksi</h6>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('opnams.create', ['report_project_id' => $report_project->id]) }}" class="btn btn-primary btn-icon-split mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-file-alt"></i>
                            </span>
                            <span class="text">Opnams</span>
                        </a>
                        <a href="{{ route('materials.create', ['report_project_id' => $report_project->id]) }}" class="btn btn-success btn-icon-split mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-box"></i>
                            </span>
                            <span class="text">Materials</span>
                        </a>
                        <a href="{{ route('constraints.create', ['report_project_id' => $report_project->id]) }}" class="btn btn-danger btn-icon-split mb-2">
                            <span class="icon text-white-50">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            <span class="text">Constraints</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
