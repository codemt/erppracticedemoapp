<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
   
     protected $table = 'cities'; 
     protected $fillable = ['id', 'title','slug','state_id']; 
 
  public function getCityAttribute($value) 
  { 
    return $this->attribute = ucwords($value); 
  }  
 
  public function subcategory() 
    { 
      return $this->hasMany(State::class); 
    } 
}
