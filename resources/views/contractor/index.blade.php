@extends('layouts.contractor')
@section('title', 'Dashboard - Laravel Admin Panel With Login and Registration')
@section('js')
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}

    <script>
    $(document).ready(function() {
        // Handle form submission
        $('#yearForm').submit(function(e) {
            e.preventDefault();
            var year = $('#yearInput').val();

            // Validate year
            if(year < 1900 || year > 2100) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Year',
                    text: 'Please enter a valid year between 1900 and 2100'
                });
                return false;
            }

            // Redirect with year parameter
            window.location.href = "{{ route('dashboard') }}?year=" + year;
        });

        // Initialize year input with current value
        $('#yearInput').val("{{ $selectedYear }}");
    });
    </script>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Year Input Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form id="yearForm" class="form-inline">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Select Year</span>
                            </div>
                            <input type="number"
                                   id="yearInput"
                                   class="form-control"
                                   min="1900"
                                   max="2100"
                                   placeholder="Enter year..."
                                   required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> View Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row">
        <!-- Prospek Count Card -->
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Prospek This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countProspek }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Survey Count Card -->
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Survey This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countSurvey }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deal Count Card -->
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Deal This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countDeal }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Prospect Analysis {{ $selectedYear }}</h6>
                </div>
                <div class="card-body">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @extends('layouts.contractor')
@section('title', 'Dashboard - Laravel Admin Panel With Login and Registration')
@section('js')
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endsection
@section('content')
<div class="container-fluid">
    <!-- Year Selector -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form action="{{ route('dashboard') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <select name="year" class="form-control" onchange="this.form.submit()">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Prospek Count Card -->
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Prospek This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countProspek }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Survey Count Card -->
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Survey This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countSurvey }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deal Count Card -->
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Deal This Month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countDeal }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
