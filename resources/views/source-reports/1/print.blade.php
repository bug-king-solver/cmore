<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Source Report</title>
    <style nonce="{{ csp_nonce() }}">
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: 2px;
            font-size: 13px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .checkbox {
            position: relative;
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 2px solid grey;
            cursor: pointer;
        }

        .checkbox::before,
        .checkbox::after {
            content: '';
            position: absolute;
            width: 9px;
            height: 2px;
            background-color: grey;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
        }

        .checkbox::before {
            transform: translate(-50%, -50%) rotate(45deg);
        }

        .checkbox::after {
            transform: translate(-50%, -50%) rotate(-45deg);
        }

        .checkbox input[type="checkbox"] {
            visibility: hidden;
        }

        .tick-checkbox::before {
            content: "\2713";
            /* Checked icon */
            position: absolute;
            width: 9px;
            height: 2px;
            background-color: grey;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
        }

        .w-8 {
            width: 8%;
        }

        .w-21 {
            width: 21%;
        }

        .w-30 {
            width: 30%;
        }

        .w-11 {
            width: 11%;
        }
    </style>
</head>

<body>
    <h1 style="color:rgb(93, 90, 90)">{{ $report_data->sources->name }} Table</h1>
    <table>
        <thead>
            <tr>
                <th class="w-8" style="color:rgb(78, 11, 78);">{{ $report_data->sources->name }}</th>
                <th class="w-21">{{ __('Indicator') }}</th>
                <th class="w-30">{{ __('Location of disclosures') }}</th>
                <th class="w-30">{{ __('Comment/Reason for Omission') }}</th>
                <th class="w-11">{{ __('External Assurance') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report_data->data as $key => $data)
            @php
            $loopEncoded = json_encode($loop);
            @endphp
            <tr class="reports">
                <td loop="{!! $loopEncoded !!}" style="font-weight: 20px">{{ $data['reference'] }}</td>
                <td loop="{!! $loopEncoded !!}">{{ $data['subtitle'] }}</td>
                <td loop="{!! $loopEncoded !!}" style="color:rgb(78, 11, 78);">
                    {!! nl2br($data['location']) !!}
                </td>
                <td loop="{!! $loopEncoded !!}">
                    {!! $data['comment'] !!}
                </td>
                <td loop="{!! $loopEncoded !!}" style="vertical-align: middle;">
                    @if ($data['external_assurance'])
                    <label for="tick-checkbox" class="tick-checkbox">
                        <input type="checkbox" checked>
                        <span></span>
                    </label>
                    <span style="margin-left: 5px;">Yes</span>
                    @else
                    <label for="myCheckbox" class="checkbox"></label>
                    <span style="margin-left: 5px;">No</span>
                    @endif


                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
