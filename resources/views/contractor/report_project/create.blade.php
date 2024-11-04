@extends('layouts.contractor')
@section('title', 'CreateProject Deal')
@section('content')
<div class="container">
    <form action="{{ route('deal_projects.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <select name="prospect_id" class="form-control" required>
                    <option value="" disabled selected>Nama Produk</option>
                    @foreach ($prospect as $prospect)
                        <option value="{{ $prospect->id }}">{{ $prospect->nama_produk }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="date" name="date" class="form-control" placeholder="Tanggal">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <input type="number" type="number" name="nominal" step="0.01" class="form-control" placeholder="Nominal">
            </div>
            <div class="col">
                <input type="number" type="number" name="price_quotation" step="0.01" class="form-control" placeholder="Price Quotation">
            </div>
        </div>

        {{-- <div>
            <label for="nominal">Nominal:</label>
            <input  value="{{ old('nominal') }}" required>
            @error('nominal')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div> --}}

        <!-- Operational Project Fields -->

        <div class="row mb-3">
            <div class="col">
                <textarea class="form-control" name="keterangan" placeholder="Keterangan"></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <select name="users[]" multiple class="form-control" required>
                    <option value="" disabled selected>Nama User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->position }}</option>
                    @endforeach
                </select>
            </div>
            </div>

        <button type="submit">Submit</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('deal-projects-container');
    const addButton = document.getElementById('add-more');
    addButton.addEventListener('click', function() {
        const forms = container.getElementsByClassName('deal-project-form');
        const firstForm = forms[0];
        const newForm = firstForm.cloneNode(true);
        newForm.querySelectorAll('input').forEach(input => {
            input.value = '';
        });
        newForm.querySelectorAll('select').forEach(select => {
            select.selectedIndex = 0;
        });
        newForm.querySelector('.remove-form').style.display = 'block';
        container.appendChild(newForm);
        if (forms.length === 1) {
            firstForm.querySelector('.remove-form').style.display = 'block';
        }
    });
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-form')) {
            const forms = container.getElementsByClassName('deal-project-form');
            if (forms.length > 1) {
                e.target.closest('.deal-project-form').remove();
                if (forms.length === 2) {
                    forms[0].querySelector('.remove-form').style.display = 'none';
                }
            }
        }
    });
});
</script>
@endsection
