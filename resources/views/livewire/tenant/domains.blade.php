<div>
    <div class="bg-esg4 overflow-hidden shadow sm:rounded-md">
        <ul>
            @foreach(tenant()->domains as $domain)
            <li
            @if(! $loop->first)
            class="border-t border-gray-200"
            @endif>
            <div class="transition duration-150 ease-in-out hover:bg-gray-50 focus:bg-gray-50 focus:outline-none">
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="text-base font-medium text-esg8">
                            {{ $domain->domain }}
                        </div>
                        <div class="flex">
                          @if($domain->is_fallback)
                          <div class="ml-2 flex items-center">
                              <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-800">
                                  {{ __('Fallback') }}
                              </span>
                          </div>
                          @else
                            @if($domain->certificate_status === 'issued')
                                <div class="mr-2 flex items-center">
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                        {{ __('Certificate issued') }}
                                    </span>
                                </div>
                                <span class="rounded-md shadow-sm">
                                    <button type="button" wire:click="revokeCertificate({{ $domain->id }})" class="bg-esg4 focus:shadow-outline-esg6 items-center rounded border border-gray-300 px-2.5 py-1.5 text-xs font-medium leading-4 text-gray-700 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:outline-none active:bg-gray-50 active:text-gray-800">
                                        {{ __('Revoke certificate') }}
                                    </button>
                                </span>
                            @elseif($domain->certificate_status === 'pending')
                                <div class="mr-2 flex items-center">
                                    <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-800">
                                        {{ __('Pending') }}
                                    </span>
                                </div>
                            @else
                                @if($domain->certificate_status === 'revoked')
                                    <div class="mr-2 flex items-center">
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                            {{ __('Certificate revoked') }}
                                        </span>
                                    </div>
                                @endif
                                <span class="rounded-md shadow-sm">
                                    <button type="button" wire:click="requestCertificate({{ $domain->id }})" class="bg-esg4 focus:shadow-outline-esg6 items-center rounded border border-gray-300 px-2.5 py-1.5 text-xs font-medium leading-4 text-gray-700 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:outline-none active:bg-gray-50 active:text-gray-800">
                                        {{ __('Request certificate') }}
                                    </button>
                                </span>
                            @endif
                          @endif
                          @if($domain->is_primary)
                          <div class="ml-2 flex items-center">
                              <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                  {{ __('Primary') }}
                              </span>
                          </div>
                          @else
                          <div class="ml-2 flex">
                              <span class="rounded-md shadow-sm">
                                  <button type="button" wire:click="makePrimary({{ $domain->id }})" class="bg-esg4 focus:shadow-outline-esg6 items-center rounded border border-gray-300 px-2.5 py-1.5 text-xs font-medium leading-4 text-gray-700 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:outline-none active:bg-gray-50 active:text-gray-800">
                                      {{ __('Make primary') }}
                                  </button>
                              </span>
                              @if(! $domain->is_fallback)
                              <span class="ml-2 rounded-md shadow-sm">
                                  <button id="delete_{{ $domain->id }}" name="delete_{{ $domain->id }}" type="button" wire:click="delete({{ $domain->id }})" class="bg-esg4 focus:shadow-outline-esg6 inline-flex items-center rounded border border-gray-300 px-2.5 py-1.5 text-xs font-medium leading-4 text-red-700 transition duration-150 ease-in-out hover:text-red-500 focus:border-blue-300 focus:outline-none active:bg-gray-50 active:text-gray-800">
                                      {{ __('Delete') }}
                                  </button>
                              </span>
                              @endif
                          </div>
                          @endif
                        </div>
                    </div>
                    <div class="mt-2 sm:flex sm:justify-between">
                        <div class="">
                            <div class="mr-6 flex items-center text-sm text-esg8">
                                <svg class="h-5 w-5 text-esg8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <div class="ml-1">
                                    {{ ucfirst($domain->type) }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-esg8 sm:mt-0">
                            <svg class="h-5 w-5 text-esg8" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-1">
                                {{ __('Added on') }}
                                <time datetime="{{ $domain->created_at->format('Y-m-d') }}">
                                    {{ $domain->created_at->format('M d, Y') }}
                                </time>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
    </div>
</div>
