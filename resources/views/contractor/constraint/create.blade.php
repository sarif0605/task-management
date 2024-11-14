@extends('layouts.contractor')
@section('title', 'Create Kendala')
@section('content')
<form id="constraint-form" action="{{ route('constraints.store') }}" method="POST">
    @csrf
    <input type="hidden" name="report_project_id" value="{{ $report_project_id }}">
    <div id="project-entries">
        <div class="project-entry border p-3 mb-3 rounded">
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="entries[0][tanggal]" class="form-control" required>
                </div>
                <div class="col">
                    <label class="form-label">Pekerjaan</label>
                    <input type="text" name="entries[0][pekerjaan]" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Progress</label>
                    <input type="text" name="entries[0][progress]" class="form-control" required>
                </div>
                <div class="col">
                    <label class="form-label">Kendala</label>
                    <input type="text" name="entries[0][kendala]" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <button type="button" class="btn btn-danger remove-entry" style="display: none;">Remove Entry</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons to add and submit entries -->
    <div class="row mb-3">
        <div class="col">
            <button type="button" class="btn btn-secondary" id="addEntry">Add Another Entry</button>
            <button type="submit" class="btn btn-primary">Submit All Entries</button>
        </div>
    </div>
</form>

@push('scripts')
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
