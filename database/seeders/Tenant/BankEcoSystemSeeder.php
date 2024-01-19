<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\SharingOption;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankEcoSystemSeeder extends Seeder
{
    /** php artisan tenants:seed --class=Database\\Seeders\\Tenant\\BankEcoSystemSeeder
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SharingOption::updateOrCreate(
            [
                'identifier' => '0269',
            ],
            [
                'identifier' => '0269',
                'logo' => 'millennium.png',
                'name' => 'BANKINTER, SA - SUCURSAL EM PORTUGAL',
                'commercial_name' => 'bankintar',
                'data' => ['description' => 'BANKINTER, SA - SUCURSAL EM PORTUGAL'],
                'created_at' => Carbon::now(),
            ]
        );

        SharingOption::updateOrCreate(
            [
                'identifier' => '9000',
            ],
            [
                'identifier' => '9000',
                'logo' => 'santander.png',
                'name' => 'CAIXA CENTRAL - CAIXA CENTRAL DE CRÉDITO AGRÍCOLA MÚTUO, CRL',
                'commercial_name' => 'CA',
                'data' => ['description' => 'CAIXA CENTRAL - CAIXA CENTRAL DE CRÉDITO AGRÍCOLA MÚTUO, CRL'],
                'created_at' => Carbon::now(),
            ]
        );

        SharingOption::updateOrCreate(
            [
                'identifier' => '5180',
            ],
            [
                'identifier' => '5180',
                'logo' => 'caixa-geral-de-depositos.png',
                'name' => 'CAIXA DE CRÉDITO AGRÍCOLA MÚTUO DE LEIRIA, CRL',
                'commercial_name' => 'CA Leiria',
                'data' => ['description' => 'CAIXA DE CRÉDITO AGRÍCOLA MÚTUO DE LEIRIA, CRL'],
                'created_at' => Carbon::now(),
            ]
        );


        SharingOption::updateOrCreate(
            [
                'identifier' => '5200',
            ],
            [
                'identifier' => '5200',
                'logo' => 'novobanco.png',
                'name' => 'CAIXA DE CRÉDITO AGRÍCOLA MÚTUO DE MAFRA, CRL',
                'commercial_name' => 'CA Mafra',
                'data' => ['description' => 'CAIXA DE CRÉDITO AGRÍCOLA MÚTUO DE MAFRA, CRL'],
                'created_at' => Carbon::now(),
            ]
        );

        SharingOption::updateOrCreate(
            [
                'identifier' => '5340',
            ],
            [
                'identifier' => '5340',
                'logo' => 'bpi.png',
                'name' => 'CAIXA DE CRÉDITO AGRÍCOLA MÚTUO DE TORRES VEDRAS, C.R.L',
                'commercial_name' => 'CA Torres Vedras',
                'data' => ['description' => 'CAIXA DE CRÉDITO AGRÍCOLA MÚTUO DE TORRES VEDRAS, C.R.L'],
                'created_at' => Carbon::now(),
            ]
        );

        SharingOption::updateOrCreate(
            [
                'identifier' => '0098',
            ],
            [
                'identifier' => '0098',
                'logo' => '',
                'name' => 'CAIXA DE CRÉDITO AGRÍCOLA MÚTUO DE BOMBARRAL, CRL',
                'commercial_name' => 'CA Bombarral',
                'data' => ['description' => 'CAIXA DE CRÉDITO AGRÍCOLA MÚTUO DE BOMBARRAL, CRL'],
                'created_at' => Carbon::now(),
            ]
        );

        SharingOption::updateOrCreate(
            [
                'identifier' => '0007',
            ],
            [
                'identifier' => '0007',
                'logo' => '',
                'name' => 'NOVO BANCO, S.A.',
                'commercial_name' => 'Novobanco',
                'data' => ['description' => 'NOVO BANCO, S.A.'],
                'created_at' => Carbon::now(),
            ]
        );

        SharingOption::updateOrCreate(
            [
                'identifier' => '0010',
            ],
            [
                'identifier' => '0010',
                'logo' => '',
                'name' => 'BANCO BPI S.A.',
                'commercial_name' => 'BPI',
                'data' => ['description' => 'BANCO BPI S.A.'],
                'created_at' => Carbon::now(),
            ]
        );

        SharingOption::updateOrCreate(
            [
                'identifier' => '0018',
            ],
            [
                'identifier' => '0018',
                'logo' => '',
                'name' => 'BANCO SANTANDER TOTTA, S.A.',
                'commercial_name' => 'BST',
                'data' => ['description' => 'BANCO SANTANDER TOTTA, S.A.'],
                'created_at' => Carbon::now(),
            ]
        );

        SharingOption::updateOrCreate(
            [
                'identifier' => '0033',
            ],
            [
                'identifier' => '0033',
                'logo' => '',
                'name' => 'BANCO COMERCIAL PORTUGUÊS, SA',
                'commercial_name' => 'BCP',
                'data' => ['description' => 'BANCO COMERCIAL PORTUGUÊS, SA'],
                'created_at' => Carbon::now(),
            ]
        );

        SharingOption::updateOrCreate(
            [
                'identifier' => '0035',
            ],
            [
                'identifier' => '0035',
                'logo' => '',
                'name' => 'CAIXA GERAL DE DEPÓSITOS, S.A.',
                'commercial_name' => 'CGD',
                'data' => ['description' => 'CAIXA GERAL DE DEPÓSITOS, S.A.'],
                'created_at' => Carbon::now(),
            ]
        );

        SharingOption::updateOrCreate(
            [
                'identifier' => '0036',
            ],
            [
                'identifier' => '0036',
                'logo' => '',
                'name' => 'Montepio Geral',
                'commercial_name' => 'Montepio',
                'data' => ['description' => 'Montepio Geral'],
                'created_at' => Carbon::now(),
            ]
        );

        SharingOption::updateOrCreate(
            [
                'identifier' => '0079',
            ],
            [
                'identifier' => '0079',
                'logo' => '',
                'name' => 'BANCO BIC PORTUGUÊS, SA',
                'commercial_name' => 'Eurobic',
                'data' => ['description' => 'BANCO BIC PORTUGUÊS, SA'],
                'created_at' => Carbon::now(),
            ]
        );
    }
}
