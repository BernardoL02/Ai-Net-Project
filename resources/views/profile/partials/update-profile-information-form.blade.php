<section class="grid grid-cols-1 md:items-start md:grid-cols-2 md:gap-x-24 sm:gap-x-10">
    <div>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Profile Information') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Update your account's profile information.") }}
            </p>
        </header>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form id="from_to_update_profile" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user?->name)" required autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="nif" :value="__('NIF')" />
                <x-text-input id="nif" name="nif" type="text" class="mt-1 block w-full" :value="old('nif', $customer?->nif)" autocomplete="nif" />
                <x-input-error class="mt-2" :messages="$errors->get('nif')" />
            </div>

            <div>
                <x-field.select
                    name="payment_type"
                    :options="['' => ' - ', 'VISA' => 'Visa', 'MBWAY' => 'MB Way', 'PAYPAL' => 'PayPal']"
                    :value="(string)$customer?->payment_type"
                    label="Payment Type"
                    :required="False"
                    :width="'full'"
                />
            </div>

            <div>
                <x-input-label for="payment_ref" :value="__('Payment Reference')" />
                <x-text-input id="payment_ref" name="payment_ref" type="text" class="mt-1 block w-full" :value="old('payment_ref', $customer?->payment_ref)" autocomplete="payment_ref" />
                <x-input-error class="mt-2" :messages="$errors->get('payment_ref')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user?->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-6 text-gray-800 dark:text-gray-200">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center">
                <x-button element="a" type="light" text="Cancel" class="mr-5" href="{{ url()->full() }}"/>

                <x-primary-button>{{ __('Save') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-600 dark:text-green-400 ml-4"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>

    <div>
        <x-field.image class="pt-2"
            name="photo_file"
            label="Photo"
            width="md"
            deleteTitle="Delete Photo"
            deleteAllow="true"
            deleteForm="form_to_delete_photo"
            submitForm="from_to_update_profile"
            :imageUrl="$user->photoFullUrl"/>
    </div>
</section>


<form id="form_to_delete_photo"
    method="POST" action="{{ route('profile.photo.destroy', ['user' => $user]) }}">
    @csrf
    @method('DELETE')
</form>



<!-- claudia.collier@mail.pt com valor -->
<!-- c4@mail.pt sem valor -->
<!-- e1@mail.pt--->
