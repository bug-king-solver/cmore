@php $useExtends = $useExtends ?? true; @endphp

@if ($useExtends)
    @extends($useExtends ? customInclude('layouts.tenant') : 'layouts.base')
@endif

@if ($useExtends)
    @section('content')
    @endif
    <div class="h-fit flex items-center justify-center text-center">
        <div class="mt-30">
            <p class="text-esg28 text-center text-xl font-bold">
                {{ __('We are just preparing your questionnaire result') }}<br>
                {{ __('Please wait a few seconds...') }}
            </p>
            <p class="mt-4 text-esg28 text-center">
                {{ __('(the page will automatically refresh)') }}
            </p>
        </div>
    </div>
    @if ($useExtends)
    @endsection
@endif

@push('body')
    <script nonce="{{ csp_nonce() }}">
        window.setTimeout(() => {
            window.location = window.location;
        }, 3000);
    </script>
@endpush
