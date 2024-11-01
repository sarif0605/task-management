<script type="text/javascript">
    $(document).ready(function () {
        $("#table-opnam").DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('opnams') }}",
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
                { data: "date" },
                { data: "pekerjaan",
                render: function (data, type, row) {
                    return data.length > 15 ? data.substring(0, 15) + "..." : data;
                }
                 },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        return `
                                <a href="/opnams/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>
                                <a href="/opnams/edit/${data.id}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                <button class="btn btn-danger delete-btn" data-id="${data.id}"><i class="fa-solid fa-trash-arrow-up"></i></button>
                            `;
                    },
                },
            ],
        });
        $("#table-opnam").on("click", ".delete-btn", function () {
            const surveyId = $(this).data("id");
            deleteSurvey(surveyId);
        });
    });

    const createData = () => {
        $("#loading").show();
        $.ajax({
            url: $("#opnam-form").attr("action"),
            type: "POST",
            data: $("#opnam-form").serialize(),
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
                    $("#table-opnam").DataTable().ajax.reload();
                    $("#opnam-form")[0].reset();
                    window.location.href = "/opnams";
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
            url: $("#opnam-form-edit").attr("action"),
            type: "POST",
            data: $("#opnam-form-edit").serialize(),
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
                    $("#table-opnam").DataTable().ajax.reload();
                    $("#opnam-form-edit")[0].reset();
                    window.location.href = "/opnams";
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
                    url: `/opnams/destroy/${surveyId}`,
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
                        $("#table-opnam").DataTable().ajax.reload();
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
        handleFormSubmit("#opnam-form", createData);
        handleFormSubmit("#opnam-form-edit", updateProspectData);
    });

    </script>
