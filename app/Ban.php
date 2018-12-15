<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    protected $table = 'bans';
    protected $primaryKey = 'BanID';
    public $timestamps = true;

    public function user(){
    	return $this->belongsTo('App\User');
    }
}