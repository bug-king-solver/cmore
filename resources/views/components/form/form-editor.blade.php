@props([
    'prop' => null,
    'id' => null,
    'label' => null,
    'form_div_size' => 'w-2/3',
    'placeholder' => null,
    'value' => null,
    'row' => false,
    'dataTest' => null,
    'fieldClass' => '',
])

@if ($row)
    <div class="flex">
@endif

@if ($label)
    <div class="mt-4 flex w-1/2 items-center">
        <label for="{{ $id }}" {{ $attributes->merge(['class' => 'text-esg29 block text-lg font-bold']) }}>
            {{ $label }}
        </label>
    </div>
@endif
@if($errors->has($prop ?? $id))
<span class="float-right mt-8 mr-8 z-10">
    @include('icons/alert-circle')
</span>
@endif

<div class="mt-4 {{ $form_div_size }} ">

    <div class="bg-white" wire:ignore data-test="{{ $dataTest }}">

        <div wire:model.defer="{{ $id }}" class="h-64 min-h-[150px] {{ $fieldClass ?? '' }}" x-data
            x-ref="quillEditor_{{ $id }}" x-init="$nextTick(() => {
            quill = new Quill($refs.quillEditor_{{ $id }}, {
                theme: 'snow',
                placeholder: '{{ $placeholder }}',
                modules: {
                    table: true,
                    history: {
                        delay: 2500,
                        userOnly: true
                    },
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['link', 'blockquote', 'code-block'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'align': [] }],
                        [
                            { 'indent': '-1' },
                            { 'indent': '+1' }
                        ],
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }, { 'color': [] }, { 'background': [] }],
                        {{-- ['table', 'column-right', 'row-below', 'row-remove', 'column-remove'], --}}['clean']
                    ]
                },
            });
            quill.on('text-change', function() {
                $dispatch('quill-input', quill.root.innerHTML);
            });

            makeQuillEdtitorTable(quill.getModule('table'));})"
            x-on:quill-input.debounce.500ms="@this.set('{{ $id }}', $event.detail, true)"
            >
            {!! html_entity_decode($value) !!}
        </div>
    </div>

    @error($prop ?? $id)
        <p class="mt-2 text-sm text-red-500">
            {{ $message }}
        </p>
    @enderror
</div>

@if ($row)
    </div>
@endif
