<script type="text/javascript">
    var userPosition = "{{ Auth::user()->position }}";
    $(document).ready(function () {
    $("#table-report").DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ route('report_projects') }}",
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
                data: "deal_project.prospect.nama_produk",
                render: function (data) {
                    return data ? data : "N/A";
                }
            },
            { data: "pekerjaan",
                render: function (data) {
                    return data ? data : "N/A";
                }
             },
            { data: "status",
                render: function (data) {
                    return data ? data : "N/A";
                }
             },
            { data: "start_date",
            render: function (data) {
                    return data ? data : "N/A";
                }
             },
            { data: "end_date",
            render: function (data) {
                    return data ? data : "N/A";
                }
             },
            { data: "bobot",
            render: function (data) {
                    return data ? data : "N/A";
                }
             },
            { data: "progress",
            render: function (data) {
                    return data ? data : "N/A";
                }
             },
            { data: "durasi",
            render: function (data) {
                    return data ? data : "N/A";
                }
             },
            { data: "harian",
            render: function (data) {
                    return data ? data : "N/A";
                }
             },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    if(userPosition === 'sales' || userPosition === 'admin'){
                        return `
                            <a href="/report_projects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>
                            <a href="/report_projects/edit/${data.id}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button class="btn btn-danger delete-btn" data-id="${data.id}"><i class="fa-solid fa-trash-arrow-up"></i></button>
                        `;
                    } else {
                        return `
                            <a href="/report_projects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>`;
                    }
                },
            },
        ],
    });

    $("#table-report").on("click", ".delete-btn", function () {
        const surveyId = $(this).data("id");
        deleteSurvey(surveyId);
    });
});

    const createData = () => {
        $("#loading").show();
        $.ajax({
            url: $("#report-form").attr("action"),
            type: "POST",
            data: $("#report-form").serialize(),
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
                    $("#table-report").DataTable().ajax.reload();
                    $("#report-form")[0].reset();
                    window.location.href = "/report_projects";
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
            url: $("#report-form-edit").attr("action"),
            type: "POST",
            data: $("#report-form-edit").serialize(),
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
                    $("#table-report").DataTable().ajax.reload();
                    $("#report-form-edit")[0].reset();
                    window.location.href = "/report_projects";
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

    function deleteSurvey(surveyId) {
    Swal.fire({
        title: "Are you sure?",
        text: `You want to delete the entry with ID ${surveyId}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/report_projects/destroy/${surveyId}`,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function () {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted",
                        text: "The entry has been deleted.",
                    });
                    $("#table-report").DataTable().ajax.reload();
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
        handleFormSubmit("#report-form", createData);
        handleFormSubmit("#report-form-edit", updateProspectData);
    });

    </script>
