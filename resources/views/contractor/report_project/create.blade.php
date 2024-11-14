@extends('layouts.contractor')

@section('title', 'Create Project Report')

@section('content')
    <form id="report-form" action="{{ route('report_projects.store') }}" method="POST">
        @csrf
        <input type="hidden" name="deal_project_id" value="{{ $deal_project_id }}">
        <div id="project-entries">
            <div class="project-entry border p-3 mb-3 rounded">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Status</label>
                        <select name="entries[0][status]" class="form-control" required>
                            <option value="" disabled selected>Status</option>
                            <option value="belum">Belum</option>
                            <option value="mulai">Mulai</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="entries[0][start_date]" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">End Date</label>
                        <input type="date" name="entries[0][end_date]" class="form-control" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Bobot</label>
                        <input type="number" name="entries[0][bobot]" step="0.01" class="form-control" placeholder="Enter bobot" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Progress</label>
                        <input type="number" name="entries[0][progress]" class="form-control" placeholder="Enter progress" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Durasi</label>
                        <input type="number" name="entries[0][durasi]" step="0.01" class="form-control" placeholder="Enter durasi" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Harian</label>
                        <input type="number" name="entries[0][harian]" step="0.01" class="form-control" placeholder="Enter harian" required>
                    </div>
                    <div class="col d-flex align-items-end">
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
    @include('contractor.report_project.script')
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
        newEntry.querySelectorAll('input, select').forEach(input => {
            input.name = input.name.replace('[0]', `[${entryCount}]`);
            input.value = '';
        });
        const removeButton = newEntry.querySelector('.remove-entry');
        removeButton.style.display = 'block';
        removeButton.addEventListener('click', function() {
            newEntry.remove();
        });
        entriesContainer.appendChild(newEntry);
    });
});
</script>
@endpush
@endsection
