<script type="text/javascript">
var userPosition = "{{ Auth::user()->position }}";
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
            } },
            { data: "status" },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    if (userPosition === "marketing" || userPosition === "admin") {
                        return `
                            <a href="/prospects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>
                            <a href="/prospects/edit/${data.id}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button class="btn btn-danger delete-btn" data-id="${data.id}"><i class="fa-solid fa-trash-arrow-up"></i></button>
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
        deleteProspect(prospectId);
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
            const errors =
                xhr.responseJSON?.errors ||
                "There was a problem creating the prospect.";
            Swal.fire({
                icon: "error",
                title: "Error",
                text: errors,
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
            const errors =
                xhr.responseJSON?.errors ||
                "Gagal memperbarui data. Silakan coba lagi.";
            Swal.fire({
                icon: "error",
                title: "Error",
                text: errors,
            });
        },
    });
}

function deleteProspect(prospectId) {
    Swal.fire({
        title: "Apakah Anda Yakin?",
        text: `Anda yakin menghapus data dengan ID ${prospectId}?`,
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
                        text: `Prospect dengan ID ${prospectId} telah dihapus.`,
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

$(document).ready(function () {
    function handleFormSubmit(formSelector, submitFunction) {
        $(formSelector).on("submit", function (e) {
            e.preventDefault();
            submitFunction();
        });
    }
    handleFormSubmit("#prospect-form", createData);
    handleFormSubmit("#prospect-form-edit", updateProspectData);
});

</script>
