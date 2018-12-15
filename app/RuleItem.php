<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RuleItem extends Model
{
    protected $table = 'ruleitems';
    protected $primaryKey = 'ruleItem_id';
    public $timestamps = true;
}
