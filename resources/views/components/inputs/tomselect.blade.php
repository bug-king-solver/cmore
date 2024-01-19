@props([
    'id' => null, // This is not used, it was added to solve the bug related with data refresh
    'wire_ignore' => true,
    'remote' => false,
    'preload' => false,
    'plugins' => null,
    'optgroups' => [],
    'options' => [],
    'items' => [],
    'limit' => null,
    'class' => null,
    'max_options' => 'null',
    'allowcreate' => null,
    'dataTest' => null,
    'modelmodifier' => '.defer',
    'prop' => null,
])

<div {{ $wire_ignore ? 'wire:ignore' : '' }} class="{{ $class }}">

    @php
        // set the prop as : :prop="false" when you dont want to have model bind events OR
        // a diferent model name from the ID
        $wireModel = $prop === false ? false : ($prop ?? $id);
    @endphp

    <select autocomplete="nope" data-property-id="{{ $id }}" class="{{ $class }}"
        data-test="{{ $dataTest }}" wire:model{{ $modelmodifier ?? '' }}="{{ $wireModel }}" x-data="{
            tomSelectInstance: null,
            @if (!$remote) @if ($optgroups)
                    optgroups: {{ collect($optgroups) }}, @endif
            options: {{ collect($options) }},
            @endif
            items: {{ collect($items) }},
            optgroupTemplate(data, escape) {
                let title = 'title' in data ? `<div class='block'>${escape(data.title)}</div>` : '';

                return `
                            <div class='flex items-center'>
                                ${title}
                            </div>
                        `;
            },
            optionTemplate(data, escape) {
                let img = 'img' in data ? `<div class='bg-esg4 mr-3 w-6'>${data.img}</div>` : '';
                let title = 'title' in data ? `<div class='block'>${escape(data.title)}</div>` : '';
                let subtitle = 'subtitle' in data ? `<div class='block text-sm'>${escape(data.subtitle)}</div>` : '';

                return `
                            <div class='flex items-center'>
                                ${title}
                                ${subtitle}
                            </div>
                        `;
            },
            itemTemplate(data, escape) {
                let img = 'img' in data ? `<div class='mr-3 w-6'>${data.img}</div>` : '';
                let title = 'title' in data ? `<div>${escape(data.title)}</div>` : '';

                return `
                        <div class='flex items-center bg-esg6'>
                            ${img}
                            ${title}
                        </div>`;
            }
        }"
        x-init="tomSelectInstance = new TomSelect($refs.input, {
            plugins: {{ $plugins ?? "''" }},
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            items: items,
            @if($remote)
            load: function(query, callback) {
                var self = this;
                if (self.loading > 1) {
                    callback();
                    return;
                }

                var url = '{{ $remote }}';
                fetch(url)
                    .then(response => response.json())
                    .then(json => {
                        callback(json);
                        self.settings.load = null;
                    }).catch(() => {
                        callback();
                    });
            },
            preload: {{ $preload ? "'focus'" : 'false' }},
            @else
            @if($optgroups)
            optgroups: optgroups,
            optgroupField: 'optgroup',
            optgroupValueField: 'id',
            @endif
            options: options,
            maxOptions: {{ $max_options }},
            maxItems: {{ $limit ?: 'null' }},
            create: {{ $allowcreate ? 'true' : 'false' }},
            createOnBlur: {{ $allowcreate ? 'true' : 'false' }},
            @if(!empty($items) && !$attributes->has('multiple'))
            placeholder: undefined,
            @endif
            @endif
            onChange: function(value) {
                this.setTextboxValue('');
            },
            render: {
                optgroup_header: optgroupTemplate,
                option: optionTemplate,
                item: itemTemplate
            }
        });" x-ref="input" x-cloak {{ $attributes }}></select>
</div>
