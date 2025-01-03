<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('data_title', 'CV Bina Nusa Prima')</title>
        <link rel="icon" href="{{ asset('admin/img/bnp-bg.png') }}" type="image/png">
        <link href="{{asset('admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
        <link href="{{asset('admin/css/sb-admin-2.min.css')}}" rel="stylesheet" />
        <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">
            <!-- Sidebar -->
            @include('layouts.partials.sidebar')
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    <!-- Topbar -->
                    @include('layouts.partials.navbar')
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        {{-- <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
                            <hr />
                        </div> --}}

                        @yield('content')
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                @include('layouts.partials.footer')
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <script src="{{asset('admin/vendor/jquery/jquery.min.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
        <script src="{{asset('admin/js/sb-admin-2.min.js')}}"></script>
        <script src="{{asset('admin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('admin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('admin/js/demo/datatables-demo.js')}}"></script>
        <script src="{{asset('admin/vendor/chart.js/Chart.min.js')}}"></script>
        <script src="{{asset('admin/js/demo/chart-area-demo.js')}}"></script>
        <script src="{{asset('admin/js/demo/chart-pie-demo.js')}}"></script>
        <script src="{{asset('admin/js/demo/chart-bar-demo.js')}}"></script>
        @yield('js')
        @stack('js')
    </body>
</html>
