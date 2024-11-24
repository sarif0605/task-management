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
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `
                        <div class="d-flex gap-2 justify-content-start">
                            <button class="btn btn-primary btn-sm" onclick="downloadAllFiles('${data.id}')">
                                <i class="fa-solid fa-download"></i> Download All
                            </button>
                        </div>`;
                }
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
function downloadAllFiles(id) {
    $.ajax({
        url: `/penawaran_projects/download-all/${id}`, // URL endpoint Laravel
        type: 'POST',
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        xhrFields: {
            responseType: 'blob' // Terima file dalam format blob
        },
        success: function(response, status, xhr) {
            const contentDisposition = xhr.getResponseHeader('Content-Disposition');
            const fileName = contentDisposition
                ? contentDisposition.split('filename=')[1]?.replace(/"/g, '')
                : `penawaran_files.zip`;
            const blob = new Blob([response], { type: 'application/zip' });
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = fileName;
            link.click();
            window.URL.revokeObjectURL(link.href);
        },
        error: function(xhr) {
            // Tangani error
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to download files. Please try again.'
            });
        }
    });
}

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
                            text: `Penawaran Project telah dihapus.`,
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
    </script>
