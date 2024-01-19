<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    public static function test()
    {
        // nothing to do
    }
}
