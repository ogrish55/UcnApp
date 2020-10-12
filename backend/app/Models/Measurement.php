<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Measurement extends Model
{
    public $table = 'measurements';

    public function device(){
        return $this->belongsTo('App\Models\Device');
    }

    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deviceId',
        'meterTyper',
        'readingType',
        'measurement',
        'value'
    ];
}
