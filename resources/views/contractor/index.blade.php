@extends('layouts.contractor')
@section('title', 'Dashboard - Laravel Admin Panel With Login and Registration')
@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Prospek Count Card -->
        <div class="col-xl-4 col-md-4 mb-4"> <!-- Ubah col-xl-3 ke col-xl-4 -->
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
        <div class="col-xl-4 col-md-4 mb-4"> <!-- Ubah col-xl-3 ke col-xl-4 -->
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
        <div class="col-xl-4 col-md-4 mb-4"> <!-- Ubah col-xl-3 ke col-xl-4 -->
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
</div>
@endsection
