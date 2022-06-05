<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\isNull;

class MalfunctioningSensor extends Model
{
    use HasFactory;

    public static function insertMalfunctioningSensors(array $sensorsArray)
    {
        $finalArray = [];
        foreach ($sensorsArray as $key => $sensor)
        {
            $finalArray[] = [
                'sensor_id'     => $sensor['id'],
                'face'          => $sensor['face'],
                'temperature'   => $sensor['temperature'],
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ];
        }
        self::insert($finalArray);
    }

}
