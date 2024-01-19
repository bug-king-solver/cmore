<?php

namespace Database\Seeders\Tenant;

use App\Models\Chart;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardTemplateSeeder extends Seeder
{
    /** artisan tenants:seed --class=Database\\Seeders\\Tenant\\DashboardTemplateSeeder
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dashboard_templates')->truncate();

        $charts = Chart::where('type', 'chart')->get();
        $layout = [];
        $headings = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        foreach ($charts as $i => $chart) {
            $layout[$i] = [
                [
                    'type' => $headings[rand(0, 5)],
                    'value' => $chart->name
                ],
            ];
            $layout[$i] = [
                [
                    'type' => 'graph',
                    'value' => $chart->slug
                ],
            ];
        }

        DB::table('dashboard_templates')->insert([
            'name' => 'General Template',
            'description' => 'General information dashboard template',
            'layout' => json_encode($layout),
            'created_at' => Carbon::now(),
        ]);

        DB::table('dashboard_templates')->insert([
            'name' => 'Environment Template',
            'description' => 'Environment information dashboard template',
            'created_at' => Carbon::now(),
        ]);

        DB::table('dashboard_templates')->insert([
            'name' => 'Social Template',
            'description' => 'Social information dashboard template',
            'created_at' => Carbon::now(),
        ]);

        DB::table('dashboard_templates')->insert([
            'name' => 'Governance Template',
            'description' => 'Governance information dashboard template',
            'created_at' => Carbon::now(),
        ]);
    }
}
