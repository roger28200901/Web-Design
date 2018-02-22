<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Place;
use App\Schedule;

class RoutesController extends Controller
{
    private $schedule_tables,
            $routes,
            $temporary_schedules;

    /**
     * Prepare places and schedules will used.
     *
     * @return void
     */
    public function __construct()
    {
        /* Getting all schedules and push into array with places information */
        Schedule::with('from_place', 'to_place')->get()->map(function ($schedule) {
            $this->schedule_tables[$schedule->from_place_id][$schedule->to_place_id][] = $schedule->toArray();
        });

        /* Initializing routes */
        $this->routes = array();

        /* Initializing temporary schedules */
        $this->temporary_schedules = array(); // Using tmemporary schedules as schedules buffer
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
        /* Setting expected count of routes */
        $count_of_routes = 5;

        /* Using current time as default departure time */
        if (!$departure_time) {
            $departure_time = date('H:i:s');
        }

        /* Retriving all routes via from place, to place and departure_time */
        $this->retrieveRoutes($from_place_id, $to_place_id, $departure_time);

        /* Getting sorted routes via count of routes */
        $routes = $this->sortRoutes($count_of_routes);

        /* Return routes information */
        return response()->json($routes);
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
     * Using DFS (Depth-First-Search) algorithm to retrieve every schedule.
     *
     * @param string $current_place_id
     * @param string $destination_place_id
     * @param string $arrival_time
     * @return void
     */
    private function retrieveRoutes($current_place_id, $destination_place_id, $arrival_time)
    {
        /* Parsing into stack while current place equals to destination place */
        if ($current_place_id === $destination_place_id) {
            /* Compacting temporary schedules into expected route data */
            $current_route = collect($this->temporary_schedules)->map(function ($schedule) {
                /* Calculating travel time via departure time and arrival time */
                $departure_time = new \DateTime($schedule['departure_time']);
                $arrival_time = new \DateTime($schedule['arrival_time']);
                $travel_time = $departure_time->diff($arrival_time)->format('%H:%I:%S');

                /* Compacting expected places data */
                $from_place = $schedule['from_place'];
                $to_place = $schedule['to_place'];
                unset($from_place['place_id'], $to_place['place_id']);

                /* Compacting expected schedule data into result */
                $result = array(
                    'id' => $schedule['id'],
                    'type' => $schedule['type'],
                    'line' => $schedule['line'],
                    'departure_time' => $schedule['departure_time'],
                    'arrival_time' => $schedule['arrival_time'],
                    'travel_time' => $travel_time,
                    'from_place' => $from_place,
                    'to_place' => $to_place
                );
                return $result;
            })->all();

            /* Pushing into routes stack */
            array_push($this->routes, $current_route);

            return;
        }

        /* Handling expected errors */
        if (!isset($this->schedule_tables[$current_place_id]) || collect($this->temporary_schedules)->where('from_place_id', $current_place_id)->count()) {
            return;
        }

        /* Retrieving all feasible routes via current place */
        foreach ($this->schedule_tables[$current_place_id] as $schedule_table) {
            /* Collecting places group by type and sort by departure time which are still time */
            $schedule_types = collect($schedule_table)->where('departure_time', '>', $arrival_time)->sortBy('departure_time')->groupBy('type')->all();

            /* Retrieving all type of schedules */
            foreach ($schedule_types as $schedule_type) {
                $next_schedule = $schedule_type[0]; // Getting next schedule of first schedule in this type
                array_push($this->temporary_schedules, $next_schedule); // Pushing into temporary schedules stack

                /* Traveling to next place */
                $this->retrieveRoutes($next_schedule['to_place_id'], $destination_place_id, $next_schedule['arrival_time']);

                array_pop($this->temporary_schedules); // Popping next schedule which have to update in the next step
            }
        }
    }

    /**
     * Sort routes.
     *
     * @param int $count_of_routes
     * @return array $sorted_routes
     */
    private function sortRoutes($count_of_routes = 0)
    {
        /* Sorting routes */
        $sorted_routes = $this->routes;
        usort($sorted_routes, function ($first_route, $second_route) {
            return end($first_route)['arrival_time'] <=> end($second_route)['arrival_time'];
        });

        /* Returning expected count of routes */
        if ($count_of_routes) {
            return array_slice($sorted_routes, 0, $count_of_routes);
        }

        /* Returning all routes by default */
        return $sorted_routes;
    }
}
