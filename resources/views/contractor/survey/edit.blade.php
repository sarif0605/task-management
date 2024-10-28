//resources/views/products/edit.blade.php
@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
    <h1 class="mb-0">Edit Prospect</h1>
    <hr />
    <form action="{{ route('surveys.update', $survey->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Nama Produk</label>
                <select name="prospect_id" class="form-control" required>
                    @foreach ($prospect as $prospect)
                        <option value="{{ $prospect->id }}" {{ $prospect->id == $survey->prospect_id ? 'selected' : '' }}>
                            {{ $prospect->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" class="form-control" placeholder="Tanggal" value="{{ $survey->date }}" >
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Hasil Survey</label>
                <textarea class="form-control" name="survey_results" placeholder="Hasil Survey" >{{ $survey->survey_results }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="d-grid">
                <button class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
@endsection
