<?php

namespace App\Services\v1;

use Validator;

class Service
{

    protected $supportedIncludes = [];

    protected $clauseProperties = [];

    protected $rules = [];

    protected $select = [];

    protected $pagination = [
        'perpage'=>10,
        'page'=>1
    ];

    public function validate($model)
    {
        $validator = Validator::make($model, $this->rules);
        $validator->validate();
    }

    public function get($parameters)
    {
        if (empty($parameters)) {
            $data = $this->filter($this->model::All());
            return $data;
        }

        $withKeys = $this->getWithKeys($parameters);
        $whereClauses = $this->getWhereClauses($parameters);
        $pagination = $this->getPagination($parameters);
        $orderByClauses = $this->getOrderBy($parameters);
        $groupByClauses = $this->getGroupByClauses($parameters);
        $data = $this->model::with($withKeys)
            ->where($whereClauses);
        if (in_array('orderby',$parameters)) {
            $data->orderBy($orderByClauses['attr'], $orderByClauses['ordertype']);
        };
        if (in_array('groupby', $parameters)) {
            $data->groupBy($groupByClauses);
        }
        $total = $data->count();
        $data = $data->paginate(
            $pagination['perpage'],
            ['*'],
            $this->model,
            $pagination['page']
        );
        $data = $this->filter($data, $withKeys);
        if ($total<=1) {
            return ['data'=>$data];
        }
        return ['data'=>$data,'meta'=>$this->getMetas($data, $total, $pagination)];
    }

    public function delete($parameters)
    {
        $flight = $this->modelClass::where($this->modelClass->pkey, $parameters)->firstOrFail();
        $flight->delete();
    }    

    protected function getGroupByClauses($parameters)
    {
        $clause = [];
        if (isset($parameters['groupby'])) {
            $includeParams = explode(',',$parameters['groupby']);
            $includes = array_intersect($this->clauseProperties, $includeParams);
            $clause = array_keys($includes);
        }
        return $clause;
    }

    protected function getOrderBy($parameters)
    {
        $orderBy = [];
        if (isset($parameters['orderby'])) {
            if (in_array($parameters['orderby'], $this->clauseProperties)) {
                $orderBy['attr'] = $parameters['orderby'];
            }
        }
        if (isset($parameters['ordertype'])) {
            $direction = $parameters['ordertype'];
            if ($direction == 'asc') {
                $orderBy['ordertype'] = 'asc';
            } else {
                $orderBy['ordertype'] = 'desc';
            }
        } else {
            $orderBy['ordertype'] = 'asc';
        }
        return $orderBy;
    }

    protected function getWithKeys($parameters){

        $withKeys = [];
        if (isset($parameters['include'])) {
            $includeParams = explode(',',$parameters['include']);
            $includes = array_intersect($this->supportedIncludes, $includeParams);
            $withKeys = array_keys($includes);
        }
        return $withKeys;        
    }

    protected function getWhereClauses($parameters){

        $clause = [];
        foreach ($this->clauseProperties as $prop) {
            if (in_array($prop, array_keys($parameters))) {
                $clause[$prop] = $parameters[$prop];
            }
        }

        return $clause;
    }

    protected function getPagination($parameters)
    {
        $pagination = [];
        if (isset($parameters['perpage'])) {
            $pagination['perpage'] = $parameters['perpage'];
        } else {
            $pagination['perpage'] = $this->pagination['perpage'];
        }
        if (isset($parameters['page'])) {
            $pagination['page'] = $parameters['page'];
        } else {
            $pagination['page'] = $this->pagination['page'];
        }
        return $pagination;
    }

    //TODO agregar otras rutas a los metas como primera, ultima, siguiente y pagina anterior
    protected function getMetas($data, $total, $pagination)
    {
        $meta=[
            'total' => $total,
            'current_page' => $pagination['page'],
            'per_page' => $pagination['perpage'],
            'total_pages' => round($total/$pagination['perpage'])==0?1:round($total/$pagination['perpage']),
        ];
        return $meta;
    }
}
