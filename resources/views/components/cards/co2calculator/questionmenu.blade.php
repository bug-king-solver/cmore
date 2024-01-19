@php $childrens = collect($category['childrens'])->chunk(3); @endphp

<div class="w-full flex flex-col items-center mt-8 justify-center md:flex-row md:gap-5 mb-8">
    @foreach ($childrens as $categories)
        <div class="flex items-center justify-center md:flex-row gap-5">
            @foreach ($categories as $cat)
                <div wire:click='updateCategoryChildrenShow({{ $category['id'] }}, {{ $cat['id'] }})'
                    class="drop-shadow px-10 py-8 grid place-items-center bg-esg4 border rounded-md cursor-pointer hover:border-esg5 hover:bg-[#FEF7F4] hover:scale-90 transition-all duration-300 {{ $cat['active'] ? 'border-esg5' : null }}">
                    @includeIf('icons.co2calculator.' . str_replace('icon-', '', $cat['note']), [
                        'color' => color(16),
                    ])
                </div>
            @endforeach
        </div>
    @endforeach
</div>
