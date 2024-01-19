<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan tenants:seed --class=Database\\Seeders\\Tenant\\PermissionSeeder
     *
     * @return void
     */
    public function run()
    {
        $message = 'Started PermissionSeeder seeder';
        activity()
            ->event('seeding')
            ->log($message);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $groups = [
            __('Dashboard'),
            __('Data'),
            __('Library'),
            __('Companies'),
            __('Roles'),
            __('Permissions'),
            __('Users'),
            __('Catalog'),
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

        // Customs
        // Benchmarking
        $group = __('Benchmarking');
        $slug = Str::slug($group);

        $options[] = [
            'group' => $group,
            'name' => "{$slug}.*",
            'description' => __(':resource » All', ['resource' => $group]),
            'order' => 10,
            'guard_name' => 'web',
        ];

        // Targets
        $group = __('Targets');
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
                'name' => "{$slug}.view,comment,update,create",
                'description' => __(':resource » Only View, Comment, Update and Create', [
                    'resource' => $group,
                ]),
                'order' => 20,
                'guard_name' => 'web',
            ],
            [
                'group' => $group,
                'name' => "{$slug}.view,comment,update",
                'description' => __(':resource » Only View, Comment and Update', [
                    'resource' => $group,
                ]),
                'order' => 30,
                'guard_name' => 'web',
            ],
            [
                'group' => $group,
                'name' => "{$slug}.view,comment",
                'description' => __(':resource » Only View and Comment', [
                    'resource' => $group
                ]),
                'order' => 40,
                'guard_name' => 'web',
            ],
            [
                'group' => $group,
                'name' => "{$slug}.view",
                'description' => __(':resource » Only View', [
                    'resource' => $group,
                ]),
                'order' => 50,
                'guard_name' => 'web',
            ],
        ]);

        // Questionnaires
        $group = __('Questionnaires');
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
                'name' => "{$slug}.view,answer,submit,review,update,create",
                'description' => __(':resource » Only View, Answer, Submit, Review, Update and Create', [
                    'resource' => $group,
                ]),
                'order' => 20,
                'guard_name' => 'web',
            ],
            [
                'group' => $group,
                'name' => "{$slug}.view,answer,submit,update,create",
                'description' => __(':resource » Only View, Answer, Submit, Update and Create', [
                    'resource' => $group,
                ]),
                'order' => 30,
                'guard_name' => 'web',
            ],
            [
                'group' => $group,
                'name' => "{$slug}.view,answer,update,create",
                'description' => __(':resource » Only View, Answer, Update and Create', [
                    'resource' => $group,
                ]),
                'order' => 40,
                'guard_name' => 'web',
            ],
            [
                'group' => $group,
                'name' => "{$slug}.view,answer,update",
                'description' => __(':resource » Only View, Answer and Update', [
                    'resource' => $group,
                ]),
                'order' => 50,
                'guard_name' => 'web',
            ],
            [
                'group' => $group,
                'name' => "{$slug}.view,answer",
                'description' => __(':resource » Only View and Answer', [
                    'resource' => $group,
                ]),
                'order' => 60,
                'guard_name' => 'web',
            ],
            [
                'group' => $group,
                'name' => "{$slug}.view",
                'description' => __(':resource » Only View', [
                    'resource' => $group,
                ]),
                'order' => 70,
                'guard_name' => 'web',
            ],
        ]);


        foreach ($options as $option) {
            Permission::updateOrCreate(
                [
                    'name' => $option['name'],
                    'group' => $option['group'],
                ],
                $option
            );
        }

        $message = 'Finished PermissionSeeder seeder';
        activity()
            ->event('seeding')
            ->log($message);
    }
}
