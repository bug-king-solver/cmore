<div class="relative flex overflow-hidden my-2 rounded-xl bg-esg4 drop-shadow-md font-encodesans">
    <a href="{{ route('tenant.library.show', ['documentHeading' => $type, 'documentType' => $value, 'folder' => $folder ?? null])}}" class="flex w-full">
        <div class="mx-auto bg-esg6 ">
            @include($icon ?? "icons.library.{$value}", ['class' => 'inline-block', 'width' => $iconWith ?? 81, 'height' => $iconHeight ?? 70])
        </div>
        <div class="w-full flex items-center">
            <h2 class="text-sm font-medium text-esg8 pl-5 uppercase">
                {{ __(str_replace("_", " ", $slot)) }}
            </h2>
        </div>
    </a>
</div>
