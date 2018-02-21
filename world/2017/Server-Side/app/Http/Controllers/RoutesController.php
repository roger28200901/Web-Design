<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Place;
use App\Schedule;

class RoutesController extends Controller
{
    private $places,
            $schedule_tables,
            $routes,
            $temporary_schedules;

    /**
     * Prepare places and schedules will used.
     *
     * @return void
     */
    public function __construct()
    {
        /* Getting all places, use place_id as array key */
        $this->places = Place::all()->keyBy('place_id');

        /* Getting all schedules and push into array with places infomation */
        Schedule::with('from_place', 'to_place')->get()->map(function ($schedule) {
            $this->schedule_tables[$schedule->from_place_id][$schedule->to_place_id][] = $schedule;
        });

        /* Initializing routes */
        $this->routes = collect();

        /* Initializing temporary schedules */
        $this->temporary_schedules = collect(); // Using tmemporary schedules as schedules buffer
    }

    /**
     * Display a listing of the route.
     *
     * @param string $from_place_id
     * @param string $to_place_id
     * @param string $departure_time
     * @return \Illuminate\Http\Response
     */
    public function search($from_place_id, $to_place_id, $departure_time = null)
    {
        $count_of_routes = 5;

        /* Using current time as default departure time */
        if (!$departure_time) {
            $departure_time = date('H:i:s');
        }

        /* Retriving all routes via from place, to place and departure_time */
        $this->retrieveRoutes($from_place_id, $to_place_id, $departure_time);

        $sorted_routes = $this->sortRoutes()->slice(0, $count_of_routes);

        $data = [];
        foreach ($sorted_routes as $route) {
            $number_of_history_selection = 0;
            $schedules = [];
            foreach ($route as $schedule) {
                $from_place = $this->places[$schedule->from_place_id]->toArray();
                $to_place = $this->places[$schedule->to_place_id]->toArray();
                unset($from_place['place_id'], $to_place['place_id']);

                $departure_time = new \DateTime($schedule->departure_time);
                $arrival_time = new \DateTime($schedule->arrival_time);

                $schedules[] = [
                    'id' => $schedule->id,
                    'type' => $schedule->type,
                    'line' => $schedule->line,
                    'departure_time' => $schedule->departure_time,
                    'arrival_time' => $schedule->arrival_time,
                    'travel_time' => $departure_time->diff($arrival_time)->format('%H:%I:%S'),
                    'from_place' => $from_place,
                    'to_place' => $to_place
                ];
            }
            $data[] = ['number of history selection' => $number_of_history_selection, 'schedules' => $schedules];
        }

        return response()->json($data[0]['schedules']);
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

    /**
     * Retrieve a listing of the route with expect count of routes.
     *
     * @param string $current_place_id
     * @param string $destination_place_id
     * @param string $arrival_time
     * @return void
     */
    private function retrieveRoutes($current_place_id, $destination_place_id, $arrival_time)
    {
        if ($current_place_id === $destination_place_id) {
            $this->routes->push(collect($this->temporary_schedules->all()));
            return;
        }

        if (!isset($this->schedule_tables[$current_place_id]) || $this->temporary_schedules->where('from_place_id', $current_place_id)->count()) {
            return;
        }

        foreach ($this->schedule_tables[$current_place_id] as $schedule_table) {
            $schedule_types = collect($schedule_table)->where('departure_time', '>', $arrival_time)->groupBy('type');
            foreach ($schedule_types as $schedule_type) {
                $next_schedule = $schedule_type->first();
                $this->temporary_schedules->push($next_schedule);
                $this->retrieveRoutes($next_schedule->to_place_id, $destination_place_id, $next_schedule->arrival_time);
                $this->temporary_schedules->pop();
            }
        }
    }

    /**
     * Sort routes.
     *
     * @return Illuminate\Support\Collection $sorted_routes
     */
    private function sortRoutes()
    {
        $sorted_routes = $this->routes->all();
        usort($sorted_routes, function ($first_schedule, $second_schedule) {
            return $first_schedule->last()->arrival_time <=> $second_schedule->last()->arrival_time;
        });

        return collect($sorted_routes);
    }
}
