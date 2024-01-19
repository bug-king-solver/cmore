<?php

/*
 * Part of the Converter package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Converter
 * @version    7.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2022, Cartalyst LLC
 * @link       https://cartalyst.com
 */

$conversions = [
    /*
    |--------------------------------------------------------------------------
    | Measurements
    |--------------------------------------------------------------------------
    |
    | The available measurements to convert and format units.
    |
    */
    'measurements' => [
        /*
        |--------------------------------------------------------------------------
        | Decimal numeration system
        |--------------------------------------------------------------------------
        |
        | The available decimal numeration classes.
        |
        */

        'decimal' => [
            'unit' => [
                'label' => 'unit',
                'format' => '1,00',
                'format_save' => '1!00',
                'unit'   => 1,
            ],

            'thousands' => [
                'label' => 'thousands',
                'format' => '1,00.000 thousands',
                'unit'   => 0.001,
            ],

            'millions' => [
                'label' => 'millions',
                'format' => '1,00.000 millions',
                'unit'   => 0.000001,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Area
        |--------------------------------------------------------------------------
        |
        | The available measurements to convert and format areas.
        |
        */

        'area' => [
            'km2' => [
                'label' => 'km2',
                'format' => '1,00.000 km2',
                'unit'   => 0.000001,
            ],

            'sqm' => [
                'label' => 'sqm',
                'format' => '1,00.00 sq m',
                'unit'   => 1,
            ],

            'acre' => [
                'label' => 'acre',
                'format' => '1,00.000 ac',
                'unit'   => 0.000247105,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Currency
        |--------------------------------------------------------------------------
        |
        | The available measurements to convert and format currencies.
        |
        */

        'currency' => [
            'eur' => [
                'label' => 'EUR',
                'format' => '&euro;1,0.00',
            ],

            'usd' => [
                'label' => 'USD',
                'format' => '$1,0.00',
            ],

            'gbp' => [
                'label' => 'GBP',
                'format' => '&pound;1,0.00',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Length
        |--------------------------------------------------------------------------
        |
        | The available measurements to convert and format lengths.
        |
        */

        'length' => [
            'km' => [
                'label' => 'km',
                'format' => '1,0.000 km',
                'unit'   => 0.001,
            ],

            'mi' => [
                'label' => 'mi',
                'format' => '1,0.000 mi.',
                'unit'   => 0.000621371,
            ],

            'm' => [
                'label' => 'm',
                'format' => '1,0.000 m',
                'unit'   => 1.00,
            ],

            'cm' => [
                'label' => 'cm',
                'format' => '1!0 cm',
                'unit'   => 100,
            ],

            'mm' => [
                'label' => 'mm',
                'format' => '1,0.00 mm',
                'unit'   => 1000,
            ],

            'ft' => [
                'label' => 'ft',
                'format' => '1,0.00 ft.',
                'unit'   => 3.28084,
            ],

            'in' => [
                'label' => 'in',
                'format' => '1,0.00 in.',
                'unit'   => 39.3701,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Mass
        |--------------------------------------------------------------------------
        |
        | The available measurements to convert and format weights.
        |
        */

        'mass' => [
            'g' => [
                'label' => 'g',
                'format' => '1,0.00 g',
                'unit'   => 1000.00,
            ],

            'kg' => [
                'label' => 'kg',
                'format' => '1,0.00 kg',
                'unit'   => 1.00,
            ],

            'lb' => [
                'label' => 'lb',
                'format' => '1,0.00 lb',
                'unit'   => 2.20462262,
            ],

            't' => [
                'label' => 't',
                'format' => '1,0.00 t',
                'unit'   => 0.001,
            ],

            'tn' => [
                'label' => 'tn',
                'format' => '1,0.00 tn',
                'unit'   => 0.000984207,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | CO2 Equivalent
        |--------------------------------------------------------------------------
        |
        | The available measurements to convert and format CO2 equivalent.
        |
        */

        'co2eq' => [
            'g' => [
                'label' => 'g',
                'format' => '1,0.00 g',
                'unit'   => 1000000.00,
            ],

            'kg' => [
                'label' => 'kg ',
                'format' => '1,0.00 kg ',
                'unit'   => 1000.00,
            ],

            't' => [
                'label' => 't ',
                'format' => '1,0.00 t ',
                'unit'   => 1,
            ],

            'kt' => [
                'label' => 'kt ',
                'format' => '1,0.00 kt ',
                'unit'   => 0.001,
            ],

            'Mt' => [
                'label' => 'Mt',
                'format' => '1,0.00 Mt',
                'unit'   => 0.000001,
            ],

            'kwh' => [
                'label' => 'Kwh',
                'format' => '1,0.00 g',
                'unit'   => 1,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Concentration
        |--------------------------------------------------------------------------
        |
        | The available measurements to convert and format concentration.
        |
        */

        'concentration' => [
            'µg/m3' => [
                'label' => 'µg/m3',
                'format' => '1,0.00 µg/m3',
                'unit'   => 1000000.00,
            ],

            'µg/l' => [
                'label' => 'µg/l',
                'format' => '1,0.00 µg/l',
                'unit'   => 1000.00,
            ],

            'mg/m3t' => [
                'label' => 'mg/m3',
                'format' => '1,0.00 mg/m3',
                'unit'   => 1000,
            ],

            'mg/l' => [
                'label' => 'mg/l',
                'format' => '1,0.00 mg/l',
                'unit'   => 1,
            ],

            'g/m3' => [
                'label' => 'g/m3',
                'format' => '1,0.00 g/m3',
                'unit'   => 1,
            ],

            'kg/m3' => [
                'label' => 'kg/m3',
                'format' => '1,0.00 kg/m3',
                'unit'   => 0.001,
            ],

            'g/l' => [
                'label' => 'g/l',
                'format' => '1,0.00 g/l',
                'unit'   => 0.001,
            ],

            'kg/l' => [
                'label' => 'kg/l',
                'format' => '1,0.00 kg/l',
                'unit'   => 0.0000001,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Volume
        |--------------------------------------------------------------------------
        |
        | The available measurements to convert and format volumes.
        |
        */

        'volume' => [
            'l' => [
                'label' => 'l',
                'format' => '1,00.00 l',
                'unit'   => 1000,
            ],

            'm3' => [
                'label' => 'm3',
                'format' => '1,00.000 m3',
                'unit'   => 1,
            ],

            'ML' => [
                'label' => 'ML',
                'format' => '1,00.000 ML',
                'unit'   => 0.001,
            ],

            'gal' => [
                'label' => 'gal',
                'format' => '1,00.000 gal',
                'unit'   => 264.172052,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Energy
        |--------------------------------------------------------------------------
        |
        | The available measurements to convert and format energies.
        |
        */

        'energy' => [
            'Wh' => [
                'label' => 'Wh',
                'format' => '1,00.000 wh',
                'unit'   => 1000,
            ],

            'J' => [
                'label' => 'J',
                'format' => '1,00.00 J',
                'unit'   => 3600000,
            ],

            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.00 MJ',
                'unit'   => 3.6,
            ],

            'kwh' => [
                'label' => 'kWh',
                'format' => '1,00.00 kWh',
                'unit'   => 1,
            ],

            'MWh' => [
                'label' => 'MWh',
                'format' => '1,00.000 Mwh',
                'unit'   => 0.001,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Temperature
        |--------------------------------------------------------------------------
        |
        | The available measurements to convert and format temperatures.
        |
        */

        'temperature' => [
            'c' => [
                'label' => 'c',
                'format' => '1,0.00 C',
                'unit'   => 1.00,
            ],

            'f' => [
                'label' => 'f',
                'format' => '1,0.00 °F',
                'unit'   => 1.80,
                'offset' => 32,
            ],

            'k' => [
                'label' => 'k',
                'format' => '1,0.00 K',
                'unit'   => 1.00,
                'offset' => 273.15,
            ],

            'rankine' => [
                'label' => 'rankine',
                'format' => '1,0.00 °R',
                'unit'   => 1.80,
                'offset' => 491.67,
            ],

            'romer' => [
                'label' => 'romer',
                'format' => '1,0.00 °Rø',
                'unit'   => 0.525,
                'offset' => 7.5,
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Energy
        |--------------------------------------------------------------------------
        |
        | The available measurements to convert and format energies.
        |
        */

        'time' => [
            'hr' => [
                'label' => 'hours',
                'format' => '1,00.00 hr',
                'unit'   => 24,
            ],
            'day' => [
                'label' => 'days',
                'format' => '1,00.00 days',
                'unit'   => 1,
            ],

            'week' => [
                'label' => 'weeks',
                'format' => '1,00.000 weeks',
                'unit'   => 0.142857143,
            ],
            'month' => [
                'label' => 'months',
                'format' => '1,00.000 months',
                'unit'   => 0.0328549112,
            ],
            'year' => [
                'label' => 'years',
                'format' => '1,00.000 years',
                'unit'   => 0.00273790926,
            ],
        ],

        "fuel-butane" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 79.4723039021
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0794723039
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 138.2743362832
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-cng" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 79.6558865700
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0796558866
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 455.1661356395
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-lng" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 79.6558865700
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0796558866
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 176.0253476501
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-gpl" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 78.3576241968
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0783576242
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 147.9289940828
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-natural-gas" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 79.6558865700
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0796558866
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 100000.0000000000
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-propane" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 77.5855380557
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0775855381
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 150.8295625943
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-aviation" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 81.9672131148
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0819672131
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 102.6167265264
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-kerosene" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 82.0142704831
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0820142705
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 102.4275325207
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-avg-biofuel-diesel" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 84.5022815616
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0845022816
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 100.2004008016
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-100-mineral-diesel" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 83.9489590329
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0839489590
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 99.8203234178
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-avg-biofuel-petrol" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 82.5354902608
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0825354903
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 110.8401684771
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-100-mineral-petrol" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 80.6256550834
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0806256551
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 108.6838387132
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-coal-industrial" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 141.7032733456
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.1417032733
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-coal-eletricity-generation" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 148.9646953672
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.1489646954
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-coal-domestic" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 125.8178158027
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.1258178158
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],
        "fuel-naphta" => [
            "mwh" => [
                "id" => "mwh",
                "label" => "MWh",
                "format" => "1 MWh",
                "unit" => 1
            ],
            "kwh" => [
                "id" => "kwh",
                "label" => "kWh",
                "format" => "1 kWh",
                "unit" => 1000
            ],
            "kg" => [
                "label" => "Kg",
                "format" => "1,0.00 kg",
                "unit" => 79.1891035793
            ],
            "t" => [
                "label" => "T",
                "format" => "1,0.00 t",
                "unit" => 0.0791891036
            ],
            "l" => [
                "label" => "L",
                "format" => "1,0.00 l",
                "unit" => 117.5917215428
            ],
            'gj' => [
                'label' => 'GJ',
                'format' => '1,00.000 GJ',
                'unit'   => 3.5999712002
            ],
            'MJ' => [
                'label' => 'MJ',
                'format' => '1,00.000 MJ',
                'unit'   => 3599.9712002304
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | power numeration system
        |--------------------------------------------------------------------------
        |
        | The available power numeration classes.
        |
        */

        "power" => [
            "MW" => [
                "id" => "MW",
                "label" => "MW",
                "format" => "1 MW",
                "unit" => 1
            ],
            "W" => [
                "label" => "W",
                "format" => "1,0.00 W",
                "unit" => 1000000
            ],
            "kW" => [
                "label" => "kW",
                "format" => "1,0.00 kW",
                "unit" => 1000
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Cache Expiration Duration
    |--------------------------------------------------------------------------
    |
    | The duration currency rates are cached in minutes.
    |
    */

    'expires' => 60,

    /*
    |--------------------------------------------------------------------------
    | Currency Service Exchangers
    |--------------------------------------------------------------------------
    |
    | Here, you may specify any number of service exchangers configurations
    | your application requires.
    |
    */

    'exchangers' => [
        /*
        |--------------------------------------------------------------------------
        | Default Exchanger
        |--------------------------------------------------------------------------
        |
        | Define here the default exchanger.
        |
        */

        'default' => 'native',

        /*
        |--------------------------------------------------------------------------
        | OpenExchangeRates.org
        |--------------------------------------------------------------------------
        |
        | Define here the OpenExchangeRates.org app id.
        |
        */

        'openexchangerates' => [
            'app_id' => null,
        ],
    ],
];

foreach ($conversions['measurements'] as $unity => &$unities) {
    foreach ($unities as $key => $values) {
        unset($unities[$key]);
        $unities[strtolower($key)] = $values;
    }
}
return $conversions;
