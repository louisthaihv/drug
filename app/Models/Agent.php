<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use SoftDeletes;
    protected $table = 'agents';

    protected $guarded = ['id'];

    public function users(){
        return $this->hasMany(User::class, 'agent_id', 'id');
    }

}
