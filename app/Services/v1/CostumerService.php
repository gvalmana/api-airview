<?php
namespace App\Services\v1;

use App\Models\Costumer;
use App\Services\v1\Service;
use App\Services\v1\ServicesInterface;
use Symfony\Component\VarDumper\Cloner\Data;

class CostumerService extends Service implements ServicesInterface
{

    protected $model;

    protected $supportedIncludes = [
        'Vuelos' => 'flights'
    ];
    protected $clauseProperties = [
        'passport',
        'firstname',
        'lastname'
    ];

    protected $rules = [
        'firstname' => 'required',
        'lastname' => 'required',
        'passport' => 'required|unique:costumers,passport'
    ];

    public function __construct(Costumer $model=null)
    {
        if ($model==null) {
            $this->model = new Costumer();
        } else {
            $this->model =  $model;
        }
        
    }

    public function create($request)
    {
        $costumer = new $this->model;
        $costumer->firstname = $request->input('firstname');
        $costumer->lastname = $request->input('lastname');
        $costumer->passport = $request->input('passport');
        $costumer->save();
        return $this->filter([$costumer]);
    }

    public function update($request, $paramaters)
    {
        $costumer = $this->model::where('passport', $paramaters)->firstOrFail();
        $costumer->firstname = $request->input('firstname');
        $costumer->lastname = $request->input('lastname');
        $costumer->passport = $request->input('passport');
        $costumer->save();
        return $this->filter([$costumer]);
    }

    public function delete($paramaters)
    {
        $costumer = $this->modelClass::where('passport', $paramaters)->firstOrFail();
        $costumer->delete();
    }

    public static function filter($costumers, $keys =[])
    {
        $data = [];
        foreach ($costumers as $costumer) {
            $entry = [
                'firstname' => $costumer->firstname,
                'lastname' => $costumer->lastname,
                'passport' => $costumer->passport,
                '_links' =>[
                    'self'=> [
                        'href' => route('costumers.show',['costumer'=>$costumer->passport]),
                    ],
                    'deleteById'=>[
                        'href' => route('costumers.destroy',['costumer'=>$costumer->passport]),
                    ],
                    'updateById'=>[
                        'href' => route('costumers.update',['costumer'=>$costumer->passport]),
                    ]
                ],                 
            ];
            if (in_array('Vuelos', $keys)) {
                $entry['flights'] =FlightService::filter($costumer->Vuelos);
            }
            $data[] = $entry;
        }
        return $data;
    }

}
