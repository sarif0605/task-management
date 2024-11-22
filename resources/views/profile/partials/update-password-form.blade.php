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
        <div class="mb-3">
            <label for="update_password_current_password">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="form-control w-full" placeholder="Current Password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>
        <div class="mb-3">
            <label for="update_password_password">New Password</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="form-control w-full" placeholder="New Password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>
        <div class="mb-3">
            <label for="update_password_password_confirmation">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="form-control w-full" placeholder="Confirm Password">
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
    const form = document.getElementById("update-password-form");
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
