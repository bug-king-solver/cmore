@php
    $data = json_decode($data,true);
    $data['assignment_type'] = App\Enums\Questionnaire\AssignmentType::CAN_ANSWER->value;
    if($isValidator){
        $data['assignment_type'] = App\Enums\Questionnaire\AssignmentType::CAN_VALIDATE->value;
    }
    $data = json_encode($data);

@endphp

<button class="relative cursor-pointer py-1 px-4" @if (! tenant()->onActiveSubscription) @click="trial_modal = true" @else @click="Livewire.emit('openModal', 'modals.user-assign', {{ $data }})" @endif>

    @if ($counter && count($counter)>0)
{{--        user or validator assigned now need to show there images--}}
        <span class="flex gap-2 mt-1">
            @if($isValidator)
                @if($answer->validation)
                    @include('icons.user-validator-assign-green', ['class' =>'w-4 h-4', 'color' => color('esg6')])
                @else
                    @include('icons.user-validator-assign', ['class' =>'w-4 h-4', 'color' => color('esg6')])
                @endif

            @else
                @include('icons.user-assign', ['class' =>'w-4 h-4', 'color' => color('esg6')])
            @endif

            @php
             $users = \App\Models\User::whereIn('id', $counter)->get();
            @endphp

            @foreach($users as $user)
                   <img title="{{ $user->name }}" class="w-5 h-5 mr-2 mt-1 rounded-md border-2" src="{{ $user->avatar }}"  />
            @endforeach


        </span>
    @else
        <span class="flex items-center gap-2 whitespace-nowrap text-xs text-esg6">
            @if(isset($text))
                {{$text}}
            @else
                @if($isValidator)
                    @include('icons.user-validator-assign', ['class' =>'w-4 h-4', 'color' => ($counter ?? false) ? color('esg4') : color('esg6')])
                @else
                    @include('icons.user-assign', ['class' =>'w-4 h-4', 'color' => ($counter ?? false) ? color('esg4') : color('esg6')])
                @endif

            @endif

            @if($isValidator)
                {{ __('Assign Validator')}}
            @else
                {{ __('Assign User')}}
            @endif
        </span>
    @endif
    <div class="{{$isValidator ? 'validator-user':'assign-users'}} hidden">
        {{json_encode($counter ? $users->pluck('id') : [])}}
    </div>
</button>
