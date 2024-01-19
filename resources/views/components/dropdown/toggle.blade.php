@php 
    $data = json_decode($data);
@endphp
@if($data && count($data) > 1)
    <button id="dropdownInformationButton" data-dropdown-toggle="dropdownDefault_role_{{ $roleid ?? '' }}" class="py-2.5 inline-flex items-center" type="button">{{ $data[0] }}@include('icons/tables/toggle')</button>
    <!-- Dropdown menu -->
    <div id="dropdownDefault_role_{{ $roleid ?? '' }}" class="hidden z-10 w-28 bg-white rounded divide-y divide-gray-100 shadow">
        <ul class="py-1 text-sm text-esg46" aria-labelledby="dropdownDefault_role_{{ $roleid ?? '' }}">
            @for ($i = 1; $i < count($data); $i++)
                <li>
                    <p class="block py-2 px-2 hover:bg-gray-100">{{$data[$i]}}</p>
                </li>    
            @endfor
        </ul>
    </div>
@elseif($data && count($data)==1)
    {{reset($data)}}
@endif
