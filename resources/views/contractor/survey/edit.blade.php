@extends('layouts.contractor')

@section('content')
    <hr />
    @include('components.loading')

    <div class="container bg-white p-4 rounded shadow-lg">
        <div class="card-header bg-primary text-white mb-6">
            <h4 class="mb-0">Edit Survey</h4>
        </div>
        <form id="survey-form-edit" action="{{ route('surveys.update', $survey->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Hidden Survey ID -->
            <input type="hidden" name="survey_id" id="survey_id">

            <!-- Input Tanggal Survey -->
            <div class="mb-4">
                <label for="date" class="form-label">Tanggal Survey</label>
                <input type="date" name="date" class="form-control shadow-sm" value="{{ $survey->date }}">
                @error('date')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input Keterangan -->
            <div class="mb-4">
                <label for="survey_results" class="form-label">Keterangan</label>
                <textarea class="form-control shadow-sm" name="survey_results" placeholder="Hasil Survey">{{ $survey->survey_results }}</textarea>
                @error('survey_results')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Upload Gambar Baru -->
            <div class="mb-4">
                <label for="image" class="form-label">Gambar</label>
                <input type="file" id="imageInput" name="image[]" class="form-control shadow-sm" multiple accept="image/*">
                @error('image')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Preview Gambar Sebelumnya -->
            <div class="mb-4">
                <label class="form-label">Gambar Sebelumnya:</label>
                <div id="existingImages" class="d-flex flex-wrap gap-3">
                    @foreach ($survey->survey_images as $image)
                        <div class="position-relative">
                            <img src="{{ asset('storage/survey/' . $image->image_url) }}" alt="Existing Image" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" data-bs-toggle="tooltip" title="Hapus Gambar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Preview Gambar Baru -->
            <div class="mb-4">
                <label class="form-label">Preview Gambar Baru:</label>
                <div id="imagePreview" class="d-flex flex-wrap gap-3"></div>
            </div>

            <!-- Tombol -->
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-warning w-100 shadow-sm">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('surveys') }}" class="btn btn-primary w-100 shadow-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </form>
    </div>

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("survey-form-edit");
        const loadingOverlay = document.getElementById("loading");
        const submitButton = form.querySelector("button[type='submit']");

        if (form && loadingOverlay && submitButton) {
            form.addEventListener("submit", function () {
                loadingOverlay.style.display = "flex"; // Tampilkan loading overlay
                submitButton.disabled = true; // Nonaktifkan tombol submit
                submitButton.textContent = "Loading..."; // Ubah teks tombol
            });
        } else {
            console.error("Form, loading overlay, atau tombol submit tidak ditemukan!");
        }
    });

    // Script untuk preview gambar baru
    document.getElementById('imageInput').addEventListener('change', function (event) {
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.innerHTML = ''; // Kosongkan preview sebelumnya

        const files = event.target.files; // Ambil file yang dipilih
        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            // Validasi jika file bukan gambar, skip
            if (!file.type.startsWith('image/')) continue;

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail', 'me-2', 'mb-2');
                img.style.maxWidth = '150px';
                img.style.maxHeight = '150px';

                imagePreview.appendChild(img); // Tambahkan gambar ke div preview
            };
            reader.readAsDataURL(file); // Baca file sebagai URL
        }
    });
</script>
@endpush

@endsection
