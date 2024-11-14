<script type="text/javascript">
    function handleFormSubmit(formSelector, submitFunction) {
        $(formSelector).on("submit", function (e) {
            e.preventDefault();
            submitFunction();
        });
    }

    function updateUser() {
        $("#loading").show();
        const formData = new FormData($("#update_data_form")[0]);

        $.ajax({
            url: $("#update_data_form").attr("action"),
            type: "POST",
            data: formData,
            processData: false, // Jangan memproses data
            contentType: false, // Jangan set content-type
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                $("#loading").hide();
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Berhasil Mengubah Data",
                    showConfirmButton: false,
                    timer: 1000,
                }).then(() => {
                    window.location.href = "/profile";
                });
            },
            error: function (xhr) {
                console.log("Update failed", xhr);
                $("#loading").hide();
                const errors = xhr.responseJSON?.errors || "There was a problem updating the profile.";
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errors,
                });
            },
        });
    }

    updatePassword = () => {
        $("#loading").show();
        $.ajax({
            url: $("#update_password_form").attr("action"),
            type: "POST",
            data: $("#update_password_form").serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                $("#loading").hide();
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Berhasil Ubah Password",
                    showConfirmButton: false,
                    timer: 1500,
                }).then(() => {
                    window.location.href = "/profile";
                });
            },
            error: function (xhr) {
                $("#loading").hide();
                const errors = xhr.responseJSON?.errors || "There was a problem creating the prospect.";
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: errors,
                });
            },
        });
    };

    $(document).ready(function () {
        handleFormSubmit("#update_data_form", updateUser);
        handleFormSubmit("#update_password_form", updatePassword);
    });
    </script>
