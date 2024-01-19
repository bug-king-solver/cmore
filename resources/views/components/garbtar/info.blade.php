 {{-- GAR [STAOCK] ratio --}}
 <div class="mt-10 {{ $kpi == 'gar' && $stockflow == 'stock' && $ratio == 'ratio' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('GAR - Green Asset Ratio')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">

         <p class="text-base text-esg8">
             {{ __('The Green Asset Ratio (GAR) is the main official performance indicator for financial institutions and is calculated as the ratio between the') }}
             <span class="text-[#9CDAA5] font-extrabold ">{{ __('assets exposed to aligned activities') }} </span>
             {{ __('within the coverage universe and') }}
             <span
                 class="text-[#629E71] font-extrabold ">{{ __('the total bank assets not excluded from the ratios.') }}</span>
         </p>
     </x-cards.garbtar.card>
 </div>

 {{-- GAR [STAOCK] coverage --}}
 <div class="mt-10 {{ $kpi == 'gar' && $stockflow == 'stock' && $ratio == 'coverage' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('Coverage')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">

         <p class="text-base text-esg8">{{ __('The coverage is intended to express the') }}
             <span
                 class="text-[#9CDAA5] font-extrabold ">{{ __('total assets that could theoretically be included in the numerator of the GAR') }}
             </span>
             {{ __('and the') }}
             <span class="text-[#629E71] font-extrabold ">{{ __('total balance sheet') }}</span>
             {{ __('outside the bank. This leaves out assets which by their nature are not used for ratio calculations and assets which, although they are used for the denominator, cannot be included in the numerator of the GAR.') }}
         </p>
     </x-cards.garbtar.card>
 </div>

 {{-- GAR [STAOCK] eligibility --}}
 <div class="mt-10 {{ $kpi == 'gar' && $stockflow == 'stock' && $ratio == 'eligibility' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('Eligibility')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">
         <p class="text-base text-esg8">{{ __('This ratio conveys the relation between the') }}
             <span class="text-[#9CDAA5] font-extrabold ">{{ __('assets that are eligible') }} </span>
             {{ __('and the') }}
             <span
                 class="text-[#629E71] font-extrabold ">{{ __('total assets that would have the potential to be eligible') }}</span>
             {{ __(', eliminating from the ratio all those assets that are excluded by nature') }}
         </p>
     </x-cards.garbtar.card>
 </div>

 {{-- GAR [STAOCK] alignment --}}
 <div class="mt-10 {{ $kpi == 'gar' && $stockflow == 'stock' && $ratio == 'alignment' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('Alignment')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">
         <p class="text-base text-esg8">{{ __('This ratio conveys the relation between the') }}
             <span class="text-[#9CDAA5] font-extrabold ">{{ __('assets that are aligned') }} </span>
             {{ __('and the') }}
             <span
                 class="text-[#629E71] font-extrabold ">{{ __('total assets that would have the potential to be eligible or aligned') }}</span>
             {{ __(', eliminating from the ratio all those assets that are by nature excluded. It is a ratio that has the same numerator as the GAR, but uses a denominator that conveys a closer idea of the institution`s performance compared to the best possible.') }}
         </p>
     </x-cards.garbtar.card>
 </div>


 {{-- GAR [FLOW] ratio --}}
 <div class="mt-10 {{ $kpi == 'gar' && $stockflow == 'flow' && $ratio == 'ratio' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('GAR - Green Asset Ratio')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">

         <p class="text-base text-esg8">
             {{ __('The Green Asset Ratio (GAR) is the main official performance indicator for financial institutions and is calculated as the ratio between the') }}
             <span class="text-[#9CDAA5] font-extrabold ">{{ __('assets exposed to aligned activities') }} </span>
             {{ __('within the coverage universe and') }}
             <span
                 class="text-[#629E71] font-extrabold ">{{ __('the total bank assets not excluded from the ratios') }}</span>
             {{ __('In the "Flow" option, only new assets originated in the reporting period are taken into account.') }}
         </p>
     </x-cards.garbtar.card>
 </div>

 {{-- GAR [FLOW] coverage --}}
 <div class="mt-10 {{ $kpi == 'gar' && $stockflow == 'flow' && $ratio == 'coverage' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('Coverage')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">

         <p class="text-base text-esg8">{{ __('The coverage is intended to express the total relation between the') }}
             <span
                 class="text-[#9CDAA5] font-extrabold ">{{ __('total assets that could theoretically be included in the numerator of the GAR') }}
             </span>
             {{ __('and the') }}
             <span class="text-[#629E71] font-extrabold ">{{ __('total balance sheet') }}</span>
             {{ __('outside the bank. This leaves out assets which by their nature are not used for ratio calculations and assets which, although they are used for the denominator, cannot be included in the numerator of the GAR. In the "Flow" option, only new assets originated in the reporting period are considered in the numerator.') }}
         </p>
     </x-cards.garbtar.card>
 </div>

 {{-- GAR [FLOW] eligibility --}}
 <div class="mt-10 {{ $kpi == 'gar' && $stockflow == 'flow' && $ratio == 'eligibility' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('Eligibility')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">

         <p class="text-base text-esg8">{{ __('This ratio conveys the relation between the') }}
             <span class="text-[#9CDAA5] font-extrabold ">{{ __('assets that are eligible') }} </span>
             {{ __('and the') }}
             <span
                 class="text-[#629E71] font-extrabold ">{{ __('total assets that would have the potential to be eligible') }}</span>
             {{ __(', eliminating from the ratio all those assets that are excluded by nature. In the "Flow" option, only new assets originated in the reporting period are taken into account.') }}
         </p>
     </x-cards.garbtar.card>
 </div>

 {{-- GAR [FLOW] alignment --}}
 <div class="mt-10 {{ $kpi == 'gar' && $stockflow == 'flow' && $ratio == 'alignment' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('Alignment')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">

         <p class="text-base text-esg8">{{ __('This ratio conveys the relation between the') }}
             <span class="text-[#9CDAA5] font-extrabold ">{{ __('assets that are aligned') }} </span>
             {{ __('and the') }}
             <span
                 class="text-[#629E71] font-extrabold ">{{ __('total assets that would have the potential to be eligible or aligned') }}</span>
             {{ __(', eliminating from the ratio all those assets that are excluded by nature. In the "Flow" option, only new assets originated in the reporting period are taken into account.') }}
         </p>
     </x-cards.garbtar.card>
 </div>







 {{-- BITAR [STAOCK] --}}
 <div class="mt-10 {{ $kpi == 'btar' && $stockflow == 'stock' && $ratio == 'ratio' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('BTAR - The Banking book taxonomy alignment ratio')]) }}"
         class="!h-auto" type="grid" contentplacement="none" nolist="true">

         <p class="text-base text-esg8">
             {{ __('The Banking book taxonomy alignment ratio (BTAR) is an official environmental performance indicator for financial institutions that has a rationale equivalent to the GAR, but with a much wider range of assets eligible for the numerator of the ratio, including exposures to SMEs.') }}
         </p>
     </x-cards.garbtar.card>
 </div>

 {{-- BITAR [STAOCK] coverage --}}
 <div class="mt-10 {{ $kpi == 'btar' && $stockflow == 'stock' && $ratio == 'coverage' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('Coverage')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">

         <p class="text-base text-esg8">{{ __('The coverage is intended to express the total relation between the') }}
             <span
                 class="text-[#9CDAA5] font-extrabold ">{{ __('total number of assets that could theoretically be included in the BTAR') }}
             </span>
             {{ __('numerator and the') }}
             <span class="text-[#629E71] font-extrabold ">{{ __('total balance sheet outside the bank.') }}</span>
             {{ __('This excludes assets which by their nature are not used for ratio calculations and assets which, although they are used for the denominator, cannot be included in the BTAR numerator.') }}
         </p>
     </x-cards.garbtar.card>
 </div>

 {{-- BITAR [STAOCK] eligibility --}}
 <div class="mt-10 {{ $kpi == 'btar' && $stockflow == 'stock' && $ratio == 'eligibility' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('Eligibility')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">

         <p class="text-base text-esg8">{{ __('This ratio conveys the relation between the') }}
             <span class="text-[#9CDAA5] font-extrabold ">{{ __('assets that are eligible') }} </span>
             {{ __('and the') }}
             <span
                 class="text-[#629E71] font-extrabold ">{{ __('total assets that would have the potential to be eligible') }}</span>
             {{ __(', eliminating from the ratio all those assets that are excluded by nature') }}
         </p>
     </x-cards.garbtar.card>
 </div>

 {{-- BITAR [STAOCK] alignment --}}
 <div class="mt-10 {{ $kpi == 'btar' && $stockflow == 'stock' && $ratio == 'alignment' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('Alignment')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">

         <p class="text-base text-esg8">{{ __('This ratio conveys the relation between the') }}
             <span class="text-[#9CDAA5] font-extrabold ">{{ __('assets that are aligned') }} </span>
             {{ __('and the') }}
             <span
                 class="text-[#629E71] font-extrabold ">{{ __('total assets that would have the potential to be eligible or aligned') }}</span>
             {{ __(', eliminating from the ratio all those assets that are by nature excluded. This ratio has the same numerator as BTAR, but uses a denominator that gives a closer idea of the institution`s performance compared to the best possible.') }}
         </p>
     </x-cards.garbtar.card>
 </div>





 {{-- BITAR [FLOW] --}}
 <div class="mt-10 {{ $kpi == 'btar' && $stockflow == 'flow' && $ratio == 'ratio' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('BTAR - The Banking book taxonomy alignment ratio')]) }}"
         class="!h-auto" type="grid" contentplacement="none" nolist="true">

         <p class="text-base text-esg8">
             {{ __('The Banking book taxonomy alignment ratio (BTAR) is an official environmental performance indicator for financial institutions that has a rationale equivalent to the GAR, but with a much wider range of assets eligible for the numerator of the ratio, including exposures to SMEs.  In the "Flow" option, only new assets originated in the reporting period are taken into account.') }}
         </p>
     </x-cards.garbtar.card>
 </div>

 {{-- BITAR [FLOW] coverage --}}
 <div class="mt-10 {{ $kpi == 'btar' && $stockflow == 'flow' && $ratio == 'coverage' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('Coverage')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">

         <p class="text-base text-esg8">{{ __('The coverage is intended to express the total relation between the') }}
             <span
                 class="text-[#9CDAA5] font-extrabold ">{{ __('total number of assets that could theoretically be included in the BTAR') }}
             </span>
             {{ __('numerator and the') }}
             <span class="text-[#629E71] font-extrabold ">{{ __('total balance sheet outside the bank.') }}</span>
             {{ __('This excludes assets which by their nature are not used for ratio calculations and assets which, although they are used for the denominator, cannot be included in the BTAR numerator. In the "Flow" option, only new assets originated in the reporting period are considered in the numerator.') }}
         </p>
     </x-cards.garbtar.card>
 </div>

 {{-- BITAR [FLOW] eligibility --}}
 <div class="mt-10 {{ $kpi == 'btar' && $stockflow == 'flow' && $ratio == 'eligibility' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('Eligibility')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">

         <p class="text-base text-esg8">{{ __('This ratio conveys the relation between the') }}
             <span class="text-[#9CDAA5] font-extrabold ">{{ __('assets that are eligible') }} </span>
             {{ __('and the') }}
             <span
                 class="text-[#629E71] font-extrabold ">{{ __('total assets that would have the potential to be eligible') }}</span>
             {{ __(', eliminating from the ratio all those assets that are excluded by nature. In the "Flow" option, only new assets originated in the reporting period are taken into account.') }}
         </p>
     </x-cards.garbtar.card>
 </div>

 {{-- BITAR [FLOW] alignment --}}
 <div class="mt-10 {{ $kpi == 'btar' && $stockflow == 'flow' && $ratio == 'alignment' ? '' : 'hidden' }}">
     <x-cards.garbtar.card text="{{ json_encode([__('Alignment')]) }}" class="!h-auto" type="grid"
         contentplacement="none" nolist="true">

         <p class="text-base text-esg8">{{ __('This ratio conveys the relation between the') }}
             <span class="text-[#9CDAA5] font-extrabold ">{{ __('assets that are aligned') }} </span>
             {{ __('and the') }}
             <span
                 class="text-[#629E71] font-extrabold ">{{ __('total assets that would have the potential to be eligible or aligned') }}</span>
             {{ __(', eliminating from the ratio all those assets that are by nature excluded. This ratio has the same numerator as BTAR, but uses a denominator that gives a closer idea of the institution`s performance compared to the best possible. In the "Flow" option, only new assets originated in the reporting period are taken into account.') }}
         </p>
     </x-cards.garbtar.card>
 </div>
