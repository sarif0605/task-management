@extends('layouts.contractor')

@section('title', 'Show Prospect')

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
    </div>
    @push('scripts')
        @include('contractor.survey.script')
    @endpush
@endsection
