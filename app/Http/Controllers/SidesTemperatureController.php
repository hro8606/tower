<?php

namespace App\Http\Controllers;

use App\Models\MalfunctioningSensor;
use App\Models\SidesTemperature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use function PHPUnit\Framework\isEmpty;

class SidesTemperatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {

        $dateRange = Carbon::today()->subDays(7);

        $southGenerateArray = [];
        $eastGenerateArray = [];
        $northGenerateArray = [];
        $westGenerateArray = [];

        $sidesTemperature = SidesTemperature::getAggregateAvg($dateRange);

        foreach ($sidesTemperature as $k => $value) {

            switch ($value['face']) {
                case 'south':
                    $dateTameOfRow = Carbon::parse($value['created_at'])->format("Y-m-d H:00:00") . 'south';
                    if (isEmpty($southGenerateArray)) {
                        $southGenerateArray[$dateTameOfRow] = $value;
                    } else if (array_key_exists($dateTameOfRow, $southGenerateArray)) {
                        $newTemp = ($southGenerateArray[$dateTameOfRow]['temperature'] + $value['temperature']) / 2;
                        $southGenerateArray[$dateTameOfRow]['temperature'] = $newTemp;
                    }
                    break;
                case 'east':
                    $dateTameOfRow = Carbon::parse($value['created_at'])->format("Y-m-d H:00:00") . 'east';
                    if (isEmpty($eastGenerateArray)) {
                        $eastGenerateArray[$dateTameOfRow] = $value;
                    } else if (array_key_exists($dateTameOfRow, $eastGenerateArray)) {
                        $newTemp = ($eastGenerateArray[$dateTameOfRow]['temperature'] + $value['temperature']) / 2;
                        $eastGenerateArray[$dateTameOfRow]['temperature'] = $newTemp;
                    }

                    break;
                case 'north':
                    $dateTameOfRow = Carbon::parse($value['created_at'])->format("Y-m-d H:00:00") . 'north';
                    if (isEmpty($northGenerateArray)) {
                        $northGenerateArray[$dateTameOfRow] = $value;
                    } else if (array_key_exists($dateTameOfRow, $northGenerateArray)) {
                        $newTemp = ($northGenerateArray[$dateTameOfRow]['temperature'] + $value['temperature']) / 2;
                        $northGenerateArray[$dateTameOfRow]['temperature'] = $newTemp;
                    }

                    break;
                case 'west':
                    $dateTameOfRow = Carbon::parse($value['created_at'])->format("Y-m-d H:00:00") . 'west';
                    if (isEmpty($westGenerateArray)) {
                        $westGenerateArray[$dateTameOfRow] = $value;
                    } else if (array_key_exists($dateTameOfRow, $westGenerateArray)) {
                        $newTemp = ($westGenerateArray[$dateTameOfRow]['temperature'] + $value['temperature']) / 2;
                        $westGenerateArray[$dateTameOfRow]['temperature'] = $newTemp;
                    }

                    break;
            }

        }

        $generatedArray = $westGenerateArray + $southGenerateArray + $eastGenerateArray + $northGenerateArray;
        return view('tower.index')->with(array('sidesTemperature' => $generatedArray));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*count average temp for any side */
//        faces
        $sensorsArray = $request->all();
        $faultSensorsArray = [];

        $southAvg = 0;
        $eastAvg = 0;
        $northAvg = 0;
        $westAvg = 0;

        $southQuantity = 0;
        $eastQuantity = 0;
        $northQuantity = 0;
        $westQuantity = 0;


        /*loop throw sensors for getting average temperature*/
        foreach ($sensorsArray as $k => $value) {

            /*check if in sensor request body is empty the sensor Id (it's a specific case that we just can add log for this ) */
            if (!isset($value['id']) || empty($value['id'])) {
                Log::info(' We have sensor body without sensor Id !  It is in  - "' . $value['face'] . '" face with ->"' . $value['timestamp'] . '" temperature');
//                remove sensor from array
                unset($sensorsArray[$k]);
                continue;
            }
            /*check if some data is missing from sensor request body*/
            if (!isset($value['timestamp']) || !isset($value['face']) || !isset($value['temperature']) || empty($value['temperature']) || empty($value['timestamp']) || empty($value['face'])) {

                $faultSensorsArray[] = $value;
//                remove sensor from array
                unset($sensorsArray[$k]);
                continue;
            }

            switch ($value['face']) {
                case 'south':
                    $southQuantity++;
                    $southAvg += $value['temperature'];
                    break;
                case 'east':
                    $eastQuantity++;
                    $eastAvg += $value['temperature'];
                    break;
                case 'north':
                    $northQuantity++;
                    $northAvg += $value['temperature'];
                    break;
                case 'west':
                    $westQuantity++;
                    $westAvg += $value['temperature'];
                    break;
            }

        }

        $southAvg = $southQuantity != 0 ? $southAvg / $southQuantity : $southAvg;
        $eastAvg = $eastQuantity != 0 ? $eastAvg / $eastQuantity : $eastAvg;
        $northAvg = $northQuantity != 0 ? $northAvg / $northQuantity : $northAvg;
        $westAvg = $westQuantity != 0 ? $westAvg / $westQuantity : $westAvg;


//        found malfunctioning sensor and add log (NOTE)
        foreach ($sensorsArray as $k => $value) {
            $avgTemp = 0;
            switch ($value['face']) {
                case 'south':
                    $avgTemp = $southAvg;
                    break;
                case 'east':
                    $avgTemp = $eastAvg;
                    break;
                case 'north':
                    $avgTemp = $northAvg;
                    break;
                case 'west':
                    $avgTemp = $westAvg;
                    break;
            }
            $percentage = (($value['temperature'] - $avgTemp) / $avgTemp) * 100;
//            adding log
            if ($percentage > 20) {
                Log::info('The sensor Id - "' . $value['id'] . '" is a sensor which deviates more than 20% of the rest');

            }

        }
//        Store  Malfunctioning Sensor data to DB ( avg data from 4 faces )
        MalfunctioningSensor::insertMalfunctioningSensors($faultSensorsArray);

//        Store data to DB ( avg data from 4 faces )

        $dataToStore = [
            [
                'face' => "south",
                'temperature' => $southAvg,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'face' => "east",
                'temperature' => $eastAvg,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'face' => "north",
                'temperature' => $northAvg,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'face' => "west",
                'temperature' => $westAvg,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]

        ];
        $res = SidesTemperature::insert($dataToStore);

        if ($res) {
            return response()->json([
                'status' => 'success',
                'data' => Carbon::now()
            ]);
        }
        return response()->json([
            'status' => 'fail',
            'message' => 'failed to create temperature records'
        ]);


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
