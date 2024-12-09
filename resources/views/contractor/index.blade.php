@extends('layouts.contractor')
@section('title', 'Dashboard - Prospect Tracking')
@section('content')
<div class="container-fluid">
    <!-- Stats Cards Row -->
    <div class="row">
        <!-- Prospek This Month Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Prospek Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countProspek }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-3x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Survey This Month Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Survey Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countSurvey }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-3x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penawaran This Month Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Penawaran Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countPenawaran }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gift fa-3x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deal This Month Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Deal Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countDeal }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-3x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Bar Chart Paroyek Tahunan</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar">
                    <canvas id="chartProyek"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById("chartProyek").getContext("2d");
        const myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!}, // Nama bulan
                datasets: [
                    {
                        label: "Prospek",
                        backgroundColor: "rgba(78, 115, 223, 1)",
                        data: {!! json_encode($dataProspek) !!}, // Data Prospek
                    },
                    {
                        label: "Survey",
                        backgroundColor: "rgba(28, 200, 138, 1)",
                        data: {!! json_encode($dataSurvey) !!}, // Data Survey
                    },
                    {
                        label: "Penawaran",
                        backgroundColor: "rgba(246, 194, 62, 1)",
                        data: {!! json_encode($dataPenawaran) !!}, // Data Penawaran
                    },
                    {
                        label: "Deal",
                        backgroundColor: "rgba(231, 74, 59, 1)",
                        data: {!! json_encode($dataDeal) !!}, // Data Deal
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                    },
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    });
</script>
@endsection
