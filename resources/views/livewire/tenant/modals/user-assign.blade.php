<div class="p-10 min-h-[400px]">
    <div class="flex justify-end -mt-5 -mr-5">
        <button type="button" wire:click="$emit('closeModal')" class="dark:hover:text-esg27 ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-800">
            <svg class="text-esg8 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
    </div>
    <div class="text-center mb-10">

        <h2 class="text-esg29 text-2xl font-extrabold">
            @if($assignment_type=='can_validate')
                {{ __('Assign Validator') }}
            @else
                {{ __('Assign User') }}
            @endif

        </h2>
    </div>
    @error('upload.*')
        <p class="mb-5 text-center text-sm font-bold text-red-600">
            {{ $message }}
        </p>
    @enderror

    @error('attach')
        <p class="mb-5 text-center text-sm font-bold text-red-600">
            {{ $message }}
        </p>
    @enderror

    @if(session('error'))
        <p class="mb-5 text-center text-sm font-bold text-red-600">
            {{ session('error') }}
        </p>
    @endif

    @if(session('success'))
        <p class="mb-5 text-center text-sm font-bold text-green-600">
            {{ session('success') }}
        </p>
    @endif

    <div class="mb-5 flex items-center">


        <div class="grow">
            <x-inputs.tomselect
                :wire_ignore="false"
                id="users"
                modelmodifier=".defer"
                preload="focus"
                plugins="['remove_button']"
                :options="$usersList"
                :items="$users"
                placeholder="{{ __('Search for a user by name.') }}"
                />
        </div>

        <div class="pl-4">
            <x-buttons.btn text="{{ __('Save') }}" wire:click="save" />
        </div>
    </div>
</div>
