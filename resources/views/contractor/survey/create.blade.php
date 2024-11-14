<div class="modal fade" id="surveyModal" tabindex="-1" aria-labelledby="surveyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="surveyModalLabel">Add Survey</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="survey-form" action="{{ route('surveys.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="prospect_id" id="prospect_id"> <!-- Menyimpan prospect_id -->

                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal Survey</label>
                        <input type="date" name="date" class="form-control" placeholder="Tanggal">
                    </div>
                    <div class="mb-3">
                        <label for="survey_results" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="survey_results" placeholder="Hasil Survey"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="images" class="form-label">Gambar</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveSurveyBtn">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @include('contractor.prospect.script')
@endpush
{{-- @extends('layouts.contractor')
@section('title', 'Create Surveys')
@section('content')
<hr />
@include('components.loading')
<form id="survey-form" action="{{ route('surveys.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row mb-3">
        <div class="col">

        </div>
    </div>
    <div class="row mb-3">
        <div class="col">

        </div>
    </div>
    <div class="row">
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
    @push('scripts')
        @include('contractor.survey.script')
    @endpush
@endsection --}}
