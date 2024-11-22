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
                data: "file_penawaran_project",
                render: function (data, type, row) {
                    if (data && Array.isArray(data) && data.length > 0) {
                        const imageName = data[0].file; // Ambil file pertama
                        const imageUrl = `/storage/penawaran/${imageName}`;
                        return `
                            <button onclick="downloadFile('${row.id}', 'pdf')" class="btn btn-primary btn-sm">
                                Download
                            </button>`;
                    }
                    return 'No File';
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
    </script>
