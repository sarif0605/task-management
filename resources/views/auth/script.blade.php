<script type="text/javascript">
function handleFormSubmit(formSelector, submitFunction) {
    $(formSelector).on("submit", function (e) {
        e.preventDefault();
        submitFunction();
    });
}

loginUser = () => {
    $("#loading").show();
    $.ajax({
        url: $("#login-form").attr("action"),
        type: "POST",
        data: $("#login-form").serialize(),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            console.log("Login successful");
            $("#loading").hide();
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Berhasil Login",
                showConfirmButton: false,
                timer: 1500,
            }).then(() => {
                window.location.href = "/dashboard";
            });
        },
        error: function (xhr) {
            $("#loading").hide();
            if (xhr.responseJSON?.errors) {
                const errors = xhr.responseJSON.errors;
                for (const [key, messages] of Object.entries(errors)) {
                    const errorField = $(`input[name="${key}"]`).next(".text-danger");
                    errorField.text(messages[0]);
                }
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Email atau Password salah.",
                });
            }
        },
    });
};

registerUser = () => {
    $("#loading").show();
    $.ajax({
        url: $("#register-form").attr("action"),
        type: "POST",
        data: $("#register-form").serialize(),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $("#loading").hide();
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Berhasil Melakukan Registrasi dan cek email mu!",
                showConfirmButton: false,
                timer: 1500,
            }).then(() => {
                window.location.href = "/verifikasi-view";
            });
        },
        error: function (xhr) {
            if (xhr.responseJSON?.errors) {
                const errors = xhr.responseJSON.errors;
                for (const [key, messages] of Object.entries(errors)) {
                    const errorField = $(`input[name="${key}"]`).next(".text-danger");
                    errorField.text(messages[0]);
                }
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Proses Registrasimu Gagal.",
                });
            }
        },
    });
};

verifikasiUser = () => {
    $("#loading").show();
    $.ajax({
        url: $("#form-verifikasi").attr("action"),
        type: "POST",
        data: $("#form-verifikasi").serialize(),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            console.log("register successful");
            $("#loading").hide();
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Berhasil Melakukan Verifikasi User!",
                showConfirmButton: false,
                timer: 1500,
            }).then(() => {
                window.location.href = "/dashboard";
            });
        },
        error: function (xhr) {
            $("#loading").hide();
            if (xhr.responseJSON?.errors) {
                const errors = xhr.responseJSON.errors;
                for (const [key, messages] of Object.entries(errors)) {
                    const errorField = $(`input[name="${key}"]`).next(".text-danger");
                    errorField.text(messages[0]);
                }
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Proses Verifikasi Akun Gagal.",
                });
            }
        },
    });
};

$(document).ready(function () {
    handleFormSubmit("#login-form", loginUser);
    handleFormSubmit("#register-form", registerUser);
    handleFormSubmit("#form-verifikasi", verifikasiUser);
});
</script>
