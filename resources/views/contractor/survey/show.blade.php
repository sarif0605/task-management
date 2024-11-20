@extends('layouts.contractor')
@section('title', 'Show Surveys')
@section('content')
    <hr />
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" placeholder="Nama" value="{{ $survey->prospect->nama_produk }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="date" class="form-control" placeholder="Tanggal" value="{{ $survey->date }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Hasil Survey</label>
            <textarea class="form-control" name="survey_results" placeholder="Hasil Survey" readonly>{{ $survey->survey_results }}</textarea>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Gambar Survey</label>
            <div class="row">
                @forelse ($survey->survey_images as $image)
                    <div class="col-4 mb-3">
                        <img src="{{ asset('storage/survey/' . $image->image_url) }}" alt="Survey Image" class="img-fluid rounded" style="width: 100px; height: 100px;">
                    </div>
                @empty
                    <p class="text-muted">Tidak ada gambar tersedia.</p>
                @endforelse
            </div>
            <a href="{{route('surveys')}}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
