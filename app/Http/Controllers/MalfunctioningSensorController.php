<?php

namespace App\Http\Controllers;

use App\Models\MalfunctioningSensor;
use Illuminate\View\View;

class MalfunctioningSensorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $malfunctioning = MalfunctioningSensor::paginate(10);




        return view('malfunctioning.index')->with(array('malfunctioning' => $malfunctioning));
    }
}
