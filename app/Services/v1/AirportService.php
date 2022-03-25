<?php
namespace App\Services\v1;

use App\Models\Airport;
use App\Services\v1\Service;

class AirportService extends Service implements ServicesInterface
{
    protected $model;

    protected $supportedIncludes = [
        'commingFlights' => 'arrival',
        'departingFlight' => 'departure'
    ];
    protected $clauseProperties = [
        'state',
        'city',
        'iataCode'
    ];

    protected $rules = [
        'city' => 'required',
        'state' => 'required',
        'iataCode' => 'required|unique:airports,iataCode'
    ];

    public function __construct(Airport $model = null)
    {
        if ($model==null) {
            $this->model = new Airport();
        } else {
            $this->model = $model;
        }
    }

    public function create($request)
    {
        $airport = new $this->model;
        $airport->iataCode = $request->input('iataCode');
        $airport->city = $request->input('city');
        $airport->state = $request->input('state');
        $airport->save();

        return $this->filter([$airport]);
    }

    public function update($request, $paramaters)
    {   
        $airport = $this->model::where('iataCode', $paramaters)->firstOrFail();
        $airport->iataCode = $request->input('iataCode');
        $airport->city = $request->input('city');
        $airport->state = $request->input('state');

        $airport->save();
        
        return $this->filter([$airport]);
    }
    
    public static function filter($airports, $keys =[])
    {
        $data = [];
        foreach ($airports as $airport) {
            $entry = [
                'iataCode' => $airport->iataCode,
                'city' => $airport->city,
                'state' => $airport->state,
                '_links' =>[
                    'self'=> [
                        'href' => route('airports.show',['airport'=>$airport->iataCode]),
                    ],
                    'deleteById'=>[
                        'href' => route('airports.destroy',['airport'=>$airport->iataCode]),
                    ],
                    'updateById'=>[
                        'href' => route('airports.update',['airport'=>$airport->iataCode]),
                    ]
                ],                
            ];
            if (in_array('commingFlights', $keys)) {
                $entry['arrival'] = FlightService::filter($airport->commingFlights);
            }
            if (in_array('departingFlight', $keys)) {
                $entry['departure'] = FlightService::filter($airport->departingFlight);
            }
            $data[] = $entry;
        }
        return $data;
    }
}
