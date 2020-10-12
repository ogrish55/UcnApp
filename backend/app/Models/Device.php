<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Device extends Model
{
    public $table = 'devices';

    public function household(){
        return $this->belongsTo('App\Models\Household');
    }

    public function measurement(){
        return $this->hasMany('App\Models\Measurement');
    }

    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deviceId',
        'householdId',
    ];
}
