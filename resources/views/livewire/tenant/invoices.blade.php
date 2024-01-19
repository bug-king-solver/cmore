<div class="mt-8">
    <h3 class="text-lg font-medium text-esg29">Invoices</h3>
    <div class="bg-esg4 mt-2 overflow-hidden shadow sm:rounded-md">
        @if($invoices = tenant()->invoicesIncludingPending()->all())
        <ul x-data>
            @foreach($invoices as $invoice)
            <li
            @if(! $loop->first)
            class="border-t border-gray-200"
            @endif>
            <div class="block transition duration-150 ease-in-out hover:bg-gray-50 focus:bg-gray-50 focus:outline-none">
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="truncate text-sm font-medium leading-5 text-indigo-600">
                            {{ $invoice->number }}
                        </div>
                        <div class="flex flex-shrink-0">
                            @if($invoice->asStripeInvoice()->paid)
                            <div class="ml-2 flex flex-shrink-0 items-center">
                                <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                    Paid
                                </span>
                            </div>
                            @else
                            <div class="ml-2 flex flex-shrink-0 items-center">
                                <span class="inline-flex rounded-full bg-orange-100 px-2 text-xs font-semibold leading-5 text-orange-800">
                                    Pending
                                </span>
                            </div>
                            @endif
                            <span class="inline-flex rounded-md shadow-sm">
                                <button type="button" @click="window.open('{{ route('tenant.invoice.download', ['id' => $invoice->id]) }}', '_blank')" class="bg-esg4 focus:shadow-outline-esg6 ml-2 inline-flex items-center rounded border border-gray-300 px-2.5 py-1.5 text-xs font-medium leading-4 text-gray-700 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:outline-none active:bg-gray-50 active:text-gray-800">
                                    Download
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="mt-2 sm:flex sm:justify-between">
                        <div class="sm:flex">
                            <div class="mr-6 flex items-center text-sm leading-5 text-gray-500">
                                <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                {{ $invoice->total() }}
                            </div>
                        </div>
                        <div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mt-0">
                            <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span>
                                Issued on
                                <time datetime="{{ $invoice->date()->format('Y-m-d') }}">
                                    {{ $invoice->date()->format('M d, Y') }}
                                </time>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        @endforeach
        </ul>
        @else
        <p class="p-4 text-sm font-medium text-esg29">
            No invoices issued yet.
        </p>
        @endif
</div>
</div>
