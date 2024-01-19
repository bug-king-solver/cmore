
    @if(count($list) > 0)
        @foreach ($list as $labels)
        <div class="flex items-center gap-5 w-full mt-5">
            <div class="">
                <span class="w-3 h-3 block rounded-full {{$color ?? ''}}"></span>
            </div>
            <div class="">
                <p for="checkbox-website" class="font-encodesans text-sm font-normal text-esg8">{{ $labels }}</p>
            </div>
        </div>
        @endforeach
    @else 
    <div class="flex items-center gap-5 w-full mt-5">
        <label class="font-encodesans font-medium text-4xl text-esg8">-</label>
    </div>
    @endif