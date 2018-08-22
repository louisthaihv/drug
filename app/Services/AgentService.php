<?php

namespace App\Services;


use App\Models\Agent;
use Yajra\DataTables\DataTables;

class AgentService
{
    protected $model;

    protected $dataTables;

    public $status = [
        AGENT_INACTIVE => 'Chưa kích hoạt',
        AGENT_ACTIVE => 'Đang hoạt động'
    ];
    public function __construct(Agent $agent, DataTables $dataTables)
    {
        $this->model = $agent;
        $this->dataTables = $dataTables;
    }

    public function all(){
        return $this->model->pluck('agent_name', 'id');
    }


    public function getJsonData(){
        return $this->dataTables->of($this->model->with('users'))
            ->addColumn('statusName', function($agent){
                return $this->status[$agent->status];
            })
            ->make(true);
    }

    public function store($data){
        return $this->model->fill($data)->save();
    }

    public function destroy($agent){
        return $agent->delete();
    }

    public function findByid($id){
        $agent = $this->model->find($id);
        return $agent ? $agent : false;
    }

    public function update($agent, $data) {
        return $agent->fill($data)->save();
    }
}
