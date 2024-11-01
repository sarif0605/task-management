@extends('layouts.contractor')

@section('title', 'Create Opnams, Materials, Constraints')

@section('content')
<hr />
@include('components.loading')
    <h1 class="mb-0">Tambah Opnams, Materials, dan Constraints</h1>
    <hr />

    <form action="{{ route('store_project_data') }}" method="POST">
        @csrf
        <input type="hidden" name="deal_project_id" value="{{ $deal_project_id }}">
        <h2>Opnams</h2>
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="opnams_date" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Pekerjaan</label>
            <textarea name="opnams_pekerjaan" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Nominal Kasbon</label>
            <input type="number" name="kasbon_nominal" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="kasbon_keterangan" class="form-control"></textarea>
        </div>

        <h2>Materials</h2>
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="materials_tanggal" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Pekerjaan</label>
            <textarea name="materials_pekerjaan" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Material</label>
            <textarea name="materials_material" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Prioritas</label>
            <textarea name="materials_priority" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="materials_fordate" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="materials_keterangan" class="form-control"></textarea>
        </div>

        <!-- Fields for Constraints -->
        <h2>Constraints</h2>
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="constraints_tanggal" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Pekerjaan</label>
            <textarea name="constraints_pekerjaan" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Progress</label>
            <textarea name="constraints_progress" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Kendala</label>
            <textarea name="constraints_kendala" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
@endsection
