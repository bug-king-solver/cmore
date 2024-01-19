<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'tests';

    public static function test()
    {
        //nothing to do
    }
}
