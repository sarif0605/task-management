@extends('layouts.contractor')
@section('title', 'Create Opnams')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Create Opnams</h1>
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Opnam Details</h6>
        </div>
        <div class="card-body">
            <form id="opnam-form" action="{{ route('opnams.store') }}" method="POST">
                @csrf
                <input type="hidden" name="report_project_id" value="{{ $report_project_id }}">

                <!-- Container for Dynamic Entries -->
                <div id="project-entries">
                    <div class="project-entry border p-3 mb-3 rounded bg-light">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Tanggal</label>
                                <input type="date" name="entries[0][date]" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Pekerjaan</label>
                                <input type="text" name="entries[0][pekerjaan]" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-right">
                                <button type="button" class="btn btn-danger btn-icon-split remove-entry" style="display: none;">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Remove Entry</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add and Submit Buttons -->
                <div class="row mb-3">
                    <div class="col text-right">
                        <button type="button" class="btn btn-secondary btn-icon-split" id="addEntry">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Add Another Entry</span>
                        </button>
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-check"></i>
                            </span>
                            <span class="text">Submit All Entries</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let entryCount = 0;
    const entriesContainer = document.getElementById('project-entries');
    const addEntryButton = document.getElementById('addEntry');

    addEntryButton.addEventListener('click', function() {
        entryCount++;
        const newEntry = document.querySelector('.project-entry').cloneNode(true);
        newEntry.querySelectorAll('input').forEach(input => {
            input.name = input.name.replace('[0]', `[${entryCount}]`);
            input.value = '';
        });
        const removeButton = newEntry.querySelector('.remove-entry');
        if (removeButton) {
            removeButton.style.display = 'block';
            removeButton.addEventListener('click', function() {
                newEntry.remove();
            });
        }
        entriesContainer.appendChild(newEntry);
    });
});
</script>
@endpush
@endsection
