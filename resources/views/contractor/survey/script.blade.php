<script type="text/javascript">
var userPosition = "{{ Auth::user()->position->pluck('name')->join(', ') }}";
$(document).ready(function () {
    $("#table-survey").DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ route('surveys') }}",
            type: "GET",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        },
        columns: [
            {
                data: null,
                render: (data, type, row, meta) => {
                    return meta.row + 1;
                },
            },
            {
                data: "prospect.nama_produk",
                render: function (data, type, row) {
                    return data ? data : "N/A";
                }
            },
            {
                data: "prospect.pemilik",
                render: function (data, type, row) {
                    return data ? data : "pemilik";
                }
            },
            {
                data: "prospect.lokasi",
                render: function (data, type, row) {
                    return `<a href="${data}" target="_blank">maps</a>`;
                }
            },
            {
                data: "prospect.no_telp",
                render: function (data, type, row) {
                    return `<a href="https://wa.me/+${data}" target="_blank">${data}</a>`;
                }
            },
            {
                data: "date",
                render: function (data, type, row) {
                    return data ? data : "date";
                }
            },
            {
                data: "survey_results",
                render: function (data, type, row) {
                    if (data && data.length > 15) {
                        return data.substring(0, 15) + "...";
                    }
                    return data ? data : "survey";
                }
            },
            {
                data: "survey_images",
                render: function (data, type, row) {
                    if (data && data.length > 0) {
                        const imageName = data[0].image_url;
                        const imageUrl = `/storage/survey/${imageName}`;
                        return `<img class="img-profile rounded-circle"
                                src="${imageUrl}"
                                alt="Survey Image"
                                style="width:50px;height:50px;">`;
                        }
                    return 'No image';
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    if (userPosition === "Admin") {
                        return ` <div class="d-flex gap-2 justify-content-start">
                        <button type="button" class="btn btn-primary btn-sm show-btn"
                                data-id="${data.id}"
                                data-prospect-id="${data.prospect_id}"
                                data-bs-toggle="modal"
                                data-bs-target="#showModal">
                                <i class="fa-solid fa-circle-info"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm add-btn"
                                data-id="${data.id}"
                                data-prospect-id="${data.prospect_id}"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}">
                                <i class="fa-solid fa-trash-arrow-up"></i>
                            </button>
                            </div>
                        `;
                    } else if (userPosition === "Sales") {
                        return `
                        <div class="d-flex gap-2 justify-content-start">
                        <button type="button" class="btn btn-primary btn-sm add-btn"
                                data-id="${data.id}"
                                data-prospect-id="${data.prospect_id}"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                <i class="fa-solid fa-plus">
                            </button>
                            <button type="button" class="btn btn-primary btn-sm show-btn"
                                data-id="${data.id}"
                                data-prospect-id="${data.prospect_id}"
                                data-bs-toggle="modal"
                                data-bs-target="#showModal">
                                <i class="fa-solid fa-circle-info"></i>
                            </button> </div>`;
                    } else {
                        return `
                            <button type="button" class="btn btn-primary show-btn"
                                data-id="${data.id}"
                                data-prospect-id="${data.prospect_id}"
                                data-bs-toggle="modal"
                                data-bs-target="#showModal">
                                <i class="fa-solid fa-circle-info"></i>
                            </button>
                        `;
                    }
                },
            },
        ],
    });

    // Handle survey delete action
    $("#table-survey").on("click", ".delete-btn", function () {
        const surveyId = $(this).data("id");
        deleteSurvey(surveyId); // Fungsi untuk menghapus survei
    });

    // Handle adding/updating survey
    $("#table-survey").on("click", ".add-btn", function () {
        const surveyId = $(this).data("id");
        const prospectId = $(this).data("prospect-id");
        $("#updateForm").trigger("reset");
        $('.loading').show();
        $.ajax({
            url: `/surveys/edit/${surveyId}`,
            type: "GET",
            success: function(response) {
                $('.loading').hide();
                $("#survey_id").val(surveyId);
                $("#prospect_id").val(prospectId);
                $("input[name='date']").val(response.survey.date ?? "");
                $("textarea[name='survey_results']").val(response.survey.survey_results ?? "");
                let imageContainer = $("#existing-images");
                imageContainer.empty();
                if (response.survey.survey_images && response.survey.survey_images.length > 0) {
                    response.survey.survey_images.forEach(function(image) {
                        imageContainer.append(`
                            <div class="mb-2 position-relative">
                                <img src="/storage/${image.image_url}" class="img-thumbnail" width="150">
                                <button type="button" class="btn btn-danger btn-sm delete-image"
                                    data-image-id="${image.id}"
                                    style="position: absolute; top: 0; right: 0;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `);
                    });
                }
                const updateUrl = `/surveys/update/${surveyId}`;
                $("#updateForm").attr('action', updateUrl);
                $("#exampleModal").modal("show");
            },
            error: function(xhr) {
                $('.loading').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch survey data'
                });
            }
        });
    });

    // Handle show survey details
    $("#table-survey").on("click", ".show-btn", function () {
        const surveyId = $(this).data("id");
        const prospectId = $(this).data("prospect-id");
        $("#showForm").trigger("reset");  // Reset form before showing modal
        $('.loading').show();  // Show loading spinner

        $.ajax({
            url: `/surveys/show/${surveyId}`,
            type: "GET",
            success: function(response) {
                $('.loading').hide();
                $("#survey_id").val(surveyId);
                $("#prospect_id").val(prospectId);
                $("input[name='date']").val(response.survey.date || 'N/A');
                $("textarea[name='survey_results']").val(response.survey.survey_results || 'No survey results');
                let imageContainer = $("#existing-images");
                imageContainer.empty();
                if (response.survey.survey_images && response.survey.survey_images.length > 0) {
                    response.survey.survey_images.forEach(function(image) {
                        imageContainer.append(`
                            <div class="mb-2 position-relative">
                                <img src="/storage/${image.image_url}" class="img-thumbnail" width="150">
                                <button type="button" class="btn btn-danger btn-sm delete-image"
                                    data-image-id="${image.id}"
                                    style="position: absolute; top: 0; right: 0;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `);
                    });
                } else {
                    imageContainer.append('<p>No images available</p>');
                }
                $("#showModal").modal("show");
            },
            error: function(xhr) {
                $('.loading').hide();  // Hide loading spinner
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch survey data'
                });
            }
        });
    });

    // Handle delete image button
    $(document).on('click', '.delete-image', function(e) {
        e.preventDefault();
        const imageId = $(this).data('image-id');
        const imageElement = $(this).parent();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Add image ID to deleted images array
                const deletedImagesInput = $("#deleted_images");
                let deletedImages = deletedImagesInput.val() ?
                    JSON.parse(deletedImagesInput.val()) : [];
                deletedImages.push(imageId);
                deletedImagesInput.val(JSON.stringify(deletedImages));

                // Remove image from display
                imageElement.remove();
            }
        });
    });

    // Handle form submission
    $("#updateForm").on("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('_method', 'PUT');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('.loading').show();
            },
            success: function(response) {
                $('.loading').hide();
                $("#exampleModal").modal("hide");

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Survey updated successfully',
                    timer: 1500
                });

                $("#table-survey").DataTable().ajax.reload();
            },
            error: function(xhr) {
                $('.loading').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to update survey'
                });
            }
        });
    });

    // Clean up modal when hidden
    $('#exampleModal').on('hidden.bs.modal', function () {
        $("#updateForm").trigger("reset");
        $('.loading').hide();
    });
});

    function deleteSurvey(surveyId) {
        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: `Anda yakin menghapus data ini?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/surveys/destroy/${surveyId}`,
                    type: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function () {
                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text: `Survey dengan ID ${surveyId} telah dihapus.`,
                            timer: 2000,
                        });
                        $("#table-survey").DataTable().ajax.reload();
                    },
                    error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Gagal menghapus data. Silakan coba lagi.",
                        });
                    },
                });
            }
        });
    }
    </script>
