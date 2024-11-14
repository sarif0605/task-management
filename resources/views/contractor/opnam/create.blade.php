@extends('layouts.contractor')
@section('title', 'Create Opnams')
@section('content')
<form id="opnam-form" action="{{ route('opnams.store') }}" method="POST">
    @csrf
    <input type="hidden" name="report_project_id" value="{{ $report_project_id }}">
    <div id="project-entries">
        <div class="project-entry border p-3 mb-3 rounded">
            <div class="row mb-3">
                {{-- <div class="col">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="entries[0][lokasi]" class="form-control" required>
                </div> --}}
                <div class="col">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="entries[0][date]" class="form-control" required>
                </div>
            </div>
            {{-- <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Pekerjaan</label>
                    <input type="text" name="entries[0][pekerjaan]" class="form-control" required>
                </div>
            </div> --}}
            <div class="row mb-3">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" name="entries[0][pekerjaan]" class="form-control" required>
                    </div>
                </div>
                {{-- <div class="col">
                    <label class="form-label">Opnams</label>
                    <input type="text" name="entries[0][opnams]" class="form-control" placeholder="Enter progress" required>
                </div> --}}
            </div>
            <div class="row mb-3">
                <div class="col">
                    <button type="button" class="btn btn-danger remove-entry" style="display: none;">Remove Entry</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <button type="button" class="btn btn-secondary" id="addEntry">Add Another Entry</button>
            <button type="submit" class="btn btn-primary">Submit All Entries</button>
        </div>
    </div>
</form>

@push('scripts')
    @include('contractor.opnam.script')
@endpush

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
