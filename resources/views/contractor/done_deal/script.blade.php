<script type="text/javascript">
var userPosition = "{{ Auth::user()->position->pluck('name')->join(', ') }}";
    $(document).ready(function () {
        $("#table-deal").DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('deal_projects') }}",
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
                { data: "date" },
                { data: "harga_deal" },
                { data: "keterangan",
                render: function (data, type, row) {
                    return data.length > 15 ? data.substring(0, 15) + "..." : data;
                }
                 },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        if(userPosition === 'Sales' || userPosition === 'Admin'){
                            return `
                                <a href="/deal_projects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>
                                <a href="/deal_projects/edit/${data.id}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                <button class="btn btn-danger delete-btn" data-id="${data.id}"><i class="fa-solid fa-trash-arrow-up"></i></button>
                            `;
                        } else {
                            return `
                                <a href="/deal_projects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>`;
                        }

                    },
                },
            ],
        });
        $("#table-deal").on("click", ".delete-btn", function () {
            const surveyId = $(this).data("id");
            deleteSurvey(surveyId);
        });
    });

    const createData = () => {
        $("#loading").show();
        $.ajax({
            url: $("#deal-form").attr("action"),
            type: "POST",
            data: $("#deal-form").serialize(),
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
                    $("#table-deal").DataTable().ajax.reload();
                    $("#deal-form")[0].reset();
                    window.location.href = "/deal_projects";
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
            url: $("#deal-form-edit").attr("action"),
            type: "POST",
            data: $("#deal-form-edit").serialize(),
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
                    $("#table-deal").DataTable().ajax.reload();
                    $("#deal-form-edit")[0].reset();
                    window.location.href = "/deal_projects";
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
                    url: `/deal_projects/destroy/${surveyId}`,
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
                        $("#table-deal").DataTable().ajax.reload();
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
        handleFormSubmit("#deal-form", createData);
        handleFormSubmit("#deal-form-edit", updateProspectData);
    });

    </script>
