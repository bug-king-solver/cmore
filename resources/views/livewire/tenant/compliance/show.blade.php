@push('head')
    <x-comments::styles />
@endpush

<div>
    <x-slot name="header">
        <x-header title="{{ __('Compliance') }}" leftarrow="<svg class='mr-4' width='16' height='16' viewBox='0 0 16 16' fill='none' xmlns='http://www.w3.org/2000/svg'>
<path fill-rule='evenodd' clip-rule='evenodd' d='M11.3536 1.64645C11.5488 1.84171 11.5488 2.15829 11.3536 2.35355L5.70711 8L11.3536 13.6464C11.5488 13.8417 11.5488 14.1583 11.3536 14.3536C11.1583 14.5488 10.8417 14.5488 10.6464 14.3536L4.64645 8.35355C4.45118 8.15829 4.45118 7.84171 4.64645 7.64645L10.6464 1.64645C10.8417 1.45118 11.1583 1.45118 11.3536 1.64645Z' fill='white'/>
</svg>" >
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>
</div>

<div class="mx-auto max-w-7xl sm:px-6 px-4 lg:px-0  ">

    <div class="flex shadow-md mb-8 items-center bg-white border-l-4 border-esg6 text-esg39 text-sm py-2.5 px-6 relative" role="alert">
        <svg class="mr-4" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 15C4.13401 15 1 11.866 1 8C1 4.13401 4.13401 1 8 1C11.866 1 15 4.13401 15 8C15 11.866 11.866 15 8 15ZM8 16C12.4183 16 16 12.4183 16 8C16 3.58172 12.4183 0 8 0C3.58172 0 0 3.58172 0 8C0 12.4183 3.58172 16 8 16Z" fill="#153A5B"/>
            <path d="M8.9307 6.58789L6.63969 6.875L6.55766 7.25586L7.00883 7.33789C7.3018 7.4082 7.36039 7.51367 7.29594 7.80664L6.55766 11.2754C6.3643 12.1719 6.66313 12.5938 7.36625 12.5938C7.91117 12.5938 8.54398 12.3418 8.83109 11.9961L8.91898 11.5801C8.71977 11.7559 8.4268 11.8262 8.23344 11.8262C7.95805 11.8262 7.85844 11.6328 7.92875 11.293L8.9307 6.58789Z" fill="#153A5B"/>
            <path d="M9 4.5C9 5.05228 8.55229 5.5 8 5.5C7.44772 5.5 7 5.05228 7 4.5C7 3.94772 7.44772 3.5 8 3.5C8.55229 3.5 9 3.94772 9 4.5Z" fill="#153A5B"/>
        </svg>

        <span class="block sm:inline">Your report has been sent, we'll analyze the document based on what you described.</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.82263 7L11.4976 3.33083C11.6075 3.22099 11.6692 3.07201 11.6692 2.91667C11.6692 2.76133 11.6075 2.61235 11.4976 2.5025C11.3878 2.39266 11.2388 2.33095 11.0835 2.33095C10.9281 2.33095 10.7791 2.39266 10.6693 2.5025L7.00013 6.1775L3.33096 2.5025C3.22112 2.39266 3.07214 2.33095 2.91679 2.33095C2.76145 2.33095 2.61247 2.39266 2.50263 2.5025C2.39278 2.61235 2.33107 2.76133 2.33107 2.91667C2.33107 3.07201 2.39278 3.22099 2.50263 3.33083L6.17763 7L2.50263 10.6692C2.44795 10.7234 2.40456 10.7879 2.37494 10.859C2.34533 10.9301 2.33008 11.0063 2.33008 11.0833C2.33008 11.1603 2.34533 11.2366 2.37494 11.3077C2.40456 11.3788 2.44795 11.4433 2.50263 11.4975C2.55686 11.5522 2.62137 11.5956 2.69246 11.6252C2.76354 11.6548 2.83979 11.67 2.91679 11.67C2.9938 11.67 3.07005 11.6548 3.14113 11.6252C3.21221 11.5956 3.27673 11.5522 3.33096 11.4975L7.00013 7.8225L10.6693 11.4975C10.7235 11.5522 10.788 11.5956 10.8591 11.6252C10.9302 11.6548 11.0065 11.67 11.0835 11.67C11.1605 11.67 11.2367 11.6548 11.3078 11.6252C11.3789 11.5956 11.4434 11.5522 11.4976 11.4975C11.5523 11.4433 11.5957 11.3788 11.6253 11.3077C11.6549 11.2366 11.6702 11.1603 11.6702 11.0833C11.6702 11.0063 11.6549 10.9301 11.6253 10.859C11.5957 10.7879 11.5523 10.7234 11.4976 10.6692L7.82263 7Z" fill="#181818"/>
                </svg>

        </span>
    </div>



    <div class="text-esg5 text-sm font-bold">
        Working Conditions and Human Rights policy
        <span class="ml-1 text-xs inline-flex items-center p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg32 text-esg8 rounded">
            <i class="inline-flex justify-center items-center mr-2 w-[7px] h-[7px] bg-esg33 rounded-full"></i> Low
        </span>
    </div>
    <p class="mt-1 font-xs text-esg8">Formal policy covering working conditions and human rights</p>
    
    <!-- Accordian -->
    
    <div id="accordion-flush" data-accordion="collapse" data-active-classes="bg-esg37" data-inactive-classes="" class="mt-6 mb-36">

        <div id="accordion-flush-heading-1">
            <div class="flex items-center justify-between w-full cursor-pointer text-xs font-medium	px-2 py-4 text-left text-esg8 border-b border-esg38 hover:bg-esg37" data-accordion-target="#accordion-flush-body-1" aria-expanded="true" aria-controls="accordion-flush-body-1">
                
                <div class="w-full md:w-1/2 ">
                Child labour and Young Workers
                </div>
                <div class="w-full md:w-1/5   inline-flex items-center">
                    <svg class="mr-2" width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.3746 10.0629H12.6519L12.3957 9.81589C13.2922 8.77301 13.832 7.4191 13.832 5.94625C13.832 2.66209 11.1699 0 7.88573 0C4.75708 0 2.19562 2.41509 1.96692 5.48885H3.81483C4.04353 3.43053 5.76337 1.82962 7.88573 1.82962C10.1636 1.82962 12.0024 3.66838 12.0024 5.94625C12.0024 8.22413 10.1636 10.0629 7.88573 10.0629C7.73021 10.0629 7.58384 10.0354 7.42833 10.0172V11.8651C7.58384 11.8834 7.73021 11.8925 7.88573 11.8925C9.35857 11.8925 10.7125 11.3528 11.7554 10.4563L12.0024 10.7124V11.4351L16.5764 16L17.9395 14.6369L13.3746 10.0629Z" fill="#0D9401"/>
                        <path d="M13.3746 10.0629H12.6519L12.3957 9.81589C13.2922 8.77301 13.832 7.4191 13.832 5.94625C13.832 2.66209 11.1699 0 7.88573 0C4.75708 0 2.19562 2.41509 1.96692 5.48885H3.81483C4.04353 3.43053 5.76337 1.82962 7.88573 1.82962C10.1636 1.82962 12.0024 3.66838 12.0024 5.94625C12.0024 8.22413 10.1636 10.0629 7.88573 10.0629C7.73021 10.0629 7.58384 10.0354 7.42833 10.0172V11.8651C7.58384 11.8834 7.73021 11.8925 7.88573 11.8925C9.35857 11.8925 10.7125 11.3528 11.7554 10.4563L12.0024 10.7124V11.4351L16.5764 16L17.9395 14.6369L13.3746 10.0629Z" fill="#0D9401"/>
                        <path d="M2.60278 11.1029L0.706246 9.23713L0.0604248 9.86801L2.60278 12.3691L8.06042 7L7.41915 6.36913L2.60278 11.1029Z" fill="#0D9401"/>
                    </svg>
                    3 snippets found
                </div>
                <div class="w-full md:w-1/2  ">
                    
                </div>
                <div class="">
                        <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
        </div>

        <div id="accordion-flush-body-1" class="hidden" aria-labelledby="accordion-flush-heading-1">
            <div class="py-3 px-4 text-xs text-esg8 bg-esg37 border-b border-esg38 dark:border-esg38">
                <p class="mb-4">
                    <strong>Description: </strong>
                    Work that deprives children of their childhood, their potential and their dignity, and that is harmful to physical and mental development.
                </p>
                <p class="mb-4">
                    <strong>Keywords: </strong>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">child labour</span>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">young workers</span>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">young labour</span>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">underage work</span>
                </p>
                <div class="flex">
                    <div class="mr-3"><strong>Snippets:</strong></div>
                    <div class="w-full text-esg35">
                        <p>
                            “... loren ipsum child labour dolor sit amet consectetur adipiscing ...”
                            <span class="mx-2">|</span>
                            Page 13
                        </p>
                        <p>
                            “... loren ipsum child labour dolor sit amet consectetur adipiscing ...”
                            <span class="mx-2">|</span>
                            Page 13
                        </p>
                        <p>
                            “... loren ipsum child labour dolor sit amet consectetur adipiscing ...”
                            <span class="mx-2">|</span>
                            Page 13
                        </p>
                        <p>
                            “... loren ipsum child labour dolor sit amet consectetur adipiscing ...”
                            <span class="mx-2">|</span>
                            Page 13
                        </p>
                    </div>
                </div>
            </div>
        </div>


        <div id="accordion-flush-heading-2">
            <div class="flex items-center justify-between w-full cursor-pointer text-xs font-medium	px-2 py-4 text-left text-esg8 border-b border-esg38 hover:bg-esg37" data-accordion-target="#accordion-flush-body-2" aria-expanded="false" aria-controls="accordion-flush-body-2">
                
                <div class="w-full md:w-1/2 ">
                Wages and benefits
                </div>
                <div class="w-full md:w-1/5   inline-flex items-center">
                <svg class="mr-2" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M7.96502 0.0436913C7.26891 0.193747 6.68802 0.785147 6.53759 1.497C6.41636 2.07081 6.5906 2.69163 6.99809 3.13748C7.20569 3.36464 7.45273 3.52337 7.77588 3.63725C8.00852 3.71924 8.08594 3.73141 8.37185 3.73107C8.64817 3.73075 8.74027 3.71699 8.95079 3.64457C9.69816 3.38755 10.1801 2.76533 10.2372 1.98388C10.303 1.08241 9.65541 0.229298 8.76302 0.0419388C8.49389 -0.0145474 8.23259 -0.0139842 7.96502 0.0436913ZM3.37327 2.13446C2.74735 2.29416 2.23193 2.8364 2.11101 3.46234C1.9142 4.48104 2.71374 5.44803 3.75593 5.45179C4.26052 5.45363 4.68431 5.26339 5.02457 4.88235C5.60931 4.22746 5.58612 3.23289 4.97159 2.61092C4.77375 2.4107 4.55203 2.26565 4.31923 2.18413C4.08058 2.10054 3.60416 2.07554 3.37327 2.13446ZM12.7349 3.27577C12.4276 3.4196 12.3253 3.79097 12.5126 4.08307C12.612 4.23813 12.87 4.35902 13.043 4.33167C13.3183 4.28817 13.5354 4.04069 13.5354 3.77034C13.5354 3.6155 13.4367 3.4293 13.2987 3.32409C13.1507 3.21121 12.916 3.19108 12.7349 3.27577ZM1.65922 6.90049C0.995684 7.00448 0.495726 7.49799 0.388386 8.15486C0.271596 8.86972 0.726427 9.59972 1.41925 9.80942C1.67098 9.88562 2.1055 9.87887 2.33984 9.79509C2.95712 9.57447 3.34379 9.0301 3.34379 8.38175C3.34379 7.58728 2.75496 6.95451 1.96518 6.90034C1.84855 6.89232 1.71085 6.89239 1.65922 6.90049ZM14.6931 7.65991C14.3482 7.76484 14.1465 8.03228 14.1488 8.38175C14.1517 8.8152 14.4509 9.11485 14.8811 9.11485C15.5208 9.11485 15.8618 8.39016 15.4489 7.90782C15.2604 7.68761 14.9436 7.58371 14.6931 7.65991ZM3.52461 11.7015C3.2935 11.7469 3.01173 11.8987 2.8468 12.0666C2.33533 12.5873 2.33442 13.3899 2.84471 13.9002C3.04255 14.098 3.1866 14.1813 3.44862 14.2496C4.18899 14.4424 4.93219 13.9451 5.05643 13.1737C5.16653 12.4901 4.6696 11.8123 3.975 11.6987C3.7611 11.6637 3.71547 11.664 3.52461 11.7015ZM12.776 12.0755C12.2766 12.2004 11.9655 12.6759 12.0659 13.1611C12.2182 13.8973 13.103 14.1685 13.6308 13.6407C13.9905 13.281 13.9992 12.7066 13.6503 12.3477C13.4203 12.111 13.0702 12.002 12.776 12.0755ZM8.15419 13.7973C7.44832 13.9422 7.06105 14.6957 7.35534 15.3516C7.46034 15.5855 7.67764 15.8043 7.91075 15.9108C8.07643 15.9865 8.14593 16 8.37185 16C8.71076 16 8.95667 15.898 9.17154 15.6683C9.82202 14.9729 9.41103 13.8772 8.46516 13.7853C8.37081 13.7761 8.23086 13.7815 8.15419 13.7973Z" fill="#8A8A8A"/>
</svg>


                    Snippet not found
                </div>
                <div class="w-full md:w-1/2  ">
                    
                </div>
                <div class="">
                        <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
        </div>
        
        <div id="accordion-flush-body-2" class="hidden" aria-labelledby="accordion-flush-heading-2">
            <div class="py-3 px-4 text-xs text-esg8 bg-esg37 border-b border-esg38 dark:border-esg38">
                <p class="mb-4">
                    <strong>Description: </strong>Wages and benefits means wages and salaries, the employer's share of FICA taxes, Medicare taxes, state and federal unemployment taxes, workers' compensation, mileage reimbursement, health and dental insurance, life insurance, disability insurance, long-term care insurance, uniform allowance, and contributions to employee retirement accounts.
                </p>
                <p class="mb-4">
                    <strong>Keywords: </strong>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">wages</span>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">salary</span>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">insurance</span>
                </p>
                <div class="flex">
                    <div class="mr-3"><strong>Snippets:</strong></div>
                    <div class="w-full text-esg35">
                        <p>
                            “Not found”
                            <span class="mx-2">|</span>
                            Click on “create task” button and assign a person responsible to fix this issue
                        </p>
                        
                    </div>
                </div>
            </div>
        </div>




        <div id="accordion-flush-heading-3">
            <div class="flex items-center justify-between w-full cursor-pointer text-xs font-medium	px-2 py-4 text-left text-esg8 border-b border-esg38 hover:bg-esg37" data-accordion-target="#accordion-flush-body-3" aria-expanded="false" aria-controls="accordion-flush-body-3">
                
                <div class="w-full md:w-1/2 ">
                Working hours
                </div>
                <div class="w-full md:w-1/5   inline-flex items-center">
                    <svg class="mr-2" width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.3746 10.0629H12.6519L12.3957 9.81589C13.2922 8.77301 13.832 7.4191 13.832 5.94625C13.832 2.66209 11.1699 0 7.88573 0C4.75708 0 2.19562 2.41509 1.96692 5.48885H3.81483C4.04353 3.43053 5.76337 1.82962 7.88573 1.82962C10.1636 1.82962 12.0024 3.66838 12.0024 5.94625C12.0024 8.22413 10.1636 10.0629 7.88573 10.0629C7.73021 10.0629 7.58384 10.0354 7.42833 10.0172V11.8651C7.58384 11.8834 7.73021 11.8925 7.88573 11.8925C9.35857 11.8925 10.7125 11.3528 11.7554 10.4563L12.0024 10.7124V11.4351L16.5764 16L17.9395 14.6369L13.3746 10.0629Z" fill="#0D9401"/>
                        <path d="M13.3746 10.0629H12.6519L12.3957 9.81589C13.2922 8.77301 13.832 7.4191 13.832 5.94625C13.832 2.66209 11.1699 0 7.88573 0C4.75708 0 2.19562 2.41509 1.96692 5.48885H3.81483C4.04353 3.43053 5.76337 1.82962 7.88573 1.82962C10.1636 1.82962 12.0024 3.66838 12.0024 5.94625C12.0024 8.22413 10.1636 10.0629 7.88573 10.0629C7.73021 10.0629 7.58384 10.0354 7.42833 10.0172V11.8651C7.58384 11.8834 7.73021 11.8925 7.88573 11.8925C9.35857 11.8925 10.7125 11.3528 11.7554 10.4563L12.0024 10.7124V11.4351L16.5764 16L17.9395 14.6369L13.3746 10.0629Z" fill="#0D9401"/>
                        <path d="M2.60278 11.1029L0.706246 9.23713L0.0604248 9.86801L2.60278 12.3691L8.06042 7L7.41915 6.36913L2.60278 11.1029Z" fill="#0D9401"/>
                    </svg>
                    7 snippets found
                </div>
                <div class="w-full md:w-1/2  ">
                    <button class="text-xs text-esg6 bg-transparent font-medium py-1.5 px-3 border border-esg6 rounded hover:bg-esg6 hover:text-white hover:border-transparent" data-modal-toggle="createtask-modal">Create Task</button>
                </div>
                <div class="">
                        <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
        </div>
        
        <div id="accordion-flush-body-3" class="hidden" aria-labelledby="accordion-flush-heading-3">
            <div class="py-3 px-4 text-xs text-esg8 bg-esg37 border-b border-esg38 dark:border-esg38">
                <p class="mb-4">
                    <strong>Description: </strong>
                    Work that deprives children of their childhood, their potential and their dignity, and that is harmful to physical and mental development.
                </p>
                <p class="mb-4">
                    <strong>Keywords: </strong>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">child labour</span>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">young workers</span>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">young labour</span>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">underage work</span>
                </p>
                <div class="flex">
                    <div class="mr-3"><strong>Snippets:</strong></div>
                    <div class="w-full text-esg35">
                        <p>
                            “... loren ipsum child labour dolor sit amet consectetur adipiscing ...”
                            <span class="mx-2">|</span>
                            Page 13
                        </p>
                        <p>
                            “... loren ipsum child labour dolor sit amet consectetur adipiscing ...”
                            <span class="mx-2">|</span>
                            Page 13
                        </p>
                        <p>
                            “... loren ipsum child labour dolor sit amet consectetur adipiscing ...”
                            <span class="mx-2">|</span>
                            Page 13
                        </p>
                        <p>
                            “... loren ipsum child labour dolor sit amet consectetur adipiscing ...”
                            <span class="mx-2">|</span>
                            Page 13
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div id="accordion-flush-heading-4">
            <div class="flex items-center justify-between w-full cursor-pointer text-xs font-medium	px-2 py-4 text-left text-esg8 border-b border-esg38 hover:bg-esg37" data-accordion-target="#accordion-flush-body-4" aria-expanded="false" aria-controls="accordion-flush-body-4">
                
                <div class="w-full md:w-1/2 ">
                Wages and benefits
                </div>
                <div class="w-full md:w-1/5   inline-flex items-center">
                <svg class="mr-2" width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.4322 10.0629H12.7095L12.4534 9.81589C13.3499 8.77301 13.8896 7.4191 13.8896 5.94625C13.8896 2.66209 11.2275 0 7.94335 0C4.8147 0 2.25324 2.41509 2.02453 5.48885H3.87245C4.10115 3.43053 5.82099 1.82962 7.94335 1.82962C10.2212 1.82962 12.06 3.66838 12.06 5.94625C12.06 8.22413 10.2212 10.0629 7.94335 10.0629C7.78783 10.0629 7.64146 10.0354 7.48594 10.0172V11.8651C7.64146 11.8834 7.78783 11.8925 7.94335 11.8925C9.41619 11.8925 10.7701 11.3528 11.813 10.4563L12.06 10.7124V11.4351L16.634 16L17.9971 14.6369L13.4322 10.0629Z" fill="#F44336"/>
<path d="M5.17148 7.1538L2.9119 9.41338L0.652322 7.1538L0.00280762 7.80332L2.26238 10.0629L0.00280762 12.3225L0.652322 12.972L2.9119 10.7124L5.17148 12.972L5.82099 12.3225L3.56141 10.0629L5.82099 7.80332L5.17148 7.1538Z" fill="#F44336"/>
</svg>

                    Snippet not found
                </div>
                <div class="w-full md:w-1/2  ">
                    <button class="text-xs text-esg6 bg-transparent font-medium py-1.5 px-3 border border-esg6 rounded hover:bg-esg6 hover:text-white hover:border-transparent" data-modal-toggle="createtask-modal">Create Task</button>
                </div>
                <div class="">
                        <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
        </div>
        
        <div id="accordion-flush-body-4" class="hidden" aria-labelledby="accordion-flush-heading-4">
            <div class="py-3 px-4 text-xs text-esg8 bg-esg37 border-b border-esg38 dark:border-esg38">
                <p class="mb-4">
                    <strong>Description: </strong>
                    Work that deprives children of their childhood, their potential and their dignity, and that is harmful to physical and mental development.
                </p>
                <p class="mb-4">
                    <strong>Keywords: </strong>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">child labour</span>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">young workers</span>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">young labour</span>
                    <span class="text-xs mr-2 inline-block p-1.5 leading-none text-center whitespace-nowrap align-baseline bg-esg6 text-white rounded">underage work</span>
                </p>
                <div class="flex">
                    <div class="mr-3"><strong>Snippets:</strong></div>
                    <div class="w-full text-esg35">
                        <p>
                            “... loren ipsum child labour dolor sit amet consectetur adipiscing ...”
                            <span class="mx-2">|</span>
                            Page 13
                        </p>
                        <p>
                            “... loren ipsum child labour dolor sit amet consectetur adipiscing ...”
                            <span class="mx-2">|</span>
                            Page 13
                        </p>
                        <p>
                            “... loren ipsum child labour dolor sit amet consectetur adipiscing ...”
                            <span class="mx-2">|</span>
                            Page 13
                        </p>
                        <p>
                            “... loren ipsum child labour dolor sit amet consectetur adipiscing ...”
                            <span class="mx-2">|</span>
                            Page 13
                        </p>
                    </div>
                </div>
            </div>
        </div>

    
    </div>
    <div class="flex items-center justify-end text-[10px]">
        <svg class="mr-2" width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.08756 0.870597C7.10244 0.862328 7.12104 0.857143 7.14167 0.857143C7.1623 0.857143 7.18089 0.862328 7.19578 0.870597C7.20863 0.87774 7.22562 0.890793 7.24268 0.91982L13.1201 10.9198C13.1506 10.9718 13.15 11.0263 13.1219 11.077C13.1078 11.1024 13.0902 11.1192 13.075 11.1286C13.0624 11.1364 13.0461 11.1429 13.0191 11.1429H1.26428C1.23723 11.1429 1.22095 11.1364 1.20833 11.1286C1.19317 11.1192 1.17554 11.1024 1.16144 11.077C1.13332 11.0263 1.1327 10.9718 1.16327 10.9198L7.04066 0.91982C7.05772 0.890793 7.07471 0.87774 7.08756 0.870597ZM7.98164 0.485504C7.60117 -0.161835 6.68217 -0.161835 6.3017 0.485504L0.424311 10.4855C0.0325686 11.152 0.502871 12 1.26428 12H13.0191C13.7805 12 14.2508 11.152 13.859 10.4855L7.98164 0.485504Z" fill="#444444"/>
            <path d="M6.28452 9.42857C6.28452 8.95518 6.66828 8.57143 7.14167 8.57143C7.61505 8.57143 7.99881 8.95518 7.99881 9.42857C7.99881 9.90196 7.61505 10.2857 7.14167 10.2857C6.66828 10.2857 6.28452 9.90196 6.28452 9.42857Z" fill="#444444"/>
            <path d="M6.36849 4.28146C6.32283 3.82482 6.68142 3.42857 7.14035 3.42857C7.59927 3.42857 7.95786 3.82481 7.9122 4.28146L7.61156 7.28784C7.58735 7.52993 7.38364 7.71429 7.14035 7.71429C6.89705 7.71429 6.69334 7.52993 6.66913 7.28784L6.36849 4.28146Z" fill="#444444"/>
        </svg>  Found something wrong with the analysis?
        <button class="text-esg8 bg-transparent font-medium p-1.5 ml-2 border border-esg8 rounded" data-modal-toggle="reportissue-modal">Report an issue</button>
    </div>
</div>

<div id="createtask-modal" data-modal-backdropClasses="bg-white bg-opacity-25 fixed inset-0 z-40" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 p-4 w-full md:inset-0 h-modal md:h-full">
    <div class="relative w-full max-w-xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm p-1.5 ml-auto inline-flex items-center  " data-modal-toggle="createtask-modal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="py-6 px-6 lg:px-8">
                <h3 class="mb-4 text-base font-bold text-esg6">Create a task</h3>
                <form action="#" class="space-y-6">

                        <div>
                            <label for="name" class="block mb-1 text-xs font-normal text-esg8 ">Name</label>
                            <input type="text" name="name" id="name" class="bg-esg41 border border-gray-300 text-esg8 text-sm rounded block w-full p-2.5" placeholder="Enter name" required>
                        </div>
                        <div>
                            <label for="description" class="block mb-1 text-xs font-normal text-esg8 ">Description</label>
                            <input type="text" name="description" id="description" class="bg-esg41 border border-gray-300 text-esg8 text-sm rounded block w-full p-2.5" placeholder="Enter description" required>
                        </div>
                        <div>
                            <label for="team_member" class="block mb-1 text-xs font-normal text-esg8 ">Team member</label>
                            <select name="team_member" id="team_member" class="bg-esg41 border border-gray-300 text-esg8 text-sm rounded block w-full p-2.5">
                                <option value="">Select the designated person</option>
                                <option value="">FirstName LastName</option>
                                <option value="">FirstName LastName</option>
                                <option value="">FirstName LastName</option>
                                <option value="">FirstName LastName</option>
                                <option value="">FirstName LastName</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="">
                                <label for="targer_association" class="block mb-1 text-xs font-normal text-esg8 ">Target association</label>
                                <select name="targer_association" id="targer_association" class="bg-esg41 border border-gray-300 text-esg8 text-sm rounded block w-full p-2.5">
                                    <option value="">Select or create a target</option>
                                    <option value="">Target XPTO One</option>
                                    <option value="">Target XPTO Two</option> 
                                    <option value="">Target XPTO Theree</option>
                                    <option value="">Target XPTO Four</option>
                                    
                                </select>
                            </div>
                            
                            <div>
                                <label for="duedate" class="block mb-1 text-xs font-normal text-esg8 ">Due Date</label>
                                <input type="date" name="duedate" id="duedate" class="bg-esg41 border border-gray-300 text-esg8 text-sm rounded block w-full p-2.5" placeholder="Define estimated date">
                            </div>
                        </div>
                   
                        <div class="flex items-center justify-end space-x-2">
                            <button data-modal-toggle="createtask-modal" type="button" class="text-esg6 bg-transparent p-3 mr-4">Cancel</button>

                            <button data-modal-toggle="createtask-modal" type="button" class="text-white bg-esg6 font-medium rounded text-sm p-3 text-center">Create Task</button>
                            
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div id="reportissue-modal" data-modal-backdropClasses="bg-white bg-opacity-25 fixed inset-0 z-40" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 p-4 w-full md:inset-0 h-modal md:h-full">
    <div class="relative w-full max-w-xl h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm p-1.5 ml-auto inline-flex items-center  " data-modal-toggle="reportissue-modal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="py-6 px-6 lg:px-8">
                <h3 class="text-base font-bold text-esg6">Report an issue</h3>
                <p class="mb-4 mt-1 text-xs text-esg8">The analysis is made by searching for one or more words within the document. If you think there are any inconsistencies, select the areas in question and write the terms we must look for.</p>
                <form action="#" class="space-y-4">

                    <div class="flex items-center">
                        <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-esg6 border-esg6 focus:ring-esg6 focus:ring-2">
                        <label for="default-checkbox" class="ml-2 text-sm font-medium text-esg42 inline-flex items-center">
                        Child labour and Young Workers
                            <svg class="ml-2" width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.281 7.54717H9.739L9.54689 7.36192C10.2193 6.57976 10.6241 5.56432 10.6241 4.45969C10.6241 1.99657 8.62751 0 6.16439 0C3.8179 0 1.89681 1.81132 1.72528 4.11664H3.11122C3.28274 2.5729 4.57262 1.37221 6.16439 1.37221C7.87279 1.37221 9.25187 2.75129 9.25187 4.45969C9.25187 6.1681 7.87279 7.54717 6.16439 7.54717C6.04775 7.54717 5.93797 7.52659 5.82134 7.51286V8.8988C5.93797 8.91252 6.04775 8.91938 6.16439 8.91938C7.26902 8.91938 8.28446 8.51458 9.06662 7.8422L9.25187 8.03431V8.57633L12.6824 12L13.7047 10.9777L10.281 7.54717Z" fill="#0D9401"/>
                                <path d="M10.281 7.54717H9.739L9.54689 7.36192C10.2193 6.57976 10.6241 5.56432 10.6241 4.45969C10.6241 1.99657 8.62751 0 6.16439 0C3.8179 0 1.89681 1.81132 1.72528 4.11664H3.11122C3.28274 2.5729 4.57262 1.37221 6.16439 1.37221C7.87279 1.37221 9.25187 2.75129 9.25187 4.45969C9.25187 6.1681 7.87279 7.54717 6.16439 7.54717C6.04775 7.54717 5.93797 7.52659 5.82134 7.51286V8.8988C5.93797 8.91252 6.04775 8.91938 6.16439 8.91938C7.26902 8.91938 8.28446 8.51458 9.06662 7.8422L9.25187 8.03431V8.57633L12.6824 12L13.7047 10.9777L10.281 7.54717Z" fill="#0D9401"/>
                                <path d="M2.20218 8.32718L0.779776 6.92785L0.29541 7.401L2.20218 9.27684L6.29541 5.25L5.81446 4.77684L2.20218 8.32718Z" fill="#0D9401"/>
                                </svg>

                        </label>
                    </div>

                    <div class="flex items-center">
                        <input id="default-checkbox2" type="checkbox" value="" class="w-4 h-4 text-esg6 border-esg6 focus:ring-esg6 focus:ring-2">
                        <label for="default-checkbox2" class="ml-2 text-sm font-medium text-esg42 inline-flex items-center">
                        Wages and benefits
                        <svg class="ml-2" width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10.324 7.54717H9.78197L9.58986 7.36192C10.2622 6.57976 10.667 5.56432 10.667 4.45969C10.667 1.99657 8.67048 0 6.20736 0C3.86087 0 1.93977 1.81132 1.76825 4.11664H3.15418C3.32571 2.5729 4.61559 1.37221 6.20736 1.37221C7.91576 1.37221 9.29484 2.75129 9.29484 4.45969C9.29484 6.1681 7.91576 7.54717 6.20736 7.54717C6.09072 7.54717 5.98094 7.52659 5.8643 7.51286V8.8988C5.98094 8.91252 6.09072 8.91938 6.20736 8.91938C7.31199 8.91938 8.32743 8.51458 9.10959 7.8422L9.29484 8.03431V8.57633L12.7254 12L13.7477 10.9777L10.324 7.54717Z" fill="#F44336"/>
<path d="M4.12845 5.36535L2.43377 7.06003L0.739089 5.36535L0.251953 5.85249L1.94664 7.54717L0.251953 9.24185L0.739089 9.72899L2.43377 8.03431L4.12845 9.72899L4.61559 9.24185L2.92091 7.54717L4.61559 5.85249L4.12845 5.36535Z" fill="#F44336"/>
</svg>


                        </label>
                    </div>


                    <div class="flex items-center">
                        <input id="default-checkbox3" type="checkbox" value="" class="w-4 h-4 text-esg6 border-esg6 focus:ring-esg6 focus:ring-2">
                        <label for="default-checkbox3" class="ml-2 text-sm font-medium text-esg42 inline-flex items-center">
                        Working hours
                        <svg class="ml-2" width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10.324 7.54717H9.78197L9.58986 7.36192C10.2622 6.57976 10.667 5.56432 10.667 4.45969C10.667 1.99657 8.67048 0 6.20736 0C3.86087 0 1.93977 1.81132 1.76825 4.11664H3.15418C3.32571 2.5729 4.61559 1.37221 6.20736 1.37221C7.91576 1.37221 9.29484 2.75129 9.29484 4.45969C9.29484 6.1681 7.91576 7.54717 6.20736 7.54717C6.09072 7.54717 5.98094 7.52659 5.8643 7.51286V8.8988C5.98094 8.91252 6.09072 8.91938 6.20736 8.91938C7.31199 8.91938 8.32743 8.51458 9.10959 7.8422L9.29484 8.03431V8.57633L12.7254 12L13.7477 10.9777L10.324 7.54717Z" fill="#F44336"/>
<path d="M4.12845 5.36535L2.43377 7.06003L0.739089 5.36535L0.251953 5.85249L1.94664 7.54717L0.251953 9.24185L0.739089 9.72899L2.43377 8.03431L4.12845 9.72899L4.61559 9.24185L2.92091 7.54717L4.61559 5.85249L4.12845 5.36535Z" fill="#F44336"/>
</svg>


                        </label>
                    </div>

                    <div class="flex items-center">
                        <input id="default-checkbox4" type="checkbox" value="" class="w-4 h-4 text-esg6 border-esg6 focus:ring-esg6 focus:ring-2">
                        <label for="default-checkbox4" class="ml-2 text-sm font-medium text-esg42 inline-flex items-center">
                        Modern slavery
                        <svg class="ml-2" width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10.324 7.54717H9.78197L9.58986 7.36192C10.2622 6.57976 10.667 5.56432 10.667 4.45969C10.667 1.99657 8.67048 0 6.20736 0C3.86087 0 1.93977 1.81132 1.76825 4.11664H3.15418C3.32571 2.5729 4.61559 1.37221 6.20736 1.37221C7.91576 1.37221 9.29484 2.75129 9.29484 4.45969C9.29484 6.1681 7.91576 7.54717 6.20736 7.54717C6.09072 7.54717 5.98094 7.52659 5.8643 7.51286V8.8988C5.98094 8.91252 6.09072 8.91938 6.20736 8.91938C7.31199 8.91938 8.32743 8.51458 9.10959 7.8422L9.29484 8.03431V8.57633L12.7254 12L13.7477 10.9777L10.324 7.54717Z" fill="#F44336"/>
<path d="M4.12845 5.36535L2.43377 7.06003L0.739089 5.36535L0.251953 5.85249L1.94664 7.54717L0.251953 9.24185L0.739089 9.72899L2.43377 8.03431L4.12845 9.72899L4.61559 9.24185L2.92091 7.54717L4.61559 5.85249L4.12845 5.36535Z" fill="#F44336"/>
</svg>


                        </label>
                    </div>

                    <div class="flex items-center">
                        <input id="default-checkbox5" type="checkbox" value="" class="w-4 h-4 text-esg6 border-esg6 focus:ring-esg6 focus:ring-2">
                        <label for="default-checkbox5" class="ml-2 text-sm font-medium text-esg42 inline-flex items-center">
                        Freedom of association and collective bargaining
                        <svg class="ml-2" width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10.324 7.54717H9.78197L9.58986 7.36192C10.2622 6.57976 10.667 5.56432 10.667 4.45969C10.667 1.99657 8.67048 0 6.20736 0C3.86087 0 1.93977 1.81132 1.76825 4.11664H3.15418C3.32571 2.5729 4.61559 1.37221 6.20736 1.37221C7.91576 1.37221 9.29484 2.75129 9.29484 4.45969C9.29484 6.1681 7.91576 7.54717 6.20736 7.54717C6.09072 7.54717 5.98094 7.52659 5.8643 7.51286V8.8988C5.98094 8.91252 6.09072 8.91938 6.20736 8.91938C7.31199 8.91938 8.32743 8.51458 9.10959 7.8422L9.29484 8.03431V8.57633L12.7254 12L13.7477 10.9777L10.324 7.54717Z" fill="#F44336"/>
<path d="M4.12845 5.36535L2.43377 7.06003L0.739089 5.36535L0.251953 5.85249L1.94664 7.54717L0.251953 9.24185L0.739089 9.72899L2.43377 8.03431L4.12845 9.72899L4.61559 9.24185L2.92091 7.54717L4.61559 5.85249L4.12845 5.36535Z" fill="#F44336"/>
</svg>


                        </label>
                    </div>

                    <div class="flex items-center">
                        <input id="default-checkbox6" type="checkbox" value="" class="w-4 h-4 text-esg6 border-esg6 focus:ring-esg6 focus:ring-2">
                        <label for="default-checkbox6" class="ml-2 text-sm font-medium text-esg42 inline-flex items-center">
                        Harassment and non-discrimination
                        <svg class="ml-2" width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10.324 7.54717H9.78197L9.58986 7.36192C10.2622 6.57976 10.667 5.56432 10.667 4.45969C10.667 1.99657 8.67048 0 6.20736 0C3.86087 0 1.93977 1.81132 1.76825 4.11664H3.15418C3.32571 2.5729 4.61559 1.37221 6.20736 1.37221C7.91576 1.37221 9.29484 2.75129 9.29484 4.45969C9.29484 6.1681 7.91576 7.54717 6.20736 7.54717C6.09072 7.54717 5.98094 7.52659 5.8643 7.51286V8.8988C5.98094 8.91252 6.09072 8.91938 6.20736 8.91938C7.31199 8.91938 8.32743 8.51458 9.10959 7.8422L9.29484 8.03431V8.57633L12.7254 12L13.7477 10.9777L10.324 7.54717Z" fill="#F44336"/>
<path d="M4.12845 5.36535L2.43377 7.06003L0.739089 5.36535L0.251953 5.85249L1.94664 7.54717L0.251953 9.24185L0.739089 9.72899L2.43377 8.03431L4.12845 9.72899L4.61559 9.24185L2.92091 7.54717L4.61559 5.85249L4.12845 5.36535Z" fill="#F44336"/>
</svg>


                        </label>
                    </div>

                    <div>
                        <textarea name="comments" id="comments" class="bg-esg41 border border-gray-300 text-esg8 text-sm rounded block w-full p-2.5" placeholder="Additional comments (optional)"></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-2">
                        <button data-modal-toggle="reportissue-modal" type="button" class="text-esg6 bg-transparent p-3 mr-4">Cancel</button>

                        <button data-modal-toggle="reportissue-modal" type="button" class="text-white bg-esg6 font-medium rounded text-sm p-3 text-center">Create Task</button>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>