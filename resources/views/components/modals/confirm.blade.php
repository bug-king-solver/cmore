<div>
    <div class="bg-esg4 px-4 pt-5 pb-5 sm:p-6 sm:pb-4">
        <div class="mt-3 text-center sm:mt-0 sm:text-left">
            @if (isset($title))
                <h2 {{ $title->attributes->merge(['class' => 'text-esg29 relative text-center text-2xl font-extrabold']) }}
                    id="modal-headline">
                    {{ $title }}
                </h2>
            @endif

            {{ $slot }}

            @if (isset($question))
                <h3 {{ $question->attributes->merge(['class' => 'text-esg8 pt-14 text-center font-medium']) }}>
                    {{ $question }}
                </h3>
            @endif

            @if (isset($extra))
                <h4 {{ $question->attributes->merge(['class' => 'text-esg8 text-center text-sm font-bold']) }}>
                    {{ $extra }}
                </h4>
            @endif

            @if ($errors->any())
                <div class="text-red-500 text-center mt-5">
                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-4 flex justify-center space-x-4 pb-5">
        <x-buttons.cancel />
        <x-buttons.confirm :click="$click" />
    </div>
</div>
