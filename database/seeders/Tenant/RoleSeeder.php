<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $message = 'Started RoleSeeder seeder';
        activity()
            ->event('seeding')
            ->log($message);

        Role::updateOrCreate(
            [
                'name' => 'Super Admin',
            ],
            [
                'default' => true,
                'name' => 'Super Admin',
            ]
        );

        $message = 'Finished RoleSeeder seeder';
        activity()
            ->event('seeding')
            ->log($message);
    }
}
