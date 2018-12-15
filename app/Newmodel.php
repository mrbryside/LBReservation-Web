<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newmodel extends Model
{
    protected $table = 'news';
    protected $primaryKey = 'Newid';
    public $timestamps = true;
}
