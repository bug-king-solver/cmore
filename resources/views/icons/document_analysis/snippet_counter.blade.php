@if ($count ?? 0)
    @include('icons/document_analysis/snippet_found')
@else
    @include('icons/document_analysis/snippet_not_found')
@endif
