<div class="grid grid-cols-2 gap-5 text-base text-esg8">
    @foreach(json_decode(htmlspecialchars_decode($list), true) as $row)
        <div class="flex items-center">
            <div class="w-6">@include('vendor/flag-icons/flags/4x3/' . strtolower($row['cca2']))</div>
            <div class="pl-4"> {{ $row['name'] }} </div>
        </div>
    @endforeach
</div>
