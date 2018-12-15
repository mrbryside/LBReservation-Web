<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usermodel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'Userid';
    public $timestamps = true;

}