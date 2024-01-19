<template x-if="{{ $id }}">
    <div class="fixed inset-x-0 bottom-0 z-10 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
        <div class="fixed inset-0">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div @click.away="{{ $id }} = false" class="bg-esg4 border-esg6 w-[40rem] transform overflow-hidden rounded-lg border-2 shadow-xl">
            <div class="bg-esg4 px-4 pt-5 pb-5 sm:p-6 sm:pb-4">
                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                    <h3 class="text-esg29 text-2xl font-extrabold" id="modal-headline">
                        @yield('modal_header')
                    </h3>
                    <div class="mt-3">
                        @yield('modal_content')
                    </div>
                </div>
            </div>

            <div class="mt-4 flex justify-center pb-5">
                @if (isset($cancel))
                    <button @click="{{ $id }} = false" class="focus:border-esg6 focus:shadow-outline-esg6 mr-4 rounded-md border border-gray-300 py-1 px-4 text-base font-medium uppercase text-gray-700 transition duration-150 ease-in-out hover:text-gray-500 focus:outline-none active:bg-gray-50 active:text-gray-800">
                        {{ $cancel['text'] }}
                    </button>
                @endif

                @if (isset($confirm))
                    <button {!! $confirm['tag'] !!} class="text-esg27 bg-esg6 rounded-md py-2.5 px-6 text-base font-bold uppercase">
                        {{ $confirm['text'] }}
                    </button>
                @endif
                @yield('footer')
            </div>
        </div>
    </div>
</template>
