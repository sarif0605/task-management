@extends('layouts.contractor')
@section('title', 'Create Project Deal')
@section('content')
<hr />
@include('components.loading')
<form id="deal-form" action="{{ route('deal_projects.store') }}" method="POST" enctype="multipart/form-data">
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
                    <option value="{{ $user->id }}">{{ $user->position }} - {{ $user->profile->name ?? 'N/A' }}</option>
                @endforeach
            </select>
        </div>
        </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
    @push('scripts')
        @include('contractor.done_deal.script')
    @endpush
@endsection
