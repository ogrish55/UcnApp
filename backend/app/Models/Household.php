<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Household extends Model
{
    public $table = 'households';
    public $incrementing = false;
    public $primaryKey = 'householdID';

    public function device()
    {
        return $this->hasMany('App\Models\Device', 'householdID');
    }

    use HasFactory, Notifiable;
}
