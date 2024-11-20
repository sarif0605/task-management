<script type="text/javascript">
    $(document).ready(function () {
    var userPosition = {!! json_encode(Auth::user()->position->pluck('name')->join(', ')) !!} || 'Unknown';

    // Initialize DataTable
    var table = $("#table-penawaran").DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ route('penawaran_projects') }}",
            type: "GET",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        },
        columns: [
            {
                data: null,
                render: (data, type, row, meta) => meta.row + 1
            },
            {
                data: "prospect.nama_produk",
                render: function (data) {
                    return data || "N/A";
                }
            },
            {
                data: "prospect.pemilik",
                render: function (data) {
                    return data || "pemilik";
                }
            },
            {
                data: "prospect.lokasi",
                render: function (data) {
                    return `<a href="${data}" target="_blank">maps</a>`;
                }
            },
            {
                data: "prospect.no_telp",
                render: function (data) {
                    return `<a href="https://wa.me/+${data}" target="_blank">${data}</a>`;
                }
            },
            {
                data: "pembuat_penawaran",
                render: function (data) {
                    return data || "Unknown";
                }
            },
            {
                data: "file_pdf",
                orderable: false,
                render: function (data, type, row) {
                    return data
                        ? `<button onclick="downloadFile('${row.id}', 'pdf')" class="btn btn-primary btn-sm">Download</button>`
                        : "No PDF";
                },
            },
            {
                data: "file_excel",
                orderable: false,
                render: function (data, type, row) {
                    return data
                        ? `<button onclick="downloadFile('${row.id}', 'excel')" class="btn btn-primary btn-sm">Download</button>`
                        : "No Excel";
                },
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    let buttons = '';
                    buttons += `
                        <button type="button" class="btn btn-primary btn-sm show-btn" data-id="${data.id}">
                            <i class="fa-solid fa-circle-info"></i>
                        </button>
                    `;
                    if (userPosition === "Admin" || userPosition === "Sales") {
                        buttons += `
                            <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="${data.id}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                        `;
                    }
                    if (userPosition === "Admin") {
                        buttons += `
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        `;
                    }

                    return `<div class="d-flex gap-2 justify-content-start">
                        <a href="/penawaran_projects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>
                                <a href="/penawaran_projects/edit/${data.id}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}">
                                <i class="fa-solid fa-trash-arrow-up"></i>
                            </button>
                            </div>`;
                },
            },
        ]
    });

    $("#table-penawaran").on("click", ".delete-btn", function () {
        const surveyId = $(this).data("id");
        deleteSurvey(surveyId); // Fungsi untuk menghapus survei
    });
});

// Fungsi download file
function downloadFile(id, type) {
    $.ajax({
        url: `/penawaran_projects/download/${id}/${type}`, // URL endpoint Laravel
        type: 'POST',
        headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        xhrFields: {
            responseType: 'blob' // Terima file dalam format blob
        },
        success: function(response, status, xhr) {
            // Ambil nama file dari header Content-Disposition
            const contentDisposition = xhr.getResponseHeader('Content-Disposition');
            const fileName = contentDisposition
                ? contentDisposition.split('filename=')[1]?.replace(/"/g, '') // Ambil nama file dari header
                : `${type}_${id}.pdf`; // Nama default jika header tidak ada

            // Buat objek blob
            const blob = new Blob([response], { type: 'application/pdf' }); // Sesuaikan tipe MIME untuk Excel jika perlu

            // Buat elemen link untuk mendownload file
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = fileName;
            link.click();

            // Bersihkan URL objek untuk menghindari kebocoran memori
            window.URL.revokeObjectURL(link.href);
        },
        error: function(xhr) {
            // Tangani error
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to download file. Please try again.'
            });
        }
    });
}
    const updatePenawaranData = () => {
        $("#loading").show();
        const formData = new FormData($("#penawaran-form-edit")[0]);
        $.ajax({
            url: $("#penawaran-form-edit").attr("action"),
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
                    $("#table-penawaran").DataTable().ajax.reload();
                    $("#penawaran-form-edit")[0].reset();
                    window.location.href = "/penawaran_projects";
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
                    url: `/penawaran_projects/destroy/${surveyId}`,
                    type: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function () {
                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text: `Penawaran Project dengan ID ${surveyId} telah dihapus.`,
                            timer: 2000,
                        });
                        $("#table-penawaran").DataTable().ajax.reload();
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
        handleFormSubmit("#penawaran-form-edit", updatePenawaranData);
    });
    </script>
