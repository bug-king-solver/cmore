<div x-data="{ show: true }" class="relative">
    <input placeholder="" :type="show ? 'password' : 'text'" {{ $attributes->except('extra')->merge(['class' => 'form-input text-esg29 border-esg6 block w-full min-w-0 flex-1 rounded-md text-lg transition duration-150 ease-in-out block']) }}>
    <div class="absolute inset-y-0 py-3 right-0 top-0.5 pr-3 flex items-center text-sm leading-5">

        <svg class="h-4 text-gray-700" fill="none" @click="show = !show" viewBox="0 0 23 15" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.5 0C6.5 0 2.23 3.11 0.5 7.5C2.23 11.89 6.5 15 11.5 15C16.5 15 20.77 11.89 22.5 7.5C20.77 3.11 16.5 0 11.5 0ZM11.5 12.5C8.74 12.5 6.5 10.26 6.5 7.5C6.5 4.74 8.74 2.5 11.5 2.5C14.26 2.5 16.5 4.74 16.5 7.5C16.5 10.26 14.26 12.5 11.5 12.5ZM11.5 4.5C9.84 4.5 8.5 5.84 8.5 7.5C8.5 9.16 9.84 10.5 11.5 10.5C13.16 10.5 14.5 9.16 14.5 7.5C14.5 5.84 13.16 4.5 11.5 4.5Z" fill="#B8BCCA"/>
        </svg>

        
    </div>
</div>