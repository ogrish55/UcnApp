<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Household extends Model
{
    public $table = 'household';

    public function device()
    {
        return $this->hasMany('App\Models\Device');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'householdID',
        'userID',
    ];
}
