<script type="text/javascript">
var userPosition = "{{ Auth::user()->position->pluck('name')->join(', ') }}";
$(document).ready(function () {
    $("#table-attendance").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('attendance') }}",
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
            { data: "user.profile.name" },
            {
                data: "in_image",
                render: function (data) {
                    if (data) {
                        return `<img src="/storage/absensi/masuk/${data}" alt="Gambar Masuk" style="width:50px;height:auto;">`;
                    }
                    return "No image";
                }
            },
            { data: "in_time" },
            { data: "in_info" },
            {
                data: "out_image",
                render: function (data) {
                    if (data) {
                        return `<img src="/storage/absensi/keluar/${data}" alt="Gambar Keluar" style="width:50px;height:auto;">`;
                    }
                    return "No image";
                }
            },
            {
                data: "out_time",
                render: function (data) {
                    return data ? data : "N/A";
                }
            },
            {
                data: "out_info",
                render: function (data) {
                    return data ? data : "N/A";
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    if (userPosition === "Marketing" || userPosition === "Admin") {
                        return `
                            <a href="/attendance/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>
                            <a href="/attendance/edit/${data.id}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button class="btn btn-danger delete-btn" data-id="${data.id}" data-nama-produk="${data}"><i class="fa-solid fa-trash-arrow-up"></i></button>
                        `;
                    } else {
                        return `<a href="/prospects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>`;
                    }
                },
            },
        ],
    });

    $("#table-attendance").on("click", ".delete-btn", function () {
        const attendanceId = $(this).data("id");
        const namaProduk = $(this).data("nama-produk");
        deleteAttendance(attendanceId, namaProduk);
    });
});

</script>
