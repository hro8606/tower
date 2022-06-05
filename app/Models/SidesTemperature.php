<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SidesTemperature extends Model
{
    use HasFactory;

    protected $fillable = [
        'face',
        'temperature',
    ];

    public static function getAggregateAvg($date)
    {


        return self::where('created_at', '>=', $date)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

}
