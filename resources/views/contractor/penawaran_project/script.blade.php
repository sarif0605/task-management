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

                    return ` <div class="d-flex gap-2 justify-content-start">
                        ${buttons}
                        </div>`;
                },
            },
        ]
    });

    $("#table-penawaran").on("click", ".show-btn", function () {
        const id = $(this).data("id");
        $('.loading').show();
        $.ajax({
            url: `/penawaran_projects/show/${id}`,
            type: "GET",
            success: function(response) {
                $('.loading').hide();
                $("#showModal input[name='pembuat_penawaran']").val(response.pembuat_penawaran);
                if (response.file_pdf) {
                    $("#showModal .pdf-file-name").text(response.file_pdf);
                }
                if (response.file_excel) {
                    $("#showModal .excel-file-name").text(response.file_excel);
                }

                $("#showModal").modal("show");
            },
            error: function(xhr) {
                $('.loading').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch data'
                });
            }
        });
    });

    // Edit Modal Handler
    $("#table-penawaran").on("click", ".edit-btn", function () {
        const id = $(this).data("id");
        $('.loading').show();

        $.ajax({
            url: `/penawaran_projects/edit/${id}`,
            type: "GET",
            success: function(response) {
                $('.loading').hide();
                $("#exampleModal input[name='pembuat_penawaran']").val(response.pembuat_penawaran);
                $("#exampleModal #penawaran_project_id").val(id);
                if (response.file_pdf) {
                    $("#showModal .pdf-file-name").text(response.file_pdf);
                }
                if (response.file_excel) {
                    $("#showModal .excel-file-name").text(response.file_excel);
                }
                $("#updateForm").attr('action', `/penawaran_projects/update/${id}`);
                $("#exampleModal").modal("show");
            },
            error: function(xhr) {
                $('.loading').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch data'
                });
            }
        });
    });

    // Update Form Handler
    $("#updateForm").on("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('.loading').show();
            },
            success: function(response) {
                $('.loading').hide();
                $("#exampleModal").modal("hide");

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Data updated successfully',
                    timer: 1500
                });

                table.ajax.reload();
            },
            error: function(xhr) {
                $('.loading').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to update data'
                });
            }
        });
    });

    // Delete Handler
    $("#table-penawaran").on("click", ".delete-btn", function () {
        const id = $(this).data("id");

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/penawaran_projects/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        Swal.fire(
                            'Deleted!',
                            'Record has been deleted.',
                            'success'
                        );
                        table.ajax.reload();
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Failed to delete record.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Modal cleanup
    $('.modal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
        $('.loading').hide();
    });
});

// File download function
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
    </script>
