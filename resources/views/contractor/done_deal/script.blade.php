<script type="text/javascript">
    $(document).ready(function () {
        const userPosition = "{{ Auth::user()->position->pluck('name')->join(', ') }}";
        const table = $("#table-deal").DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('deal_projects') }}",
                type: "GET",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            },
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1,
                },
                {
                    data: "prospect.nama_produk",
                    render: (data) => data || "N/A",
                },
                {
                    data: "prospect.pemilik",
                    render: (data) => data || "Pemilik Tidak Ditemukan",
                },
                {
                    data: "prospect.lokasi",
                    render: (data) => data ? `<a href="${data}" target="_blank">Maps</a>` : "Lokasi Tidak Ditemukan",
                },
                {
                    data: "prospect.no_telp",
                    render: (data) => data ? `<a href="https://wa.me/+${data}" target="_blank">${data}</a>` : "Telp Tidak Ditemukan",
                },
                {
                    data: "date",
                    render: (data) => data || "Tanggal Tidak Ditemukan",
                },
                {
                    data: "harga_deal",
                    render: (data) => data || "Harga Tidak Ditemukan",
                },
                {
                    data: "rab",
                    render: (data) => data ? `<a href="/storage/${data}" target="_blank">Download</a>` : "RAB Tidak Ditemukan",
                },
                {
                    data: "rap",
                    render: (data) => data ? `<a href="/storage/${data}" target="_blank">Download</a>` : "RAP Tidak Ditemukan",
                },
                {
                    data: "keterangan",
                    render: (data) => data && data.length > 15 ? `${data.substring(0, 15)}...` : data || "Keterangan Tidak Ditemukan",
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) => {
                        if (userPosition.includes("Admin")) {
                            return `
                                <div class="d-flex gap-2 justify-content-start">
                        <a href="/deal_projects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>
                                <a href="/deal_projects/edit/${data.id}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}">
                                <i class="fa-solid fa-trash-arrow-up"></i>
                            </button>
                            </div>`;
                        } else if (userPosition.includes("Sales")) {
                            return `
                                <button class="btn btn-primary show-btn" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#showModal"><i class="fa-solid fa-circle-info"></i></button>
                                <button class="btn btn-warning add-btn" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-plus"></i></button>
                            `;
                        } else {
                            return `
                                <button class="btn btn-primary show-btn" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#showModal"><i class="fa-solid fa-circle-info"></i></button>
                            `;
                        }
                    },
                },
            ],
        });
    });

    const updateDealData = () => {
        $("#loading").show();
        const formData = new FormData($("#deal-form-update")[0]);
        $.ajax({
            url: $("#deal-form-update").attr("action"),
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
                    $("#table-deal").DataTable().ajax.reload();
                    $("#deal-form-update")[0].reset();
                    window.location.href = "/deal_projects";
                });
            },
            error: function (xhr) {
                $("#loading").hide();
                const errors = xhr.responseJSON?.errors;
                console.error("Submission errors:", errors);
                let errorMessage = '';
                if (typeof errors === 'object' && errors !== null) {
                    // Handle nested error objects
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
            text: `Anda yakin menghapus data ini`,
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
        handleFormSubmit("#deal-form-update", updateDealData);
    });
    </script>
