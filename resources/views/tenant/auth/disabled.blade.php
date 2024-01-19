@extends(customInclude('layouts.tenant-auth'))

@section('content')

<div class="flex flex-wrap flex-col items-center justify-center mt-10">
    <div class="w-full max-w-sm flex flex-col justify-between items-center w-[360px] md:w-[320px] sm:w-[280px]">
        <div class="pt-4 pb-6">
            <h2 class="pl-4 mb-4 text-center text-2xl">
                <span class="text-esg8 font-semibold leading-9 font-encodesans font-medium">
                    {{ __('Your account has just been created!') }}
                </span>
            </h2>
            <p class="text-[#667085] max-w mt-2 text-center text-sm leading-5">
                <span>
                    {{ __('For security reasons, your account needs to be manually activated by an administrator. Once your account is activated you will receive an email notification, normally within 8 hours on weekdays.') }}
                </span>
            </p>
            <p class="text-[#667085] max-w mt-2 text-center text-sm leading-5">
                <span>
                    {{ __('Thanks for your patience through the approval process!') }}
                </span>
            </p>
        </div>
    </div>
</div>
@endsection
