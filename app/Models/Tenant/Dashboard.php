<?php

namespace App\Models\Tenant;

use App\Models\Chart;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'layout', 'filters'];

    /**
     * Get a list of the dashboard
     *
     * @return Collection
     */
    public static function list()
    {
        return self::
            orderBy('name');
    }

    /**
     * Get the dashboard's layout.
     */
    public function getPlaceholderImageAttribute()
    {
        $returnImg = url('images/charts/text.png');
        $dashboardItems = json_decode($this->layout, 1);
        if (count($dashboardItems) > 0) {
            foreach ($dashboardItems as $dashboardItem) {
                $hasGraph = array_search('graph', array_column($dashboardItem, 'type'));
                if ($hasGraph !== false) {
                    $chartImage = Chart::where('slug', $dashboardItem[$hasGraph]['value'])->pluck('placeholder')->first();
                    $returnImg = url($chartImage);
                    break;
                }
            }
        }
        return $returnImg;
    }

    /**
     * Count the number of graphs in the dashboard.
     */
    public function getTotalGraphsAttribute()
    {
        $total = 0;
        $layout = $this->layout ?? [];
        foreach (parseStringToArray($layout) as $row) {
            foreach ($row as $col) {
                if ($col['type'] == 'graph') {
                    $total++;
                }
            }
        }
        return  $total;
    }
}
