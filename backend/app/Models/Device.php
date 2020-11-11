<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Device extends Model
{
    public $table = 'devices';

    public function measurement(){
        return $this->hasMany('App\Models\Measurement', 'deviceID');
    }


    use HasFactory, Notifiable;
}
