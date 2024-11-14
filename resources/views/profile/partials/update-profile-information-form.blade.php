@include('components.loading')
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
    <form id="update_data_form" method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')
        <div class="mb-3">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->profile->name ?? '')" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="nik" :value="__('Nik')" />
            <x-text-input id="nik" name="nik" type="text" class="mt-1 block w-full" :value="old('nik', $user->profile->nik ?? '')" required autofocus autocomplete="nik" />
            <x-input-error class="mt-2" :messages="$errors->get('nik')" />
        </div>

        <div class="mb-3">
            <x-input-label for="birth_date" :value="__('Birth Date')" />
            <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $user->profile->birth_date ?? '')" required autofocus autocomplete="birth_date" />
            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
        </div>

        <div class="mb-3">
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->profile->address ?? '')" required autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="mb-3">
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" name="phone" type="number" class="mt-1 block w-full" :value="old('phone', $user->profile->phone ?? '')" required autofocus autocomplete="phone" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div class="mb-3">
            <x-input-label for="image_url" :value="__('Foto')" />
            @if ($user->profile && $user->profile->image_url)
                <div class="mb-3">
                    <img src="{{ asset($user->profile->image_url) }}" alt="Profile Image" class="h-20 w-20 rounded-full object-cover">
                </div>
            @endif
            <input id="image" name="image" type="file" class="mt-1 block w-full" accept="image/*">
            <x-input-error class="mt-2" :messages="$errors->get('image')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
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
    @include('profile.partials.script')
@endpush
