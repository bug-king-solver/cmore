<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant\Permission;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $groups = [
            __('Tags'),
            __('Reputation'),
        ];

        $options = [];
        foreach ($groups as $group) {
            $slug = Str::slug($group);

            $options = array_merge($options, [
                [
                    'group' => $group,
                    'name' => "{$slug}.*",
                    'description' => __(':resource » All', ['resource' => $group]),
                    'order' => 10,
                    'guard_name' => 'web',
                ],
                [
                    'group' => $group,
                    'name' => "{$slug}.view,update,create",
                    'description' => __(':resource » Only View, Update and Create', [
                        'resource' => $group,
                    ]),
                    'order' => 20,
                    'guard_name' => 'web',
                ],
                [
                    'group' => $group,
                    'name' => "{$slug}.view,update",
                    'description' => __(':resource » Only View and Update', [
                        'resource' => $group,
                    ]),
                    'order' => 30,
                    'guard_name' => 'web',
                ],
                [
                    'group' => $group,
                    'name' => "{$slug}.view",
                    'description' => __(':resource » Only View', [
                        'resource' => $group,
                    ]),
                    'order' => 40,
                    'guard_name' => 'web',
                ],
            ]);
        }

        Permission::insert($options);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $groups = [
            __('Tags'),
            __('Reputation'),
        ];
        Permission::whereIn('group', $groups)->delete();
    }
};
