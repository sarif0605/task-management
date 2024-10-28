//resources/views/products/create.blade.php
@extends('layouts.admin')
@section('title', 'Create Surveys')
@section('content')
    <hr />
    <form action="{{ route('prospects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <select name="prospect_id" class="form-control" required>
                    <option value="" disabled selected>Nama Produk</option>
                    @foreach ($prospect as $prospect)
                        <option value="{{ $prospect->id }}">{{ $prospect->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="date" name="date" class="form-control" placeholder="Tanggal">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <textarea class="form-control" name="survey_results" placeholder="Hasil Survey"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
