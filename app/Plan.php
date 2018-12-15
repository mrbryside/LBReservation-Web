<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'singleplans';
    protected $primaryKey = 'SinglePlanID';
    public $timestamps = true;

}
