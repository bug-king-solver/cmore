<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Data') }}</title>
    <style nonce="{{csp_nonce()}}">
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
    </style>
</head>
<body>
   
   <!-- Table HTML -->
<table>
    <thead>
        <tr>
            <th>Description</th>
            <th>Value</th>
            <th>Unit</th>
            @foreach ($sources_columns as $source)
                <th>{{$source}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($indicators as $indicator)
        <tr>
            <td>{{ $indicator->indicator->name ?? '-' }}</td>
            <td>{{ $indicator->value ?? '-' }}</td>
            <td>{{ $indicator->indicator->unit_default ?? '-' }}</td>
            @foreach ($sources_columns as $sourceName)
            @php
                $source = $indicator->indicator->sources->firstWhere('name', $sourceName);
            @endphp
            <td>
                @if ($source)
                    {{ $source->pivot->reference ?? '-' }}
                @else
                    -
                @endif
            </td>
        @endforeach
        </tr>
        @endforeach
    </tbody>
</table>

</body>