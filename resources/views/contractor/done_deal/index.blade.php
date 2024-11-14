@extends('layouts.contractor')

@section('title', 'Home Deal Project')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-2">
        {{-- @if (Auth::user()->position == 'sales' || Auth::user()->position == 'admin')
            <a href="{{ route('deal_projects.create') }}" class="btn btn-primary"><i class="fa-solid fa-square-plus"></i> Add</a>
        @endif --}}
    </div>
    <hr class="mb-1" />
    <table id="table-deal" class="table table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Tanggal</th>
                <th>Harga Deal</th>
                <th>Keterangan</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    @push('scripts')
        @include('contractor.done_deal.script')
    @endpush
@endsection
