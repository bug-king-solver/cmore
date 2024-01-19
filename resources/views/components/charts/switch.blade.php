@foreach(json_decode(htmlspecialchars_decode($data), true) as $row)
    <div class="flex">
        <div class="">
            @include(tenant()->views .'icons.book', ['class' => 'inline-block ml-2', 'color' => color($row['color'] ?? 2)])
        </div>
        <div class="pl-4 w-96">
            <p class="text-esg8 font-normal text-base"> {{ $row['text'] }}</p>
        </div>
        <div class="pl-4 -mt-5">
            <x-inputs.switch-btn id="{{ $row['id'] }}" checked="{{ $row['check'] }}" disabled="disabled" class="peer-checked:!bg-esg{{ $row['color'] ?? 2 }}"/>
        </div>
    </div>
@endforeach
