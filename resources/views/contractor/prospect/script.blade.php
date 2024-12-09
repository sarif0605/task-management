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
                {
                    data: "lokasi",
                    render: function (data) {
                        return data ? `<a href="${data}" target="_blank">maps</a>` : '';
                    }
                },
                 {
                    data: "no_telp",
                    render: function (data, type, row) {
                        return `<a href="https://wa.me/+${data}" target="_blank">${data}</a>`;
                    }
                },
                { data: "keterangan",
                    render: function (data, type, row) {
                        return data.length > 15 ? data.substring(0, 15) + "..." : data;
                    }
                },
                // {
                //     data : 'status'
                // },
                {
                    data: null,
                    render: function (data) {
                        const isChecked = data.survey_count > 0 ? "checked" : "";
                        return `<input type="checkbox" ${isChecked} data-id="${data.id}" data-type="survey">`;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        const isChecked = data.penawaran_project_count > 0 ? "checked" : "";
                        return `<input type="checkbox" ${isChecked} data-id="${data.id}" data-type="penawaran">`;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        const isChecked = data.deal_project_count > 0 ? "checked" : "";
                        return `<input type="checkbox" ${isChecked} data-id="${data.id}" data-type="deal">`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        if (userPosition === "Marketing" || userPosition === "Admin") {
                            return `
                                <div class="d-flex gap-2 justify-content-start">
                                    <a href="/prospects/show/${data.id}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                    <a href="/prospects/edit/${data.id}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}" data-nama-produk="${data.nama_produk}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>`;
                        } else {
                            return `<a href="/prospects/show/${data.id}" class="btn btn-secondary"><i class="fas fa-info-circle"></i></a>`;
                        }
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
                        $("#table-prospect").DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        console.log("Error deleting data", xhr);
                        console.log(xhr.responseText); // Tambahkan ini
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

    $(document).on('change', 'input[type="checkbox"]', function () {
        const prospectId = $(this).data('id');
        const type = $(this).data('type');
        if (type === 'survey') {
            if ($(this).is(':checked')) createSurvey(prospectId);
        }
        if (type === 'penawaran') {
            if ($(this).is(':checked')) createPenawaran(prospectId);
        }
        if (type === 'deal') {
            if ($(this).is(':checked')) createDeal(prospectId);
        }
    });

    const createSurvey = (prospectId) => {
        $("#loading").show();
        const formData = new FormData();
        formData.append('prospect_id', prospectId);
        $.ajax({
            url: "{{ route('surveys.store') }}",
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            processData: false,
            contentType: false,
            success: function (response) {
                $("#loading").hide();
                $("#table-prospect").DataTable().ajax.reload(); // Reload tabel
            },
            error: function (xhr) {
                $("#loading").hide();
                const errors = xhr.responseJSON?.errors;
                console.error("Submission errors:", errors);
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                if (errors) {
                    errorMessage = Object.values(errors).map(e => e.join(', ')).join('\n');
                }
            },
        });
    };

    const createPenawaran = (prospectId) => {
        $("#loading").show();
        const formData = new FormData();
        formData.append('prospect_id', prospectId);
        $.ajax({
            url: "{{ route('penawaran_projects.store') }}",
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            processData: false,
            contentType: false,
            success: function (response) {
                $("#loading").hide();
                $("#table-prospect").DataTable().ajax.reload();
            },
            error: function (xhr) {
                $("#loading").hide();
                const errors = xhr.responseJSON?.errors;
                console.error("Submission errors:", errors);
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                if (errors) {
                    errorMessage = Object.values(errors).map(e => e.join(', ')).join('\n');
                }
            },
        });
    };

    const createDeal = (prospectId) => {
        $("#loading").show();
        const formData = new FormData();
        formData.append('prospect_id', prospectId);
        $.ajax({
            url: "{{ route('deal_projects.store') }}",
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            processData: false,
            contentType: false,
            success: function (response) {
                $("#loading").hide();
                $("#table-prospect").DataTable().ajax.reload();
            },
            error: function (xhr) {
                $("#loading").hide();
                const errors = xhr.responseJSON?.errors;
                console.error("Submission errors:", errors);
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                if (errors) {
                    errorMessage = Object.values(errors).map(e => e.join(', ')).join('\n');
                }
            },
        });
    };

    $(document).ready(function () {
        function handleFormSubmit(formSelector, submitFunction) {
            $(formSelector).on("submit", function (e) {
                e.preventDefault();
                submitFunction();
            });
        }
        handleFormSubmit("#survey-form", createSurvey);
    });
    </script>
