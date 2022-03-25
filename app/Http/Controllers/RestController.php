<?php

namespace App\Http\Controllers;

use App\Services\v1\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class RestController extends Controller
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $parameters = request()->input();
        $result = $this->service->get($parameters);
        if (!isset($parameters['page']) && !isset($parameters['perpage'])) {
            return response()->json($result);
        }
        
        return response()->json(
            [
                'data'=>$result['data'],
                '_meta'=>$result['meta'],
                '_links'=>[
                    'self'=>[
                        'href'=>request()->fullUrl(),
                    ],
                    'getAll'=>[
                        'href'=>request()->url()
                    ],
                    'add'=>[
                        'href'=>request()->url()
                    ],
                    'getById'=>[
                        'href'=>request()->url().'/{'.$this->pkey.'}',
                        'template'=>true
                    ],
                    'deleteById'=>[
                        'href'=>request()->url().'/{'.$this->pkey.'}',
                        'template'=>true
                    ],
                    'updateById'=>[
                        'href'=>request()->url().'/{'.$this->pkey.'}',
                        'template'=>true
                    ]                    
                ]
            ]);
    }

    public function store(Request $request)
    {
        $this->service->validate($request->all());
        
        try {
            $data = $this->service->create($request);
            return response()->json($data, 201);
        } catch (Exception $e) {
            return response()->json(['message'=> $e->getMessage()], 500);
        }
    }
    
    public function show($id)
    {
        $parameters = request()->input();
        $parameters[$this->pkey] = $id;
        $data = $this->service->get($parameters);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $this->service->update($request, $id);
            return response()->json($data, 200);
        } catch (ModelNotFoundException $ex){
            throw $ex;
        }        
        catch (Exception $e) {
            return response()->json(['message'=> $e->getMessage()], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            //code...
            $data = $this->service->delete($id);
            return response()->make('', 204);
        } catch (ModelNotFoundException $ex){
            throw $ex;
        }        
        catch (Exception $e) {
            return response()->json(['message'=> $e->getMessage()], 500);
        }
    }
}
