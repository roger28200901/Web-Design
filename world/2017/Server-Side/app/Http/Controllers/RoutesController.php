<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoutesController extends Controller
{
    /**
     * Display a listing of the route.
     *
     * @param int $from_place_id
     * @param int $to_place_id
     * @param string $departure_time
     * @return \Illuminate\Http\Response
     */
    public function search($from_place_id, $to_place_id, $departure_time = '')
    {
        //
    }

    /**
     * Store a newly created history resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
}
