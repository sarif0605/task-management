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
                        <a href="/surveys/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>
                                <a href="/surveys/edit/${data.id}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
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
});

    const updateSurveyData = () => {
        $("#loading").show();
        const formData = new FormData($("#survey-form-edit")[0]);
        $.ajax({
            url: $("#survey-form-edit").attr("action"),
            type: "POST",
            data: formData,
            processData: false,  // Important for FormData
            contentType: false,  // Important for FormData
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                $("#loading").hide();
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data berhasil diperbarui",
                    showConfirmButton: false,
                    timer: 1500,
                }).then(() => {
                    $("#table-survey").DataTable().ajax.reload();
                    $("#survey-form-edit")[0].reset();
                    window.location.href = "/surveys";
                });
            },
            error: function (xhr) {
                $("#loading").hide();
                const errors = xhr.responseJSON?.errors;
                let errorMessage = '';
                if (typeof errors === 'object' && errors !== null) {
                    const formatErrors = (obj, prefix = '') => {
                        let message = '';
                        for (let key in obj) {
                            if (Array.isArray(obj[key])) {
                                message += `${prefix}${key}: ${obj[key].join(', ')}\n`;
                            } else if (typeof obj[key] === 'object' && obj[key] !== null) {
                                message += formatErrors(obj[key], `${prefix}${key}.`);
                            } else {
                                message += `${prefix}${key}: ${obj[key]}\n`;
                            }
                        }
                        return message;
                    };
                    errorMessage = formatErrors(errors);
                } else {
                    errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data';
                }
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage.trim(),
                });
            },
        });
    };

    function deleteSurvey(surveyId) {
        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: `Anda yakin menghapus data dengan ID ${surveyId}?`,
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

    $(document).ready(function () {
        function handleFormSubmit(formSelector, submitFunction) {
            $(formSelector).on("submit", function (e) {
                e.preventDefault();
                submitFunction();
            });
        }
        handleFormSubmit("#survey-form", createData);
        handleFormSubmit("#survey-form-edit", updateSurveyData);
    });
    </script>
