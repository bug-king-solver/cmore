<div>
    @if (isset($title))
        <label class="forn-normal text-esg8"> {{ $title }} </label>
    @endif

    @if ($attributes->has('readonly') && $attributes->get('readonly') == 'true')
        <x-inputs.input-group placeholder="{{ $placeholder }}" unit="{{ $unit ?? '€' }}" id="{{ $id ?? $name }}"
            type="{{ $type ?? 'text' }}" {{ $attributes->merge([]) }} readonly />
    @else
        <x-inputs.input-group placeholder="{{ $placeholder }}" unit="{{ $unit ?? '€' }}" id="{{ $id ?? $name }}"
            type="{{ $type ?? 'text' }}" modelmodifier="{{$modelmodifier ?? '.lazy'}}" pattern="[0-9]+(\.[0-9]+)?"
            {{ $attributes->merge([])->except('readonly') }} />
    @endif

    @if ($errors->has($prop ?? ($id ?? $name)))
        <span class="absolute top-4 bottom-0 right-0 flex items-center pr-9 z-10">
            @include('icons/alert-circle')
        </span>
    @endif

    @error($prop ?? ($id ?? $name))
        <p class="mt-2 text-sm text-red-500">
            {{ $message }}
        </p>
    @enderror
</div>
