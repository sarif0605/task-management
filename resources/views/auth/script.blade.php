<script type="text/javascript">
function handleFormSubmit(formSelector, submitFunction) {
    $(formSelector).on("submit", function (e) {
        e.preventDefault();
        submitFunction();
    });
}

loginUser = () => {
    console.log("Login function called");
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
            console.log("Login failed", xhr);
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

registerUser = () => {
    console.log("Login function called");
    $("#loading").show();
    $.ajax({
        url: $("#register-form").attr("action"),
        type: "POST",
        data: $("#register-form").serialize(),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            console.log("register successful");
            $("#loading").hide();
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Berhasil Registrasi!",
                showConfirmButton: false,
                timer: 1500,
            }).then(() => {
                window.location.href = "/verifikasi-view";
            });
        },
        error: function (xhr) {
            console.log("Login failed", xhr);
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

verifikasiUser = () => {
    console.log("Verifikasi function called");
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
            console.log("Login failed", xhr);
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
    handleFormSubmit("#login-form", loginUser);
    handleFormSubmit("#register-form", registerUser);
    handleFormSubmit("#form-verifikasi", verifikasiUser);
});
</script>
