<script type="text/javascript">
$(document).ready(function () {
    var userPosition = "{{ Auth::user()->position->pluck('name')->join(', ') }}";
    let currentChart = null;
    const tableReport = $("#table-report").DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ route('report_projects') }}",
            type: "GET",
            dataType: "json",
            data: function(d) {
                d.deal_project_id = $('#deal_project').val();
            },
            dataSrc: function (response) {
                if (response.status === 'error') {
                    console.error('Error:', response.message);
                    return [];
                }
                const progress = response.progress || {};
                const completedPercentage = progress.completedPercentage ?? 0;
                const remainingPercentage = progress.remainingPercentage ?? 100;
                $('#completed-tasks').text(progress.completedTasks ?? 0);
                updateDoughnutChart(completedPercentage, remainingPercentage);

                return response.data || [];
            },
            error: function(xhr, status, error) {
                console.error('Ajax Error:', error);
                alert('Error loading data. Please try again.');
                return [];
            }
        },
        columns: [
            {
                data: null,
                render: (data, type, row, meta) => meta.row + 1
            },
            {
                data: "deal_project.prospect.nama_produk",
                render: (data) => data || "-"
            },
            {
                data: "pekerjaan",
                render: (data) => data || "-"
            },
            {
                data: "status",
                render: (data) => data || "-"
            },
            {
                data: "start_date",
                render: (data) => data || "-"
            },
            {
                data: "end_date",
                render: (data) => data || "-"
            },
            {
                data: "bobot",
                render: (data) => data || "-"
            },
            {
                data: "progress",
                render: (data) => (data || 0) + '%'
            },
            {
                data: "durasi",
                render: (data) => data || "-"
            },
            {
                data: "harian",
                render: (data) => data || "-"
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    if(userPosition === 'Pengawas' || userPosition === 'Admin'){
                        return `
                        <div class="d-flex gap-2 justify-content-start">
                                    <a href="/report_projects/show/${data.id}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                    <button class="btn btn-sm btn-warning edit-btn" data-id="${data.id}">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}" data-nama-produk="${data.nama_produk}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                        `;
                    }
                    return `<a href="/report_projects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>`;
                }
            }
        ]
    });

    $('#deal_project').on('change', function () {
        tableReport.ajax.reload();
    });

    $("#table-report").on("click", ".edit-btn", function () {
        const reportId = $(this).data("id");
        $.get(`/report_projects/edit/${reportId}`, function (data) {
            $("#edit-report-id").val(data.id); // Pastikan `data.id` mengembalikan nilai ID
            $("#status").val(data.status);
            $("#editReportModal").modal("show");
        }).fail(function () {
            Swal.fire("Error", "Failed to load report data", "error");
        });
    });

    // Handle form submission
    $("#edit-report-form").on("submit", function (e) {
        e.preventDefault();
        const reportId = $("#edit-report-id").val(); // Pastikan ini tidak kosong
        const formData = $(this).serialize();
        $.ajax({
            url: `/report_projects/update/${reportId}`,
            type: "PUT",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                Swal.fire("Success", response.message, "success");
                $("#editReportModal").modal("hide");
                $("#table-report").DataTable().ajax.reload(null, false);
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors || { message: "An error occurred" };
                Swal.fire("Error", Object.values(errors).join("\n"), "error");
            },
        });
    });

    $("#table-report").on("click", ".delete-btn", function () {
        const surveyId = $(this).data("id");
        if (confirm('Are you sure you want to delete this item?')) {
            deleteSurvey(surveyId);
        }
    });

    const ctx = document.getElementById("chartPekerjaan").getContext("2d");

    function updateDoughnutChart(completedPercentage, remainingPercentage) {
        if (currentChart) {
            currentChart.data.datasets[0].data = [completedPercentage, remainingPercentage];
            currentChart.data.labels = [
                `Completed (${completedPercentage}%)`,
                `Remaining (${remainingPercentage}%)`
            ];
            currentChart.update(); // Refresh chart
        } else {
            const ctx = document.getElementById("chartPekerjaan").getContext("2d");
            currentChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [
                        `Completed (${completedPercentage}%)`,
                        `Remaining (${remainingPercentage}%)`
                    ],
                    datasets: [{
                        data: [completedPercentage, remainingPercentage],
                        backgroundColor: ['#4e73df', '#1cc88a'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673'],
                        hoverBorderColor: '#fff',
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            console.log(completedPercentage);
        }
    }
});

    const createData = () => {
        $("#loading").show();
        $.ajax({
            url: $("#report-form").attr("action"),
            type: "POST",
            data: $("#report-form").serialize(),
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
                    $("#table-report").DataTable().ajax.reload();
                    $("#report-form")[0].reset();
                    window.location.href = "/report_projects";
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
            url: $("#report-form-edit").attr("action"),
            type: "POST",
            data: $("#report-form-edit").serialize(),
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
                    $("#table-report").DataTable().ajax.reload();
                    $("#report-form-edit")[0].reset();
                    window.location.href = "/report_projects";
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
        title: "Are you sure?",
        text: `You want to delete the entry with ID ${surveyId}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/report_projects/destroy/${surveyId}`,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function () {
                    Swal.fire({
                        icon: "success",
                        title: "Deleted",
                        text: "The entry has been deleted.",
                    });
                    $("#table-report").DataTable().ajax.reload();
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
        handleFormSubmit("#report-form", createData);
        handleFormSubmit("#report-form-edit", updateProspectData);
    });

    </script>
