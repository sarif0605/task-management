@extends('layouts.contractor')

@section('title', 'Show Report Project')

@section('content')
    <hr />
    <div class="row">
        <!-- Detail Fields -->
        <div class="col mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="deal_project_id" class="form-control" placeholder="Nama" value="{{ $report_project->deal_project->prospect->nama_produk }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Status</label>
            <input type="text" name="status" class="form-control" placeholder="Date" value="{{ $report_project->status }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Mulai</label>
            <input type="date" name="start_date" class="form-control" placeholder="Penawaran Harga" value="{{ $report_project->start_date }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Berakhir</label>
            <input type="date" name="end_date" class="form-control" placeholder="Penawaran Harga" value="{{ $report_project->end_date }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Bobot</label>
            <input type="number" name="bobot" class="form-control" placeholder="Penawaran Harga" value="{{ $report_project->bobot }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Progress</label>
            <input type="number" name="progress" class="form-control" placeholder="Penawaran Harga" value="{{ $report_project->progress }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Durasi</label>
            <input type="number" name="bobot" class="form-control" placeholder="Penawaran Harga" value="{{ $report_project->durasi }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Harian</label>
            <input type="number" name="harian" class="form-control" placeholder="Penawaran Harga" value="{{ $report_project->harian }}" readonly>
        </div>
    </div>

    <a href="{{ route('opnams.create', ['report_project_id' => $report_project->id]) }}" class="btn btn-primary">Opnams</a>
    <a href="{{ route('materials.create', ['report_project_id' => $report_project->id]) }}" class="btn btn-primary">Materials</a>
    <a href="{{ route('constraints.create', ['report_project_id' => $report_project->id]) }}" class="btn btn-primary">Constraints</a>
@endsection
