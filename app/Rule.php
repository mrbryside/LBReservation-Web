<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'rules';
    protected $primaryKey = 'rule_id';
    public $timestamps = true;
}
