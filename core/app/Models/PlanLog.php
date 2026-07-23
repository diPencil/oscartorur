<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanLog extends Model
{

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
    
    public function seminar()
    {
        return $this->belongsTo(Seminar::class, 'plan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
