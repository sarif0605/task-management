@extends('layouts.contractor')

@section('content')
    <hr />

    <!-- Survey Form Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Survey Details</h5>
        </div>
        <div class="card-body">
            <!-- Name and Date Row -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control shadow-sm" placeholder="Nama" value="{{ $survey->prospect->nama_produk }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control shadow-sm" placeholder="Tanggal" value="{{ $survey->date }}" readonly>
                </div>
            </div>

            <!-- Survey Results Row -->
            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Hasil Survey</label>
                    <textarea class="form-control shadow-sm" name="survey_results" placeholder="Hasil Survey" readonly>{{ $survey->survey_results }}</textarea>
                </div>
            </div>

            <!-- Survey Images Row -->
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Gambar Survey</label>
                    <div class="row">
                        @forelse ($survey->survey_images as $image)
                            <div class="col-md-4 mb-3">
                                <img src="{{ asset('storage/survey/' . $image->image_url) }}" alt="Survey Image" class="img-fluid rounded shadow-sm" style="max-width: 100%; max-height: 150px;">
                            </div>
                        @empty
                            <div class="col">
                                <p class="text-muted">Tidak ada gambar tersedia.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="d-flex justify-content-end">
                <a href="{{ route('surveys') }}" class="btn btn-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
@endsection
