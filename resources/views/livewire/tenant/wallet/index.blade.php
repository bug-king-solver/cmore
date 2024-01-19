<div>
    <x-slot name="header">
        <x-header title="{{ __('Wallet') }}" data-test="balance-header">
            <x-slot name="left">
            </x-slot>

            <x-slot name="right">
                <x-buttons.btn-icon-text  class="!px-6 !normal-case !rounded-md !text-sm !font-medium" modal="wallet.modals.add-funds">
                    <x-slot:buttonicon>
                        @include('icons.wallet', ['width' => 18, 'height' => 18])
                    </x-slot:buttonicon>
                    <x-slot:slot>
                        <span class="ml-2">{{ __('Add Funds') }}</span>
                    </x-slot:slot>
                </x-buttons.btn-icon-text>
            </x-slot>
        </x-header>
    </x-slot>

    <div class="">
        @if (tenant()->has_insufficient_funds)
            <x-alerts.danger>{{ __('You have exceeded your credit limit. Before continuing, charge your wallet.') }}</x-alerts>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 duration-1000 transform transition-all translate-y-20 ease-out" data-replace='{ "translate-y-20": "translate-y-0" }'>
            <x-cards.catalog.card class="transition ease-in-out duration-300 hover:z-10 hover:bg-gray-100">
                <x-slot:icon>
                    @include('icons.snapshot.1')
                </x-slot:icon>
    
                <x-slot:data>
                    <div class="">
                        <p class="text-base font-medium text-esg16"> {{ __('Balance') }} </p>
                        <div class="flex items-center gap-3">
                            <label class="text-esg8 text-2xl font-bold">{{ formatToCurrency($balance, false, 'EUR') }}</label>
                        </div>
                    </div>
                </x-slot:data>
            </x-cards.catalog.card>
    
            <x-cards.catalog.card class="transition ease-in-out duration-300 hover:z-10 hover:bg-gray-100">
                <x-slot:icon>
                    @include('icons.snapshot.2')
                </x-slot:icon>
    
                <x-slot:data>
                    <div class="">
                        <p class="text-base font-medium text-esg16"> {{ __('Total deposited') }} <span class="text-xs">{{ __('(last 90 days)') }}</span></p>
                        <div class="flex items-center gap-3">
                            <label class="text-esg8 text-2xl font-bold">{{ formatToCurrency($totalDeposited90Days, false, 'EUR') }}</label>
                        </div>
                    </div>
                </x-slot:data>
            </x-cards.catalog.card>
    
            <x-cards.catalog.card class="transition ease-in-out duration-300 hover:z-10 hover:bg-gray-100">
                <x-slot:icon>
                    @include('icons.snapshot.3')
                </x-slot:icon>
                
                <x-slot:data>
                    <div class="">
                        <p class="text-base font-medium text-esg16"> {{ __('Total spent') }} <span class="text-xs">{{ __('(last 90 days)') }}</span></p>
                        <div class="flex items-center gap-3">
                            <label class="text-esg8 text-2xl font-bold">{{ formatToCurrency($totalWithdrawn90Days, false, 'EUR') }}</label>
                        </div>
                    </div>
                </x-slot:data>
            </x-cards.catalog.card>
        </div>
    </div>

    <div class="mt-24">
        <x-filters.filter-bar :filters="$availableFilters" />
    </div>

    <div class="w-full sm:px-6 px-4 lg:px-0 leading-normal">
        <h3 class="font-encodesans text-esg5 mb-4 w-fit text-xl font-semibold px-4 lg:px-0">{{ __('Transactions History') }}</h3>
        
        <x-cards.card-dashboard-version1-withshadow contentplacement="none" class="!h-full" type="grid !mt-6">
            <x-tables.table>
                <x-slot name="thead">
                    <x-tables.th class="text-left text-esg6 border-b-[1px] border-y-esg61 w-[14%]" no_border>{{ __('Transaction ID') }}</x-tables.th>
                    <x-tables.th class="text-left text-esg6 border-b-[1px] border-y-esg61 w-[12%]" no_border>{{ __('Date') }}</x-tables.th>
                    <x-tables.th class="text-left text-esg6 border-b-[1px] border-y-esg61 w-[40%]" no_border>{{ __('Title') }}</x-tables.th>
                    <x-tables.th class="text-right text-esg6 border-b-[1px] border-y-esg61 w-[12%]" no_border>{{ __('Amount') }}</x-tables.th>
                    <x-tables.th class="text-center text-esg6 border-b-[1px] border-y-esg61 w-[12%]" no_border>{{ __('Confirmed?') }}</x-tables.th>
                    <x-tables.th class="text-center text-esg6 border-b-[1px] border-y-esg61 w-[10%]" no_border>{{ __('Actions') }}</x-tables.th>
                </x-slot>
                @if(count($transactions) > 0)
                @foreach($transactions as $transaction)
                    <x-tables.tr class="text-esg8 hover:bg-gray-100">
                        <x-tables.td class="text-esg8 uppercase">{{ \Illuminate\Support\Str::limit($transaction->uuid, 13, $end="") }}</x-tables.td>
                        <x-tables.td class="text-esg8 " title="{{ $transaction->created_at->format('Y-m-d H:i') }}">{{ $transaction->created_at->format('Y-m-d') }}</x-tables.td>
                        @if ($transaction->product)
                            <x-tables.td class="text-esg8 "><a href="{{ $transaction->product_url }}" class="underline">{{ $transaction->meta['description'] ?? '-' }}</a></x-tables.td>
                        @else
                            <x-tables.td class="text-esg8 ">{{ $transaction->meta['description'] ?? '-' }}</x-tables.td>
                        @endif
                        <x-tables.td class="text-right {{ ($transaction->type === 'deposit') ? 'text-[#0D9401]' : 'text-[#F44336]' }}">{{ formatToCurrency($transaction->amountFloat, false, 'EUR') }}</x-tables.td>
                        <x-tables.td class="text-center {{ $transaction->confirmed ? 'text-[#0D9401]' : 'text-[#F44336]' }}">{{ $transaction->confirmed ? __('Yes') : __('No') }}</x-tables.td>
                        <x-tables.td class="text-esg8 text-center">
                            @if (! $transaction->confirmed)
                                <x-buttons.edit modal="wallet.modals.add-funds" :data="json_encode(['transaction' => $transaction->id])" />
                            @endif
                        </x-tables.td>
                    </x-tables.tr>
                @endforeach
                @else
                <x-tables.tr class="text-esg8 hover:bg-gray-100">
                    <x-tables.td colspan="5" class="text-center">
                        {{ __('No Transactions Found') }}
                    </x-tables.td>
                </x-tables.tr>
                @endif
            </x-tables.table>
        </x-cards.card-dashboard-version1-withshadow>
        <div>
            {{ $transactions->links() }}
        </div>
    </div>
</div>
