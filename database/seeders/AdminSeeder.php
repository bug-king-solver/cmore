<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::updateOrCreate(
            [
                'email' => 'admin@cmore.pt',
            ],
            [
                'name' => 'C-MORE',
                'email' => 'admin@cmore.pt',
                'password' => bcrypt('K&JVQ!8qLLI3!X!r'),
            ]
        );
    }
}
