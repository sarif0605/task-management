<script type="text/javascript">
var userPosition = "{{ Auth::user()->position->pluck('name')->join(', ') }}";
$(document).ready(function () {
    $("#table-user").DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ route('users') }}",
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
                data: "profile.name",
                render: function(data) {
                    return data ? data : "N/A";
                }
            },
            { data: "positions" },
            { data: "email" },
            { data: "email_verified_at" },
            { data: "status_account" },
            { data: "profile.foto",
                    render: function(data) {
                    return data ? data : "N/A";
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    if (userPosition === "Admin") {
                        return `
                            <a href="/users/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>
                            <a href="/users/edit/${data.id}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button class="btn btn-danger delete-btn" data-id="${data.id}"><i class="fa-solid fa-trash-arrow-up"></i></button>
                        `;
                    } else {
                        return `<a href="/users/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>`;
                    };
                },
            },
        ],
    });
    $("#table-user").on("click", ".delete-btn", function () {
        const prospectId = $(this).data("id");
        deleteProspect(prospectId);
    });
});


const updateUserData = () => {
    $("#loading").show();
    $.ajax({
        url: $("#user-form-edit").attr("action"),
        type: "POST",
        data: $("#user-form-edit").serialize(),
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
                $("#table-user").DataTable().ajax.reload();
                $("#user-form-edit")[0].reset();
                window.location.href = "/users";
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
                url: `/users/destroy/${prospectId}`,
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
    handleFormSubmit("#user-form-edit", updateUserData);
});

</script>
