<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;

class TenantBucketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenant = tenant();
        $tenant->createS3Bucket();
    }
}
