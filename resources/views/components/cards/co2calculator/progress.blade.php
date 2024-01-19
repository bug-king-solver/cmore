<ol class="flex items-center w-full mb-4 mt-8">

    @php
        $isFilled = true;
    @endphp
    @foreach ($categories as $i => $category)
        @php
            $class = '';
            if (!$loop->last) {
                $class = "after:content-[''] after:w-full after:h-1 after:border-b after:mx-2 after:rounded-md after:border-4 after:inline-block";
                if ($isFilled) {
                    $class .= ' after:border-esg6';
                } else {
                    $class .= ' after:border-esg6/20';
                }
            }
        @endphp
        <li  class="flex w-full items-center text-esg27 {{ $class }}">
            <div wire:click="setActiveCategory({{ $category['id'] }})"
                class="flex items-center cursor-pointer justify-center w-10 h-10 {{ $isFilled ? 'bg-esg6' : 'bg-esg6/20' }} rounded-full lg:h-12 lg:w-12 shrink-0">
                @if ($loop->last)
                    ✓
                @else
                    {{ $i + 1 }}
                @endif
            </div>
        </li>
        @php
            if ($isFilled && $category['id'] == $categoryActive['id']) {
                $isFilled = false;
            }
        @endphp
    @endforeach
</ol>
