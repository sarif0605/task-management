@extends('layouts.admin')

@section('title', 'Home Product')

@section('content')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Prospect</h1>
        <a href="{{ route('prospects.create') }}" class="btn btn-primary">Add Prospect</a>
    </div>
    <hr />
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <table class="table table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Pemilik</th>
                <th>Lokasi</th>
                <th>Keterangan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>+
            @if($prospect->count() > 0)
                @foreach($prospects as $rs)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $rs->name }}</td>
                        <td class="align-middle">{{ $rs->tanggal }}</td>
                        <td class="align-middle">{{ $rs->pemilik }}</td>
                        <td class="align-middle">{{ $rs->lokasi }}</td>
                        <td class="align-middle">{{ $rs->keterangan }}</td>
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('prospects.show', $rs->id) }}" type="button" class="btn btn-secondary">Detail</a>
                                <a href="{{ route('prospects.edit', $rs->id)}}" type="button" class="btn btn-warning">Edit</a>
                                <form action="{{ route('prospects.destroy', $rs->id) }}" method="POST" type="button" class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger m-0">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="5">Prospect not found</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
