@extends('layouts.contractor')
@section('title', 'Create Absensi')
@section('content')
@include('components.loading')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span>Keluar</span>
                <span id="clock"></span>
            </div>
            <div class="card-body">
                <div id="my_camera" class="m-auto"></div>
                <center>
                    <div id="result" class="my-3"></div>
                </center>
                <div class="d-grid my-3">
                    <button class="btn btn-primary" id="btn-absen" onclick="take_snapshot()">Ambil Foto</button>
                </div>
                <form action="{{ route('attendance.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <input type="hidden" class="image-tag" name="image">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <img src="" alt="">
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script>
    Webcam.set({
        width: 240,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
        });
    Webcam.attach('my_camera');
    function take_snapshot(){
        Webcam.snap(function(data_url) {
            // memasukan url ke inputan
            $(".image-tag").val(data_url);
            // menampilkan gambar
            document.getElementById('result').innerHTML = '<img class="img-fluid" src="' + data_url +'" alt="Gambar">'
        })
    }
</script>
<script>
    let myTimer = () => {
        let date = new Date();
        document.getElementById('clock').innerHTML = date.toLocaleTimeString();
    }
    let myTime = setInterval(() => {
        myTimer();
    }, 1000);
</script>
@endpush
