<div>
    <div class="fixed inset-x-0 bottom-0 z-50 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
        <div class="fixed inset-0 {{ $background ?? '' }}">
            <div class="absolute inset-0 bg-gray-500 opacity-0"></div>
        </div>
        <div class="transform overflow-hidden p-0">
            {{ $slot ?? '' }}
        </div>
    </div>
</div>
