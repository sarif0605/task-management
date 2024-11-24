@extends('layouts.contractor')
@section('title', 'Home Report Project')
@section('content')
<div class="row mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Project Progress</h5>
        </div>
        <div class="card-body">
            <div id="progress-chart"></div>
            <div class="mt-3">
                <div class="d-flex justify-content-between">
                    <span>Total Progress:</span>
                    <span id="total-progress">0%</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Total Weight:</span>
                    <span id="total-weight">0</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Project Filter</h5>
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

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Project Tasks</h5>
    </div>
    <div class="card-body">
        <table id="table-report" class="table table-hover">
            <thead class="table-primary">
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
@push('js')
    @include('contractor.report_project.script')
@endpush
@endsection
