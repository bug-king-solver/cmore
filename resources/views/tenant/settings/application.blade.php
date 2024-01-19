@extends(customInclude('layouts.tenant'), ['title' => __('Application Settings'), 'isheader' => true])

@push('head')
    @livewireStyles(['nonce' => csp_nonce()])
@endpush

@section('content')
    <h3 class="text-base text-esg7 mb-10 !pt-[-20px]">
        <span class="font-bold">ID:</span> {{ tenant()->id }}
    </h3>

    <x-settings.tab-navigation :tabs="[
        ['id' => 'general', 'label' => 'General', 'icon' => 'settings'],
        ['id' => 'appearance', 'label' => 'Appearance', 'icon' => 'appearance'],
        ['id' => 'exportData', 'label' => 'Export data', 'icon' => 'export-data']
    ]" activeTab="general" />
    
    

    <div id="applicationSettingTab">
        <div class="hidden" id="general" role="tabpanel" aria-labelledby="general-tab">
            <div class="">
                {{-- Company name --}}
                <div class="w-full mt-7">
                    <form action="{{ route('tenant.settings.application.configuration') }}" method="POST">
                        @csrf
                        <div class="flex justify-between">
                            <div class="">
                                <label class="text-base font-semibold text-esg5 ">{!! __('Company Name') !!}</label>
                                <p class="text-xs text-esg8 mt-2.5">{!! __('This will be displayed on your website') !!}</p>
                            </div>
                            <div class="">
                                <button
                                    class="text-esg27 w-auto h-10 focus:shadow-outline-esg6 rounded-md border border-transparent bg-esg5 py-1 px-4 text-sm font-medium shadow-sm transition duration-150 ease-in-out">
                                    {!! __('Save') !!}
                                </button>
                            </div>
                        </div>
                        <div class="overflow-hidden sm:rounded-md mt-4">
                            <div class="bg-esg4">
                                <div>
                                    <label for="company"
                                        class="block text-sm font-medium leading-5 text-gray-700">{!! __('Name') !!}
                                    </label>
                                    <div class="relative mt-1 rounded-md shadow-sm">

                                        <x-inputs.text name="company" id="company"
                                            value="{{ old('company', tenant('company')) }}" />

                                    </div>
                                </div>

                                @error('company')
                                    <p class="mt-4 text-xs text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>

                {{-- LineBreak --}}
                <div class="my-7 border border-esg7/30"></div>

                {{-- Logo --}}
                <div class="w-full">
                    <form action="{{ route('tenant.settings.application.logo') }}" enctype="multipart/form-data"
                        method="POST">
                        @csrf
                        <div class="flex justify-between">
                            <div class="">
                                <label class="text-base font-semibold text-esg5 ">{!! __('Company Logo') !!}</label>
                            </div>
                            <div class="">
                                <button
                                    class="text-esg27 w-auto h-10 focus:shadow-outline-esg6 rounded-md border border-transparent bg-esg5 py-1 px-4 text-sm font-medium shadow-sm transition duration-150 ease-in-out">
                                    Save
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 w-full pl-0">
                            <div class="">
                                <label class="text-sm font-semibold text-esg8 ">{!! __('Header logo') !!}</label>
                                <p class="text-xs text-esg8">{!! __('This is the logo that will appear on the menu.') !!}</p>
                            </div>
                            <div class="overflow-hidden sm:rounded-md mt-2">
                                <div class="bg-esg4">
                                    <div>
                                        <div class="relative mt-1 rounded-md flex">
                                            <img id="logoPreview" class="w-44 h-44 rounded-xl bg-center mr-4 hidden"
                                                alt="{!! __('Preview') !!}">
                                            <div class="flex items-center justify-center w-44 h-44 p-2">
                                                <label for="logo"
                                                    class="flex flex-col items-center justify-center p-4 w-full h-full outline-dashed outline-offset-4 outline-2 outline-esg7 rounded-lg cursor-pointer">
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                        <p class="text-xs text-esg16 text-center">
                                                            {!! __('Choose file to upload') !!}</p>
                                                    </div>
                                                    <input type="file" name="logo" id="logo" class="hidden"
                                                        x-on:change="previewLogo(event, 'logoPreview')">
                                                </label>
                                            </div>
                                        </div>
                                        @error('logo')
                                            <p class="mt-4 text-xs text-red-500">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 w-full pl-0">
                            <div class="">
                                <label class="text-sm font-semibold text-esg8 ">{!! __('Login Logo') !!}</label>
                                <p class="text-xs text-esg8">
                                    {!! __('This is the image that will appear on the login page.') !!}</p>
                            </div>
                            <div class="overflow-hidden sm:rounded-md mt-2">
                                <div class="bg-esg4">
                                    <div>
                                        <div class="relative mt-1 rounded-md flex">
                                            <img id="loginLogoPreview" class="w-44 h-44 rounded-xl bg-center mr-4 hidden"
                                                alt="{!! __('Preview') !!}">
                                            <div class="flex items-center justify-center w-44 h-44 p-2">
                                                <label for="loginLogo"
                                                    class="flex flex-col items-center justify-center p-4 w-full h-full outline-dashed outline-offset-4 outline-2 outline-esg7 rounded-lg cursor-pointer">
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                        <p class="text-xs text-esg16 text-center">
                                                            {!! __('Choose file to upload') !!}</p>
                                                    </div>
                                                    <input type="file" name="loginLogo" id="loginLogo" class="hidden"
                                                        x-on:change="previewLogo(event,'loginLogoPreview')">
                                                </label>
                                            </div>
                                        </div>
                                        @error('loginLogo')
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

                {{-- LineBreak --}}
                <div class="my-7 border border-esg7/30"></div>

                {{-- Self Registration --}}
                <div class="flex flex-row flex-wrap">

                    <div class="flex justify-between">
                        <div class="">
                            <label class="text-base font-semibold text-esg5 ">{!! __('Self Registration') !!}</label>
                            <p class="text-xs text-esg8 mt-2.5">{!! __('Manage self registration.') !!}</p>
                        </div>
                        <div class="">
                        </div>
                    </div>

                    <div class="w-full mt-4">
                        <div class="bg-esg4 grid grid-cols-1 gap-6">
                            @livewire('self-registration')
                            @livewire('email-verification')
                            @livewire('manual-activation')
                        </div>
                    </div>
                </div>

                {{-- LineBreak --}}
                <div class="my-7 border border-esg7/30"></div>

                {{-- Notification --}}
                <div class="flex flex-row flex-wrap">
                    @livewire('email-notifications')
                </div>
            </div>
        </div>
        <div class="hidden" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
            <div class="mt-7">
                @livewire('colors')
            </div>

            {{-- LineBreak --}}
            <div class="my-7 border-b border-esg7/30"></div>

            <div class="mt-7">
                @livewire('landingpage')
            </div>
        </div>

        <div class="hidden" id="exportData" role="tabpanel" aria-labelledby="exportData-tab">
            <div class="mt-7">
              @livewire('export-data')
            </div>
        </div>
    </div>

    <script nonce="{{ csp_nonce() }}">
        function previewLogo(event, id) {
            const previewLogo = document.getElementById(id);
            previewLogo.style.display = event.target.files.length > 0 ? 'block' : 'none';
            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewLogo.setAttribute('src', e.target.result);
                };
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
@endsection
