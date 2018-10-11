<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    protected $table = 'countries';

    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    protected $fillable = ['id', 'title','slug']; 

    public function getStateAttribute($value) 
    { 
        return $this->attribute = ucwords($value); 
    }  

    public function category() 
    { 
        return $this->belongsTo(State::class); 
    }
}
