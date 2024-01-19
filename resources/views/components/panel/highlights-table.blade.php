@if(count($highlights))
<div class="grid grid-cols-2 gap-2 mt-3">
    @php
        $rows = array_chunk($highlights, 5, true);
    @endphp
    <div class="w-full h-48 flex-col justify-start items-center gap-2 inline-flex">
        @foreach($rows[0] as $key => $row)
            @if($key === '')
                <div class="w-full h-8 px-4 py-1 bg-esg5/20 rounded justify-between items-center inline-flex">
                    <div class="justify-start items-center gap-2 flex">
                        <div class="w-8 h-8 flex-col justify-center items-center gap-2.5 inline-flex">
                            @include('icons.trophy-highlight', ['width' => 32, 'height' => 33])
                        </div>
                        <div class="text-esg8 text-sm font-normal tracking-tight">{{ $row["name"] }}</div>
                    </div>
                    <span class="text-esg5 text-base font-bold tracking-tight">{{ $row["percentage"] }} <span class="text-sm text-esg16">%</span></span>
                </div>
            @else
                <div class="w-full h-8 px-4 justify-between items-center inline-flex">
                    <div class="justify-start items-center gap-2 flex">
                        <div class="w-8 h-8 flex-col justify-center items-center gap-2.5 inline-flex">
                            <span class="text-esg8 text-sm font-bold tracking-tight"># {{ $key+1 }}</span>
                        </div>
                        <div class="text-esg8 text-sm font-normal tracking-tight">{{ $row["name"] }}</div>
                    </div>
                    <span class="text-esg8 text-base font-bold tracking-tight">{{ $row["percentage"] }} <span class="text-sm text-esg16">%</span></span>
                </div>
            @endif
        @endforeach
    </div>
    @if(count($highlights) > 5)
        <div class="w-full h-48 flex-col justify-start items-center gap-2 inline-flex">
            @foreach($rows[1] as $key => $row)
            <div class="w-full h-8 px-4 justify-between items-center inline-flex">
                <div class="justify-start items-center gap-2 flex">
                    <div class="w-8 h-8 flex-col justify-center items-center gap-2.5 inline-flex">
                        <span class="text-esg8 text-sm font-bold tracking-tight"># {{ $key+1 }}</span>
                    </div>
                    <div class="text-esg8 text-sm font-normal tracking-tight">{{ $row["name"] }}</div>
                </div>
                <span class="text-esg8 text-base font-bold tracking-tight">{{ $row["percentage"] }} <span class="text-sm text-esg16">%</span></span>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endif
