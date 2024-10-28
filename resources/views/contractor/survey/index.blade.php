@extends('layouts.admin')

@section('title', 'Home Surveys')

@section('content')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Survey</h1>
        <a href="{{ route('surveys.create') }}" class="btn btn-primary">Add</a>
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
                <th>Nama Produk</th>
                <th>Tanggal</th>
                <th>Hasil Survey</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>+
            @if($survey->count() > 0)
                @foreach($survey as $rs)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $rs->prospect->name }}</td>
                        <td class="align-middle">{{ $rs->date }}</td>
                        <td class="align-middle">{{ $rs->survey_results }}</td>
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('surveys.show', $rs->id) }}" type="button" class="btn btn-secondary">Detail</a>
                                <a href="{{ route('surveys.edit', $rs->id)}}" type="button" class="btn btn-warning">Edit</a>
                                <form action="{{ route('surveys.destroy', $rs->id) }}" method="POST" type="button" class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
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
                    <td class="text-center" colspan="5">Surveys not found</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
