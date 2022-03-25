<?php

namespace App\Services\v1;
use App\Models\Flight;
use App\Models\Airport;

class FlightService extends Service implements ServicesInterface
{

    protected $model;

    protected $supportedIncludes = [
        'arrivalAirport' => 'arrival',
        'departureAirport' => 'departure',
        'Pasajeros' => 'pasajeros'
    ];

    protected $clauseProperties = [
        'status',
        'flightNumber'
    ];

    protected $rules = [
        'flightNumber' => 'required',
        'status' => 'required|flightstatus',
        'arrival.datetime' => 'required|date',
        'arrival.iataCode' => 'required',
        'departure.datetime' => 'required|date',
        'departure.iataCode' => 'required'
    ];
    
    public function __construct(Flight $model=null)
    {
        if ($model==null) {
            $this->model = new Flight();
        } else {
            $this->model = $model;
        }         
    }

    public function create($request)
    {
        $arrivalAirport = $request->input('arrival.iataCode');
        $departureAirport = $request->input('departure.iataCode');

        $airports = Airport::whereIn('iatacode',[$arrivalAirport,$departureAirport])->get();

        $codes = [];

        foreach ($airports as $port) {
            $codes[$port->iataCode] = $port->id;
        }

        $flight = new $this->model;
        $flight->flightNumber = $request->input('flightNumber');
        $flight->status = $request->input('status');
        $flight->arrivalAirport_id = $codes[$arrivalAirport];
        $flight->arrivalDateTime = $request->input('arrival.datetime');
        $flight->departureAirport_id = $codes[$departureAirport];
        $flight->departureDateTime = $request->input('departure.datetime');

        $flight->save();

        return $this->filter([$flight]);
    }

    public function update($request, $paramaters)
    {
        $flight = Flight::where('flightNumber', $paramaters)->firstOrFail();
        
        $arrivalAirport = $request->input('arrival.iataCode');
        $departureAirport = $request->input('departure.iataCode');

        $airports = Airport::whereIn('iatacode',[$arrivalAirport,$departureAirport])->get();

        $codes = [];

        foreach ($airports as $port) {
            $codes[$port->iataCode] = $port->id;
        }

        $flight->flightNumber = $request->input('flightNumber');
        $flight->status = $request->input('status');
        $flight->arrivalAirport_id = $codes[$arrivalAirport];
        $flight->arrivalDateTime = $request->input('arrival.datetime');
        $flight->departureAirport_id = $codes[$departureAirport];
        $flight->departureDateTime = $request->input('departure.datetime');

        $flight->save();

        return $this->filter([$flight]);
    }

    public function delete($paramaters)
    {
        $flight = Flight::where('flightNumber', $paramaters)->firstOrFail();
        $flight->delete();
    }

    public static function filter($flights, $keys = []){

        $data = [];
        foreach ($flights as $flight) {
            $entry = [
                'flightNumber' => $flight->flightNumber,
                'status' => $flight->status,
                '_links' =>[
                    'self'=> [
                        'href' => route('flights.show',['flight'=>$flight->flightNumber]),
                    ],
                    'deleteById'=>[
                        'href' => route('flights.destroy',['flight'=>$flight->flightNumber]),
                    ],
                    'updateById'=>[
                        'href' => route('flights.update',['flight'=>$flight->flightNumber]),
                    ]
                ],                 
            ];

            if (in_array('arrivalAirport', $keys)) {
                $entry['arrival'] = [
                    'datetime' => $flight->arrivalDateTime,
                    'iataCode' => $flight->arrivalAirport->iataCode,
                    'city' => $flight->arrivalAirport->city,
                    'state' => $flight->arrivalAirport->state,
                ];
            }
            
            if (in_array('departureAirport', $keys)) {
                $entry['departure'] = [
                    'datetime' => $flight->departureDateTime,
                    'iataCode' => $flight->departureAirport->iataCode,
                    'city' => $flight->departureAirport->city,
                    'state' => $flight->departureAirport->state,
                ];
            }

            if (in_array('Pasajeros', $keys)) {
                $entry['pasajeros'] = CostumerService::filter($flight->Pasajeros);
            }

            $data[] = $entry;
        }
        return $data;
    }

}
