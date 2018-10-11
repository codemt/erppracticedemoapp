<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesignationRequest extends FormRequest
{

    public function authorize(){ 
        return true; 
    } 
 
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
     public function rules() { 
    switch ($this->method()) { 
    case 'POST': 
      { 
        return [ 
          'name' => 'required|max:50|string', 
          'description' => 'required|string',  
 
        ]; 
        break; 
      } 
    case 'PATCH': 
      { 
        return [ 
          'name' => 'required | string', 
          'description' => 'required|string', 
 
        ]; 
        break; 
      } 
    } 
    return $rules; 
  } 
  public function messages() { 
    return [ 
      'name.required' => 'Designation Name should not be blank', 
      'name.string' => 'Designation Name can only character', 
      'description.required' => 'Description should not be blank', 
      'description.string' => 'Description can only character', 
     
    ]; 
  } 
}
