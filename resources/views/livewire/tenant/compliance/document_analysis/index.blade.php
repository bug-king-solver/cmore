<div>
    <x-slot name="header">
        <x-header title="{{ __('Compliance') }}">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                @can('result.create')
                    <x-buttons.a-icon modal="compliance.document-analysis.modals.form" data-test="add-data-btn" class="flex place-content-end uppercase">
                        <div class="flex gap-1 items-center bg-esg5 py-2 px-4 cursor-pointer rounded-md text-esg4">
                            @include('icons.add', ['color' => color(4)])
                            <label>{{ __('Add') }}</label>
                        </div>
                    </x-buttons.a-icon>
                @endcan
            </x-slot>
        </x-header>
    </x-slot>
</div>

<div class="">

<div class=" text-esg5 text-lg font-semibold">
    {{__('Document Analysis')}}
</div>
</div>

<div class="mx-auto max-w-7xl sm:px-6 px-4 lg:px-0 leading-normal">

        <div>
            <div class="overflow-hidden overflow-x-auto">
                <x-tables.bordered.table aria-describedby="{{ __('List of analized documents') }}">
                    <x-slot name="thead">
                        <x-tables.bordered.th class="pl-14">{{ __('File name') }}</x-tables.bordered.th>
                        <x-tables.bordered.th>{{ __('Analysis status') }}</x-tables.bordered.th>
                        <x-tables.bordered.th>{{ __('Compliance level') }}</x-tables.bordered.th>
                        <x-tables.bordered.th>{{ __('Actions') }}</x-tables.bordered.th>
                    </x-slot>
                    @foreach ($results as $result)
                        <x-tables.bordered.tr class="text-gray-900 hover:bg-gray-100">

                            <x-tables.bordered.td class="flex items-center">
                                <div class="mr-3">
                                    @include('icons.document_analysis.1')
                                </div>
                                {{ $result->getFirstMedia('library')->name ?? '' }}
                            </x-tables.bordered.td>
                            <x-tables.bordered.td>
                                @include('partials/labels/status', ['status' => $result->status])
                            </x-tables.bordered.td>
                            <x-tables.bordered.td>
                                <div class="tooltip relative inline-block">
                                @include('partials/labels/level', ['level' => $result->compliance_level])
                                    <span class="tooltip-text w-52 invisible absolute z-10 text-esg8 bg-white rounded-lg px-4 py-3 whitespace-normal text-[11px] shadow-md
                                    before:absolute before:rotate-45 before:bg-white before:p-1 before:z-10 before:content-['']"
                                    id="right">
                                        @if ($result->compliance_level === \App\Enums\Compliance\DocumentAnalysis\ResultComplianceLevel::LOW)
                                           {{ __('ESG Maturity® preliminary analysis of Your data indicates an average of 25% of ESG topics that are either not covered or insufficiently covered.' )}}
                                        @elseif ($result->compliance_level === \App\Enums\Compliance\DocumentAnalysis\ResultComplianceLevel::MEDIUM)
                                            {{ __('ESG Maturity® preliminary analysis of your data indicates an average of 50% of ESG topics that are either not covered or insufficiently covered.') }}
                                        @elseif ($result->compliance_level === \App\Enums\Compliance\DocumentAnalysis\ResultComplianceLevel::HIGH)
                                            {{ __('ESG Maturity® preliminary analysis of your data indicates more than 75% of ESG topics are covered.') }}
                                        @elseif ($result->compliance_level === \App\Enums\Compliance\DocumentAnalysis\ResultComplianceLevel::WAITING)
                                            {{ __('ESG Maturity® is processing your data. The estimated timeline for this process is 24 business hours.') }}
                                        @endif
                                    </span>
                                </div>
                            </x-tables.bordered.td>
                            <x-tables.bordered.td class="flex items-center">
                                @if ($result->status === \App\Enums\Compliance\DocumentAnalysis\ResultStatus::COMPLETE)
                                    <x-buttons.a-icon
                                        href="{{ route('tenant.compliance.document_analysis.show', ['result' => $result]) }}">
                                        @include('icons.document_analysis.eye')
                                    </x-buttons.a-icon>
                                @endif
                                @php $buttonsData = json_encode(["result" => $result->id]); @endphp
                                <x-buttons.btn-icon modal="compliance.document-analysis.modals.delete"
                                    :data="$buttonsData">
                                    <x-slot name="buttonicon">
                                        @include('icons.trash', ['stroke' => color(12)])
                                    </x-slot>
                                </x-buttons.btn-icon>
                            </x-tables.bordered.td>
                        </x-tables.bordered.tr>
                    @endforeach
                    </x-tables-bordered-table>
            </div>
        </div>

        @if ($results->isEmpty())
            <div class="flex justify-center items-center p-6">
                <h3 class="w-fit text-md">
                    {{ __('No documents available yet. Click the “Add” button to create a new one.') }}</h3>
            </div>
        @endif
    </div>
</div>
<style nonce="{{ csp_nonce() }}">
  .tooltip:hover .tooltip-text {
    visibility: visible;
  }

  #right {
    bottom: -70%;
    left: 115%;
  }
  #right::before {
    left: -2%;
    bottom: 1.6rem;
    box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.1)
  }

</style>
