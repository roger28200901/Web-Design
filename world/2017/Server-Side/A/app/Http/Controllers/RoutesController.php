<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Place;
use App\Schedule;
use App\History;

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
        /* Getting All Schedules And Push Into Array With Places Information */
        Schedule::with('from_place', 'to_place')->get()->map(function ($schedule) {
            $this->schedule_tables[$schedule->from_place_id][$schedule->to_place_id][] = $schedule->toArray();
        });

        /* Initializing Routes */
        $this->routes = array();

        /* Initializing Temporary Schedules */
        $this->temporary_schedules = array(); // Using tmemporary schedules as schedules buffer.
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
        /* Setting Expected Count Of Routes */
        $count_of_routes = 5;

        /* Using Current Time As Default Departure Time */
        if (!$departure_time) {
            $departure_time = date('H:i:s');
        }

        /* Retriving All Routes Via From Place, To Place And Departure Time */
        $this->retrieveRoutes($from_place_id, $to_place_id, $departure_time);

        /* Getting Sorted Routes Via Count Of Routes */
        $routes = $this->sortRoutes($count_of_routes);

        /* Return Routes Information */
        return response()->json($routes);
    }

    /**
     * Store a newly created history resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeHistory(Request $request)
    {
        /* Setting Validation Rules */
        $rules = [
            'from_place_id' => 'required|exists:places,place_id',
            'to_place_id' => 'required|exists:places,place_id',
            'schedule_id' => 'required|array'
        ];

        /* Generating Validator */
        $validator = Validator::make($request->all(), $rules);

        /* Throwing Validator Exception */
        if ($validator->fails()) {
            abort(422, 'Data cannot be processed');
        }

        $schedules = Schedule::find($request->schedule_id);

        /* Throwing Exception While Any Schedule ID In Array Is Invalid */
        if ($schedules->count() !== count($request->schedule_id)) {
            abort(422, 'Data cannot be processed');
        }

        /* Storing Into History */
        $data = $request->except('userRole', 'token');
        $data['schedule_id'] = json_encode($data['schedule_id']);
        $data['place_id'] = $schedules->pluck('from_place_id')->toArray();
        array_push($data['place_id'], $request->to_place_id);
        $data['place_id'] = json_encode($data['place_id']);
        History::create($data);

        /* Returning Response */
        return response()->json(['message' => 'create success']);
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
        /* Parsing Into Stack While Current Place Equals To Destination Place */
        if ($current_place_id === $destination_place_id) {
            /* Compacting Temporary Schedules Into Expected Route Data */
            $current_route = collect($this->temporary_schedules)->map(function ($schedule) {
                /* Calculating Travel Time Via Departure Time And Arrival Time */
                $departure_time = new \DateTime($schedule['departure_time']);
                $arrival_time = new \DateTime($schedule['arrival_time']);
                $travel_time = $departure_time->diff($arrival_time)->format('%H:%I:%S');

                /* Compacting Expected Places Data */
                $from_place = $schedule['from_place'];
                $to_place = $schedule['to_place'];
                unset($from_place['place_id'], $to_place['place_id']);

                /* Compacting Expected Schedule Data Into Result */
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

            /* Compacting Places ID As Constraint Array */
            $place_id = collect($this->temporary_schedules)->pluck('from_place_id')->toArray();
            array_push($place_id, $destination_place_id);

            /* Getting Count Of History Selection */
            $count_of_history_selection = History::where('place_id', json_encode($place_id))->count();

            /* Pushing Into Routes Stack */
            array_push($this->routes, array('count of history selection' => $count_of_history_selection, 'schedules' => $current_route));

            return;
        }

        /* Handling Expected Errors */
        if (!isset($this->schedule_tables[$current_place_id]) || collect($this->temporary_schedules)->where('from_place_id', $current_place_id)->count()) {
            return;
        }

        /* Retrieving All Feasible Routes Via Current Place */
        foreach ($this->schedule_tables[$current_place_id] as $schedule_table) {
            /* Collecting Places Group By Type And Sort By Departure Time Which Are Still Time */
            $schedule_types = collect($schedule_table)->where('departure_time', '>', $arrival_time)->sortBy('departure_time')->groupBy('type')->all();

            /* Retrieving All Type Of Schedules */
            foreach ($schedule_types as $schedule_type) {
                $next_schedule = $schedule_type[0]; // Getting next schedule of first schedule in this type
                array_push($this->temporary_schedules, $next_schedule); // Pushing into temporary schedules stack

                /* Traveling To Next Place */
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
        /* Sorting Routes */
        $sorted_routes = $this->routes;
        usort($sorted_routes, function ($first_route, $second_route) {
            return end($first_route['schedules'])['arrival_time'] <=> end($second_route['schedules'])['arrival_time'];
        });

        /* Returning Expected Count Of Routes */
        if ($count_of_routes) {
            return array_slice($sorted_routes, 0, $count_of_routes);
        }

        /* Returning All Routes By Default */
        return $sorted_routes;
    }
}
