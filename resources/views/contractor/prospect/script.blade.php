<script type="text/javascript">
var userPosition = "{{ Auth::user()->position->pluck('name')->join(', ') }}";
$(document).ready(function () {
    $("#table-prospect").DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ route('prospects') }}",
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
            { data: "nama_produk" },
            { data: "tanggal" },
            { data: "pemilik" },
            { data: "lokasi",
            render: function (data, type, row) {
                return data.length > 15 ? data.substring(0, 15) + "..." : data;
            }
             },
            { data: "keterangan",
                render: function (data, type, row) {
                    return data.length > 15 ? data.substring(0, 15) + "..." : data;
                }
            },
            {
                data : 'status'
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <button class="btn btn-info survey-btn" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#surveyModal">
                            <i class="fa-solid fa-plus-circle"></i>
                        </button>
                    `;
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <button class="btn btn-info penawaran-btn" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#penawaranModal">
                            <i class="fa-solid fa-plus-circle"></i>
                        </button>
                    `;
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <button class="btn btn-info deal-btn" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#dealModal">
                            <i class="fa-solid fa-plus-circle"></i>
                        </button>
                    `;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    if (userPosition === "Marketing" || userPosition === "Admin") {
                        return `
                            <a href="/prospects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>
                            <a href="/prospects/edit/${data.id}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button class="btn btn-danger delete-btn" data-id="${data.id}" data-nama-produk="${data.nama_produk}"><i class="fa-solid fa-trash-arrow-up"></i></button>
                        `;
                    } else {
                        return `<a href="/prospects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>`;
                    };
                },
            },
        ],
    });

    $("#table-prospect").on("click", ".delete-btn", function () {
        const prospectId = $(this).data("id");
        const namaProduk = $(this).data("nama-produk");
        deleteProspect(prospectId, namaProduk);
    });
});

const createData = () => {
    $("#loading").show();
    $.ajax({
        url: $("#prospect-form").attr("action"),
        type: "POST",
        data: $("#prospect-form").serialize(),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $("#loading").hide();
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Berhasil Menyimpan Data",
                showConfirmButton: false,
                timer: 1500,
            }).then(() => {
                $("#table-prospect").DataTable().ajax.reload();
                $("#prospect-form")[0].reset();
                window.location.href = "/prospects";
            });
        },
        error: function (xhr) {
            $("#loading").hide();
                const errors = xhr.responseJSON?.errors;
                console.error("Submission errors:", errors);
                let errorMessage = '';
                if (typeof errors === 'object') {
                    for (let field in errors) {
                        errorMessage += `${field}: ${errors[field].join(', ')}\n`;
                    }
                } else {
                    errorMessage = 'Terjadi kesalahan saat menyimpan data';
                }
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage
                });
        },
    });
};

const updateProspectData = () => {
    $("#loading").show();
    $.ajax({
        url: $("#prospect-form-edit").attr("action"),
        type: "POST",
        data: $("#prospect-form-edit").serialize(),
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
                $("#table-prospect").DataTable().ajax.reload();
                $("#prospect-form-edit")[0].reset();
                window.location.href = "/prospects";
            });
        },
        error: function (xhr) {
            $("#loading").hide();
                const errors = xhr.responseJSON?.errors;
                console.error("Submission errors:", errors);
                let errorMessage = '';
                if (typeof errors === 'object') {
                    for (let field in errors) {
                        errorMessage += `${field}: ${errors[field].join(', ')}\n`;
                    }
                } else {
                    errorMessage = 'Terjadi kesalahan saat menyimpan data';
                }
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage
                });
        },
    });
}

function deleteProspect(prospectId, namaProduk) {
    Swal.fire({
        title: "Apakah Anda Yakin?",
        text: `Anda yakin menghapus data dengan nama produk "${namaProduk}"?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/prospects/destroy/${prospectId}`,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function () {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted!",
                        text: `Prospect dengan nama produk "${namaProduk}" telah dihapus.`,
                        timer: 2000,
                    });
                    $("#table-prospect").DataTable().ajax.reload(); // Reload DataTable
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

$("#table-prospect").on("click", ".survey-btn", function () {
    const prospectId = $(this).data("id");
    console.log("Prospect ID:", prospectId);
    $("#prospect_id").val(prospectId);
    var surveyModal = new bootstrap.Modal(document.getElementById('surveyModal'));
    surveyModal.show();
});

const createSurvey = () => {
    const formData = new FormData($("#survey-form")[0]);
    $("#loading").show();
    $.ajax({
        url: $("#survey-form").attr("action"),
        type: "POST",
        data: formData,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        processData: false,
        contentType: false,
        cache: false,
        success: function (response) {
            $("#loading").hide();
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Data berhasil diperbarui",
                showConfirmButton: false,
                timer: 1500,
            }).then(() => {
                $("#table-prospect").DataTable().ajax.reload();
                $("#survey-form")[0].reset();
                $("#surveyModal").modal('hide');
                window.location.href = "/prospects";
            });
        },
        error: function (xhr) {
            $("#loading").hide();
                const errors = xhr.responseJSON?.errors;
                console.error("Submission errors:", errors);
                let errorMessage = '';
                if (typeof errors === 'object') {
                    for (let field in errors) {
                        errorMessage += `${field}: ${errors[field].join(', ')}\n`;
                    }
                } else {
                    errorMessage = 'Terjadi kesalahan saat menyimpan data';
                }
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage
                });
        },
    });
}

// penawaran project
$(document).ready(function () {
    let currentProspectId = null;
    $("#table-prospect").on("click", ".penawaran-btn", function () {
        currentProspectId = $(this).data("id");
        console.log("Setting Prospect ID:", currentProspectId);
        $("#prospect_id").val(currentProspectId);
        $("#penawaran-form").find('input[name="prospect_id"]').val(currentProspectId);
        console.log("Verified Prospect ID Value:", $("#prospect_id").val());
        var penawaranModal = new bootstrap.Modal(document.getElementById('penawaranModal'));
        penawaranModal.show();
    });
    const createDeal = () => {
        const formElement = $("#penawaran-form")[0];
        const formData = new FormData(formElement);
        formData.set('prospect_id', currentProspectId);
        console.log("Form Data before submission:");
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        if (!currentProspectId) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Prospect ID is missing. Please try again.",
            });
            return;
        }
        $("#loading").show();
        $.ajax({
            url: $("#penawaran-form").attr("action"),
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            processData: false,
            contentType: false,
            success: function (response) {
                $("#loading").hide();
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data berhasil diperbarui",
                    showConfirmButton: false,
                    timer: 1500,
                }).then(() => {
                    $("#table-prospect").DataTable().ajax.reload();
                    $("#penawaranModal").modal('hide');
                    window.location.href = "/prospects";
                });
            },
            error: function (xhr) {
                $("#loading").hide();
                const errors = xhr.responseJSON?.errors;
                console.error("Submission errors:", errors);
                let errorMessage = '';
                if (typeof errors === 'object') {
                    for (let field in errors) {
                        errorMessage += `${field}: ${errors[field].join(', ')}\n`;
                    }
                } else {
                    errorMessage = 'Terjadi kesalahan saat menyimpan data';
                }
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage
                });
            }
        });
    };
    $("#penawaran-form").on("submit", function (e) {
        e.preventDefault();
        console.log("Form submitted");
        console.log("Current Prospect ID:", currentProspectId);
        console.log("Hidden input value:", $("#prospect_id").val());
        createDeal();
    });
    $('#penawaranModal').on('hidden.bs.modal', function () {
        $("#penawaran-form")[0].reset();
    });
});

$(document).ready(function () {
    let currentProspectId = null;
    $("#table-prospect").on("click", ".deal-btn", function () {
        currentProspectId = $(this).data("id");
        console.log("Setting Prospect ID:", currentProspectId);
        $("#prospect_id").val(currentProspectId);
        $("#deal-form").find('input[name="prospect_id"]').val(currentProspectId);
        console.log("Verified Prospect ID Value:", $("#prospect_id").val());
        var dealModal = new bootstrap.Modal(document.getElementById('dealModal'));
        dealModal.show();
    });
    const createDeal = () => {
        const formElement = $("#deal-form")[0];
        const formData = new FormData(formElement);
        formData.set('prospect_id', currentProspectId);
        console.log("Form Data before submission:");
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        if (!currentProspectId) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Prospect ID is missing. Please try again.",
            });
            return;
        }
        $("#loading").show();
        $.ajax({
            url: $("#deal-form").attr("action"),
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            processData: false,
            contentType: false,
            success: function (response) {
                $("#loading").hide();
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data berhasil diperbarui",
                    showConfirmButton: false,
                    timer: 1500,
                }).then(() => {
                    $("#table-prospect").DataTable().ajax.reload();
                    $("#dealModal").modal('hide');
                    window.location.href = "/prospects";
                });
            },
            error: function (xhr) {
                $("#loading").hide();
                const errors = xhr.responseJSON?.errors;
                console.error("Submission errors:", errors);
                let errorMessage = '';
                if (typeof errors === 'object') {
                    for (let field in errors) {
                        errorMessage += `${field}: ${errors[field].join(', ')}\n`;
                    }
                } else {
                    errorMessage = 'Terjadi kesalahan saat menyimpan data';
                }
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errorMessage
                });
            }
        });
    };
    $("#deal-form").on("submit", function (e) {
        e.preventDefault();
        console.log("Form submitted");
        console.log("Current Prospect ID:", currentProspectId);
        console.log("Hidden input value:", $("#prospect_id").val());
        createDeal();
    });
    $('#dealModal').on('hidden.bs.modal', function () {
        $("#deal-form")[0].reset();
    });
});


$(document).ready(function () {
    function handleFormSubmit(formSelector, submitFunction) {
        $(formSelector).on("submit", function (e) {
            e.preventDefault();
            submitFunction();
        });
    }
    handleFormSubmit("#prospect-form", createData);
    // handleFormSubmit("#penawaran-form", createPenawaran);
    handleFormSubmit("#prospect-form-edit", updateProspectData);
    handleFormSubmit("#survey-form", createSurvey);
    // handleFormSubmit("#deal-form", createDeal);
});
</script>
