<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
	use SoftDeletes;
    protected $table = 'states';

    protected $dates = ['deleted_at'];
	protected $softDelete = true;

	protected $fillable = ['id', 'title','slug','country_id']; 
 
  public function getStateAttribute($value) 
  { 
    return $this->attribute = ucwords($value); 
  }  
 
  public function category() 
    { 
      return $this->belongsTo(Country::class); 
    } 
}
