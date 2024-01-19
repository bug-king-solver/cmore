@props([
    'remote' => false,
    'preload' => false,
    'plugins' => null,
	'optgroups' => [],
	'options' => [],
	'items' => [],
    'limit' => null,
    'max_options' => 'null',
])

<script nonce="{{ csp_nonce() }}">
    document.addEventListener('DOMContentLoaded', () => {
        let options = {
            plugins: {!! $plugins ?? "''" !!},
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            items: {!! $items ?: "''" !!},
            @if ($remote)
                load: function(query, callback) {
                    var self = this;
                    if( self.loading > 1 ){
                        callback();
                        return;
                    }

                    var url = '{{ $remote }}';
                    fetch(url)
                        .then(response => response.json())
                        .then(json => {
                            callback(json);
                            self.settings.load = null;
                        }).catch(()=>{
                            callback();
                        });
                },
                preload: {{ $preload ? "'focus'" : 'false' }},
            @else
                @if ($optgroups)
                    optgroups: {!! json_encode($optgroups) !!},
                    optgroupField: 'optgroup',
                    optgroupValueField: 'id',
                @endif
                options: {!! json_encode($options) !!},
                maxOptions: {{ $max_options }},
                maxItems: {{ $limit ?: 'null' }},
                @if (! empty($items) && ! $attributes->has('multiple'))
                    placeholder: undefined,
                @endif
            @endif
            render: {
                optgroup_header: (data, escape) => {
                let title = 'title' in data ? `<div class='block'>${escape(data.title)}</div>` : '';

                return `
                    <div class='flex items-center'>
                        ${title}
                    </div>
                `;
            },
            option: (data, escape) => {
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
            item: (data, escape) => {
                let img = 'img' in data ? `<div class='mr-3 w-6'>${data.img}</div>` : '';
                let title = 'title' in data ? `<div>${escape(data.title)}</div>` : '';

                return `
                <div class='flex items-center'>
                    ${img}
                    ${title}
                </div>`;
            }
            }
        };

        new TomSelect(".tom-select", options);
    });
</script>

<div>
	<select autocomplete="nope" {{ $attributes->except('extra')->merge(['class' => 'tom-select']) }}></select>
</div>
