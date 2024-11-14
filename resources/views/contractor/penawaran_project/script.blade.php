<script type="text/javascript">
$(document).ready(function () {
    var userPosition = {!! json_encode(Auth::user()->position->pluck('name')->join(', ')) !!} || 'Unknown';
    $("#table-penawaran").DataTable({
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
                render: (data, type, row, meta) => {
                    return meta.row + 1;
                },
            },
            {
                data: "prospect.nama_produk",
                render: function (data) {
                    return data ? data : "N/A";
                }
            },
            { data: "pembuat_penawaran" },
            {
                data: "file_pdf",
                orderable: false,
                render: function (data, type, row) {
                    return data
                        ? `<button onclick="downloadFile(${row.id}, 'pdf')" class="btn btn-primary">Download PDF</button>`
                        : "N/A";
                },
            },
            {
                data: "file_excel",
                orderable: false,
                render: function (data, type, row) {
                    return data
                        ? `<button onclick="downloadFile(${row.id}, 'excel')" class="btn btn-primary">Download Excel</button>`
                        : "N/A";
                },
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    if (userPosition === 'Sales' || userPosition === 'Admin') {
                        return `
                            <a href="/penawaran_projects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>
                            <a href="/penawaran_projects/edit/${data.id}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                            <button class="btn btn-danger delete-btn" data-id="${data.id}"><i class="fa-solid fa-trash-arrow-up"></i></button>
                        `;
                    } else {
                        return `<a href="/deal_projects/show/${data.id}" class="btn btn-secondary"><i class="fa-solid fa-circle-info"></i></a>`;
                    }
                },
            },
        ],
    });

    // Delete functionality
    $("#table-penawaran").on("click", ".delete-btn", function () {
        const surveyId = $(this).data("id");
        if (typeof deleteSurvey === "function") {
            deleteSurvey(surveyId);
        } else {
            console.error("Delete function is not defined.");
        }
    });

    function downloadFile(id, type) {
        $.ajax({
            url: '{{ route("penawaran_projects.download") }}',
            type: 'POST',
            data: {
                id: id,
                type: type
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(data, status, xhr) {
                var filename = '';
                var disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                }
                var linkElement = document.createElement('a');
                linkElement.setAttribute('href', window.URL.createObjectURL(data));
                linkElement.setAttribute('download', filename);
                linkElement.style.display = 'none';
                document.body.appendChild(linkElement);
                linkElement.click();
                document.body.removeChild(linkElement);
            },
            error: function(xhr, status, error) {
                console.error('Error downloading file:', error);
            }
        });
    }
});
</script>
