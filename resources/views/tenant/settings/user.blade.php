@extends(customInclude('layouts.tenant'), ['title' => __('My account'), 'isheader' => true])

@section('content')
    <script nonce="{{ csp_nonce() }}">
        function toogleChecks2FA(event) {
            let isUsing2fa = document.getElementById("check2fa").checked;

            let divEmail2FA = document.getElementById("email-2fa");
            if (divEmail2FA) {
                let email2fa = document.getElementById('email2fa');
                let labelEmail = document.querySelector("label[for=email2fa]");
                if (isUsing2fa) {
                    divEmail2FA.classList.remove('hidden');
                    email2fa.disabled = false;
                    email2fa.readOnly = false;
                    labelEmail.classList.remove('text-esg11');
                } else {
                    email2fa.disabled = true;
                    email2fa.readOnly = true;
                    email2fa.checked = false;
                    labelEmail.classList.add('text-esg11');
                    divEmail2FA.classList.add('hidden');
                }
            }

            let divApplication2FA = document.getElementById("application-2fa");
            if (divApplication2FA) {
                if (isUsing2fa) {
                    divApplication2FA.classList.remove("hidden");
                } else {
                    divApplication2FA.classList.add("hidden");
                }
            }

            let divBackupCodes2FA = document.getElementById("backup-codes-2fa");
            if (divBackupCodes2FA) {
                if (isUsing2fa) {
                    divBackupCodes2FA.classList.remove("hidden");
                } else {
                    divBackupCodes2FA.classList.add("hidden");
                }
            }

            let divPhone2FA = document.getElementById("phone-2fa");
            if (divPhone2FA) {
                let phone2fa = document.getElementById('phone2fa');
                let labelPhone = document.querySelector("label[for=phone2fa]");
                if (isUsing2fa) {
                    phone2fa.disabled = false;
                    phone2fa.readOnly = false;
                    labelPhone.classList.remove('text-esg11');
                    divPhone2FA.classList.remove('hidden');
                } else {
                    phone2fa.disabled = true;
                    phone2fa.readOnly = true;
                    phone2fa.checked = false;
                    labelPhone.classList.add('text-esg11');
                    divPhone2FA.classList.add('hidden');
                }
            }
        }

        function unsecuredCopyToClipboard(text) {
            const textArea = document.createElement("textarea")
            textArea.value = text
            document.body.appendChild(textArea)
            textArea.focus()
            textArea.select()
            try {
                document.execCommand('copy')
            } catch (err) {
                console.error('Unable to copy to clipboard')
            }
            document.body.removeChild(textArea)
        }

        function copyText(text) {
            if (window.isSecureContext && navigator.clipboard) {
                navigator.clipboard.writeText(text);
            } else {
                unsecuredCopyToClipboard(text)
            }
        }

    </script>

    @if (auth()->user()->password_force_change)
        <x-alerts.info>{{ __('You must change the password before proceeding.') }}</x-alerts.info>
    @endif

    <div class="pt-2 px-4 lg:px-0">
        <div class="">
            <form action="{{ route('tenant.settings.user.personal') }}" method="POST">
                @csrf
                <div class="flex items-start justify-between w-full">
                    <div class="pb-4">
                        <h3 class="text-lg leading-6 text-esg5 font-semibold">{{ __('Personal information') }}
                        </h3>
                        <p class="mt-1 text-sm leading-5 text-esg16 ">
                            {{ __('This information will be displayed publicly.') }}
                        </p>
                    </div>

                    <div class="">
                        <button
                            class="text-esg27 w-auto h-10 focus:-outline-esg6 rounded-md border bg-esg5 py-1 px-4 text-sm font-medium -sm transition duration-150 ease-in-out uppercase">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
                <div class="mt-4 w-6/12 pl-0">
                    <div class="overflow-hidden  sm:rounded-md">
                        <div class="bg-esg4">
                            <div>
                                <label for="name"
                                    class="block text-sm font-medium leading-5 text-gray-700">{{ __('Name') }}
                                </label>
                                <div class="relative mt-1 rounded-md -sm">
                                    <input id="name" name="name" value="{{ old('name', auth()->user()->name) }}"
                                        class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md text-lg block w-full sm:leading-5"
                                        placeholder="Luis Coutinho" />
                                </div>
                            </div>

                            @error('name')
                                <p class="mt-4 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                            <div class="mt-4">
                                <label for="email"
                                    class="block text-sm font-medium leading-5 text-gray-700">{{ __('Email') }}
                                </label>
                                <div class="relative mt-1 rounded-md -sm">
                                    <input id="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                        class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md text-lg block w-full sm:leading-5"
                                        placeholder="you@example.com" />
                                </div>

                                @error('email')
                                    <p class="mt-4 text-xs text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label for="phone"
                                    class="block text-sm font-medium leading-5 text-gray-700">{{ __('Phone') }}
                                </label>
                                <div class="relative mt-1 rounded-md -sm">
                                    <input id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                        class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md text-lg block w-full sm:leading-5"
                                        placeholder="351123456" />
                                </div>

                                @error('phone')
                                    <p class="mt-4 text-xs text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="w-full my-8 border border-esg7/20"></div>

    <div class="px-4 lg:px-0">
        <div class="">
            <form action="{{ route('tenant.settings.user.photo') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="flex items-start justify-between w-full">
                    <div class="pb-4">
                        <h3 class="text-lg leading-6 text-esg5 font-semibold">{{ __('Avatar') }}
                        </h3>
                        <p class="mt-1 text-sm leading-5 text-esg16">
                            {{ __('Upload your own photo.') }}
                        </p>
                    </div>

                    <div class="">
                        <button
                            class="text-esg27 w-auto h-10 focus:-outline-esg6 rounded-md border bg-esg5 py-1 px-4 text-sm font-medium -sm transition duration-150 ease-in-out uppercase">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
                <div class="mt-4 w-full pl-0">
                    <div class="overflow-hidden  sm:rounded-md">
                        <div class="bg-esg4">
                            <div class="relative mt-1 rounded-md flex">
                                <img id="photoPreview"  class="w-44 h-44 rounded-xl bg-center mr-4 hidden" alt="{{ __('Preview') }}">
                                <div class="flex items-center justify-center w-44 h-44 p-2">
                                    <label for="photo" class="flex flex-col items-center justify-center p-4 w-full h-full outline-dashed outline-offset-4 outline-2 outline-esg7 rounded-lg cursor-pointer">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <p class="text-xs text-esg16 text-center">{{ __('Drag and drop or Choose file to upload') }}</p>
                                        </div>
                                        <input type="file" name="photo" id="photo" class="hidden" x-on:change="previewPhoto(event)">
                                    </label>
                                </div>
                            </div>

                            @error('photo')
                                <p class="mt-4 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="w-full my-8 border border-esg7/20"></div>

    <div class="px-4 lg:px-0">
        <div class="">
            <form action="{{ route('tenant.settings.user.password') }}" method="POST">
                @csrf
                <div class="flex items-start justify-between w-full">
                    <div class="pb-4 border-b border-esg15">
                        <h3 class="text-lg leading-6 text-esg5 font-semibold">{{ __('Password') }}
                        </h3>
                        <p class="mt-1 text-sm leading-5 text-esg16">
                            {{ __('Change your password.') }} <a
                                class="font-medium esg5 transition duration-150 ease-in-out focus:underline focus:outline-none underline hover:text-esg5"
                                href="{{ route('tenant.password.request') }}">{{ __('Forgot your current password?') }}</a>
                        </p>
                    </div>
                    <div>
                        <button
                            class="text-esg27 w-auto h-10 focus:-outline-esg6 rounded-md border bg-esg5 py-1 px-4 text-sm font-medium -sm transition duration-150 ease-in-out uppercase">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
                <div class="mt-4 w-full pl-0 ">
                    <div class="overflow-hidden  sm:rounded-md">
                        <div class="bg-esg4 w-6/12">
                            <div class="">
                                <label for="password" class="block text-sm font-medium leading-5 text-gray-700">
                                    {{ __('Current password') }}
                                </label>
                                <div class="mt-1 rounded-md">
                                    <input id="password" type="password" required
                                        class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md text-lg -sm appearance-none block w-full px-3 py-2  focus:outline-none focus:-outline-esg6 focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('password') border-red-500 @enderror"
                                        name="password" />

                                    @error('password')
                                        <p class="mt-4 text-xs text-red-500">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="new_password" class="block text-sm font-medium leading-5 text-gray-700">
                                    {{ __('New password') }}
                                </label>
                                <div class="mt-1 rounded-md">
                                    <input id="new_password" type="password" required
                                        class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md text-lg -sm appearance-none block w-full px-3 py-2 focus:outline-none focus:-outline-esg6 focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('new_password') border-red-500 @enderror"
                                        name="new_password" />

                                    @error('new_password')
                                        <p class="mt-4 text-xs text-red-500">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="new_password_confirmation"
                                    class="block text-sm font-medium leading-5 text-gray-700">
                                    {{ __('Confirm password') }}
                                </label>
                                <div class="mt-1 rounded-md">
                                    <input id="new_password_confirmation" type="password" required
                                        class="h-11 pl-3.5 text-esg8 form-input placeholder-esg10 border border-esg10 rounded-md text-lg -sm appearance-none block w-full px-3 py-2 focus:outline-none focus:-outline-esg6 focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('new_password_confirmation') border-red-500 @enderror"
                                        name="new_password_confirmation" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (tenant()->has2faEnabled)
        <div class="w-full my-8 border border-esg7/20"></div>
        <div class="">
            <form action="{{ route('tenant.settings.user.setUp2FA') }}" method="POST">
                @csrf
                <div class="flex items-start justify-between w-full">
                    <div class="pb-4">
                        <h3 class="text-lg leading-6 text-esg5 font-semibold">{{ __('Two-factor authentication (2FA) Service') }}</h3>
                        <p class="mt-1 text-sm leading-5 text-esg16">
                            {{ __('Set up 2FA') }}
                        </p>
                    </div>
                    <div>
                        <button
                            class="text-esg27 w-auto h-10 focus:-outline-esg6 rounded-md border bg-esg5 py-1 px-4 text-sm font-medium -sm transition duration-150 ease-in-out">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
                <div class="mt-4 w-full pl-0 mb-4">
                    <div class="flex flex-row">
                        <div class="grow self-center">
                            <div class="flex flex-row text-sm relative w-full items-center z-0 mb-1 w-full group">
                                <input type="checkbox" id="check2fa" name="check2fa" class="checked:bg-esg6 mr-3.5" x-on:change="toogleChecks2FA()" @if(auth()->user()->is2FAEnabled())checked @endif value="1" />
                                <label for="check2fa">{!! __('Enable 2FA') !!}</label>

                                @error('check2fa')
                                <p class="mt-1 mb-5 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            @if(auth()->user()->is2FAEnabled() && tenant()->has2faEmailEnabled)
                            <div id="email-2fa" class="flex flex-row text-sm relative w-full items-center z-0 mb-1 w-full group">
                                <input type="checkbox" id="email2fa" name="email2fa" class="checked:bg-esg6 mr-3.5" @if(auth()->user()->isToSend2FAEmail())checked @endif value="1" />
                                <label class="@if(!auth()->user()->is2FAEnabled())text-esg11 @endif" for="email2fa">{!! __('Enable 2FA by Email') !!}</label>

                                @error('email2fa')
                                <p class="mt-1 mb-5 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            @endif
                            @if(auth()->user()->is2FAEnabled() && isset(auth()->user()->phone) && tenant()->has2faSMSEnabled)
                            <div id="phone-2fa" class="flex flex-row text-sm relative w-full items-center z-0 mb-1 w-full group">
                                <input type="checkbox" id="phone2fa" name="phone2fa" class="checked:bg-esg6 mr-3.5" @if(auth()->user()->isToSend2FAPhone())checked @endif value="1" />
                                <label class="@if(!auth()->user()->is2FAEnabled())text-esg11 @endif" for="phone2fa">{!! __('Enable 2FA by SMS') !!}</label>
                                @error('phone2fa')
                                <p class="mt-1 mb-5 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-4 lg:px-0">
            <div class="flex flex-row flex-wrap">
                <div class="mt-4 w-full pl-0 mb-4">
                    <div class="overflow-hidden sm:rounded-md">
                        @if(auth()->user()->is2FAEnabled() && tenant()->has2faApplicationEnabled)
                            <div id="application-2fa" class="flex flex-row border-b-[1px] bg-esg4 px-4 py-5 sm:p-6">
                                <div class="grow self-center">
                                    <div class="flex flex-row text-sm relative w-full items-center z-0 mb-1 w-full group">
                                        <label for="configure-2fa">{{ __('Configure 2FA with Application') }}</label>
                                    </div>
                                </div>
                                <div class="flex-none self-center">
                                    <div class="flex justify-end">
                                        <button
                                            x-on:click="Livewire.emit('openModal', 'users.modals.two-fa')"
                                            class="text-esg8 w-auto h-10 rounded-md border-[1px] border-esg8/50 py-1 px-4 text-sm font-medium">
                                            {{ __('Configure') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(auth()->user()->is2FAEnabled() && tenant()->has2faBackupsCodeEnabled)
                            <div id="backup-codes-2fa" class="flex flex-row border-b-[1px] bg-esg4 px-4 py-5 sm:p-6">
                                <div class="grow self-center">
                                    <div class="flex flex-row text-sm relative w-full items-center z-0 mb-1 w-full group">
                                        <label>{!! __('Regenerate recovery codes') !!}</label>
                                    </div>
                                </div>
                                <div class="flex-none self-center">
                                    <div class="flex justify-end">
                                        <button
                                            x-on:click="Livewire.emit('openModal', 'users.modals.recovery-token')"
                                            class="text-esg8 w-auto h-10 rounded-md border-[1px] border-esg8/50 py-1 px-4 text-sm font-medium">
                                            {{ __('Regenerate') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

<script  nonce="{{ csp_nonce() }}">
    function previewPhoto(event) {
        const previewPhoto = document.getElementById('photoPreview');
        previewPhoto.style.display = event.target.files.length > 0 ? 'block' : 'none';
        if (event.target.files && event.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewPhoto.setAttribute('src', e.target.result);
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
