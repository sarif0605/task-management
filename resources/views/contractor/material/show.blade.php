@extends('layouts.contractor')

@section('title', 'Show Material')

@section('content')
    <hr />
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="deal_project_id" class="form-control" placeholder="Nama" value="{{ $constraint->prospect->nama_produk }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" placeholder="Date" value="{{ $constraint->tanggal }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Pekerjaan</label>
            <input type="text" name="price_quotation" class="form-control" placeholder="Penawaran Harga" value="{{ $constraint->pekerjaan }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Material</label>
            <input type="text" name="material" class="form-control" placeholder="Penawaran Harga" value="{{ $constraint->material }}" readonly>
        </div>
    </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Prioritas</label>
                <input type="text" name="priority" class="form-control" placeholder="Penawaran Harga" value="{{ $constraint->priority }}" readonly>
            </div>
            <div class="col mb-3">
                <label class="form-label">Untuk Tanggal</label>
                <input type="date" name="for_date" class="form-control" placeholder="Penawaran Harga" value="{{ $constraint->for_date }}" readonly>
            </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Keterangan</label>
            <input type="text" name="keterangan" class="form-control" placeholder="Penawaran Harga" value="{{ $constraint->keterangan }}" readonly>
        </div>
</div>
    </div>
@endsection
