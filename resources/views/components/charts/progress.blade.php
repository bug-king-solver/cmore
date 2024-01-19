@foreach(json_decode(htmlspecialchars_decode($data), true) as $row)
    <div class="flex">
        <div class="">
            @include(tenant()->views .'icons.book', ['class' => 'inline-block ml-2', 'color' => color($row['color'] ?? 1)])
        </div>
        <div class="pl-4 w-64">
            <p class="text-esg8 font-normal text-base"> {{ $row['text'] }}</p>
        </div>
        <div class="pl-4 mt-1 w-40">
            <div class="w-full bg-esg8/[0.2] h-8 dark:bg-gray-700">
                <div class="bg-esg{{ $row['color'] ?? 1 }} text-xs h-8 font-medium text-esg8 text-center pt-3 leading-none w-[{{ $row['percentage'] }}%]"> {{ $row['percentage'] }}%</div>
            </div>
        </div>
    </div>
@endforeach
