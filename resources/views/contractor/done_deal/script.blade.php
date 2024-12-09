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
                    render: (data) => data ? `<a href="/storage/rab/${data}" target="_blank">Download</a>` : "RAB Tidak Ditemukan",
                },
                {
                    data: "rap",
                    render: (data) => data ? `<a href="/storage/rap/${data}" target="_blank">Download</a>` : "RAP Tidak Ditemukan",
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
                        <a href="/deal_projects/show/${data.id}" class="btn btn-sm btn-secondary"><i class="fas fa-info-circle"></i></a>
                                <a href="/deal_projects/edit/${data.id}" class="btn btn-sm btn-warning"><i class="fas fa-pen"></i></a>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            </div>`;
                        } else if (userPosition.includes("Sales")) {
                            return ` <div class="d-flex gap-2 justify-content-start">
                                <a href="/deal_projects/show/${data.id}" class="btn btn-sm btn-secondary"><i class="fas fa-info-circle"></i></a>
                                <a href="/deal_projects/edit/${data.id}" class="btn btn-sm btn-warning"><i class="fas fa-pen"></i></a> </div>
                            `;
                        } else {
                            return `
                               <div class="d-flex gap-2 justify-content-start">
                                <a href="/deal_projects/show/${data.id}" class="btn btn-sm btn-secondary"><i class="fas fa-info-circle"></i></a>
                                </div>
                            `;
                        }
                    },
                },
            ],
        });
        $("#table-deal").on("click", ".delete-btn", function () {
        const surveyId = $(this).data("id");
        deleteSurvey(surveyId); // Fungsi untuk menghapus survei
    });
    });

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
    </script>
