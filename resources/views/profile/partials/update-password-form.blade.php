@include('components.loading')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form id="update_password_form" method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="mb-3 position-relative">
            <label for="update_password_current_password">Current Password</label>
            <div class="position-relative">
                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="form-control w-full pe-5" placeholder="Current Password">
                <span class="toggle-password position-absolute d-flex align-items-center justify-content-center"
                      style="top: 0; bottom: 0; right: 10px; width: 40px; cursor: pointer;">
                    <i class="fa fa-eye"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="mb-3 position-relative">
            <label for="update_password_password">New Password</label>
            <div class="position-relative">
                <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="form-control w-full pe-5" placeholder="New Password">
                <span class="toggle-password position-absolute d-flex align-items-center justify-content-center"
                      style="top: 0; bottom: 0; right: 10px; width: 40px; cursor: pointer;">
                    <i class="fa fa-eye"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="mb-3 position-relative">
            <label for="update_password_password_confirmation">Confirm Password</label>
            <div class="position-relative">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="form-control w-full pe-5" placeholder="Confirm Password">
                <span class="toggle-password position-absolute d-flex align-items-center justify-content-center"
                      style="top: 0; bottom: 0; right: 10px; width: 40px; cursor: pointer;">
                    <i class="fa fa-eye"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Fungsi untuk toggle visibility password
        const togglePasswordIcons = document.querySelectorAll(".toggle-password");
        togglePasswordIcons.forEach(icon => {
            icon.addEventListener("click", function () {
                const input = this.previousElementSibling; // Input terkait
                const isPassword = input.type === "password";
                input.type = isPassword ? "text" : "password";
                this.innerHTML = isPassword ? "<i class='fa fa-eye-slash'></i>" : "<i class='fa fa-eye'></i>";
            });
        });

        // Overlay loading saat submit
        const form = document.getElementById("update_password_form");
        const loadingOverlay = document.getElementById("loading");
        const submitButton = form.querySelector("button[type='submit']");

        if (form && loadingOverlay && submitButton) {
            form.addEventListener("submit", function (e) {
                loadingOverlay.style.display = "flex"; // Tampilkan overlay
                submitButton.disabled = true; // Nonaktifkan tombol
                submitButton.textContent = "Loading..."; // Ubah teks tombol
            });
        } else {
            console.error("Form, loading overlay, atau tombol submit tidak ditemukan!");
        }
    });
</script>
@endpush
