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
                                <button class="btn btn-primary show-btn" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#showModal"><i class="fa-solid fa-circle-info"></i></button>
                                <button class="btn btn-warning add-btn" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-plus"></i></button>
                                <button class="btn btn-danger delete-btn" data-id="${data.id}"><i class="fa-solid fa-trash-arrow-up"></i></button>
                            `;
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

        $("#table-deal").on("click", ".show-btn", function () {
        const id = $(this).data("id");
        $('.loading').show();
        $.ajax({
            url: `/deal_projects/show/${id}`,
            type: "GET",
            success: function (response) {
                $('.loading').hide();
                $("#showModal input[name='date']").val(response.date);
                $("#showModal input[name='harga_deal']").val(response.harga_deal);
                $("#showModal textarea[name='keterangan']").val(response.keterangan);
                $("#showModal .pdf-rab-name").text(response.rab || "Tidak Ada");
                $("#showModal .rap-file-name").text(response.rap || "Tidak Ada");
                $("#deal_project_id").val(id);
                $("#showModal").modal("show");
            },
            error: function () {
                $('.loading').hide();
                Swal.fire("Error", "Failed to fetch data", "error");
            },
        });
    });
    // Update form submission
    $("#updateForm").on("submit", function (e) {
        e.preventDefault();
        const id = $("#deal_project_id").val();
        const formData = new FormData(this);

        $.ajax({
            // Gunakan nama route yang benar sesuai dengan routes list
            url: `/deal_projects/update/${id}`, // URL sudah benar sesuai route list
            type: "POST", // Tetap gunakan POST karena route menerima PUT|POST
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('.loading').show();
            },
            success: function (response) {
                $('.loading').hide();
                $("#exampleModal").modal("hide");
                Swal.fire({
                    title: "Success",
                    text: "Data updated successfully",
                    icon: "success"
                }).then((result) => {
                    if (result.isConfirmed) {
                        table.ajax.reload();
                    }
                });
            },
            error: function (xhr) {
                $('.loading').hide();
                let errorMessage = "Failed to update data";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire("Error", errorMessage, "error");
            }
        });
    });

    // Delete confirmation
    $("#table-deal").on("click", ".delete-btn", function () {
        const id = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/deal_projects/destroy/${id}`, // Correctly target the destroy route
                    type: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function () {
                        Swal.fire("Deleted!", "Record has been deleted.", "success");
                        table.ajax.reload(); // Reload the DataTable to reflect the deletion
                    },
                    error: function () {
                        Swal.fire("Error!", "Failed to delete record.", "error");
                    },
                });
            }
        });
    });

    // Reset modal when hidden
    $(".modal").on("hidden.bs.modal", function () {
        $(this).find("form")[0].reset(); // Reset the form fields
        $('.loading').hide(); // Hide the loading indicator
    });
    });
    </script>

{{-- <script type="text/javascript">
var userPosition = "{{ Auth::user()->position->pluck('name')->join(', ') }}";
$(document).ready(function () {
    var userPosition = "{{ Auth::user()->position->pluck('name')->join(', ') }}";

    // Initialize the DataTable
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
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
            },
            {
                data: "prospect.nama_produk",
                render: function (data) {
                    return data ? data : "N/A";
                },
            },
            {
                data: "prospect.pemilik",
                render: function (data) {
                    return data ? data : "Pemilik Tidak Ditemukan";
                },
            },
            {
                data: "prospect.lokasi",
                render: function (data) {
                    return data ? `<a href="${data}" target="_blank">Maps</a>` : "Lokasi Tidak Ditemukan";
                },
            },
            {
                data: "prospect.no_telp",
                render: function (data) {
                    return data ? `<a href="https://wa.me/+${data}" target="_blank">${data}</a>` : "Telp Tidak Ditemukan";
                },
            },
            {
                data: "date",
                render: function (data) {
                    return data ? data : "Tanggal Tidak Ditemukan";
                },
            },
            {
                data: "harga_deal",
                render: function (data) {
                    return data ? data : "Harga Tidak Ditemukan";
                },
            },
            {
                data: "rab",
                render: function (data) {
                    return data ? `<a href="/storage/${data}" target="_blank">Download</a>` : "RAB Tidak Ditemukan";
                },
            },
            {
                data: "rap",
                render: function (data) {
                    return data ? `<a href="/storage/${data}" target="_blank">Download</a>` : "RAP Tidak Ditemukan";
                },
            },
            {
                data: "keterangan",
                render: function (data) {
                    return data && data.length > 15 ? data.substring(0, 15) + "..." : data || "Keterangan Tidak Ditemukan";
                },
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    if (userPosition === "Admin") {
                        return `
                            <button type="button" class="btn btn-primary show-btn"
                                    data-id="${data.id}"
                                    data-prospect-id="${data.prospect_id}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#showModal">
                                    <i class="fa-solid fa-circle-info"></i>
                                </button>
                                <button type="button" class="btn btn-warning add-btn"
                                    data-id="${data.id}"
                                    data-prospect-id="${data.prospect_id}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                                <button class="btn btn-danger delete-btn" data-id="${data.id}">
                                    <i class="fa-solid fa-trash-arrow-up"></i>
                                </button>
                        `;
                    } else if (userPosition === "Sales") {
                        return `
                            <button type="button" class="btn btn-primary add-btn"
                                    data-id="${data.id}"
                                    data-prospect-id="${data.prospect_id}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    <i class="fa-solid fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-primary show-btn"
                                    data-id="${data.id}"
                                    data-prospect-id="${data.prospect_id}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#showModal">
                                    <i class="fa-solid fa-circle-info"></i>
                            </button>
                        `;
                    } else {
                        return `
                            <button type="button" class="btn btn-primary show-btn"
                                    data-id="${data.id}"
                                    data-prospect-id="${data.prospect_id}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#showModal">
                                    <i class="fa-solid fa-circle-info"></i>
                            </button>
                        `;
                    }
                },
            },
        ],
    });

    // Add click event for 'add-btn' to load survey data into the update modal
    $("#table-deal").on("click", ".show-btn", function () {
        const id = $(this).data("id");
        $('.loading').show();
        $.ajax({
            url: `/deal_projects/show/${id}`,
            type: "GET",
            success: function(response) {
                $('.loading').hide();
                $("#showModal input[name='date']").val(response.date);
                $("#showModal input[name='harga_deal']").val(response.harga_deal);
                $("#showModal input[name='keterangan']").val(response.keterangan);
                if (response.rab) {
                    $("#showModal .pdf-rab-name").text(response.rab);
                }
                if (response.rap) {
                    $("#showModal .rap-file-name").text(response.rap);
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
    $("#table-deal").on("click", ".edit-btn", function () {
        const id = $(this).data("id");
        $('.loading').show();

        $.ajax({
            url: `/deal_projects/edit/${id}`,
            type: "GET",
            success: function(response) {
                $('.loading').hide();
                $("#exampleModal input[name='date']").val(response.date);
                $("#exampleModal #deal_project_id").val(id);
                $("#exampleModal input[name='harga_deal']").val(response.harga_deal);
                $("#exampleModal input[name='keterangan']").val(response.keterangan);
                if (response.rab) {
                    $("#showModal .rab-file-name").text(response.rab);
                }
                if (response.rap) {
                    $("#showModal .rap-file-name").text(response.rap);
                }
                $("#updateForm").attr('action', `/deal_projects/update/${id}`);
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

    $("#updateForm").on("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('_method', 'PUT');
        $.ajax({
        url: $(this).attr('action'),
        type: 'PUT', // Correct the method to PUT
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
    $("#table-deal").on("click", ".delete-btn", function () {
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
    </script> --}}
