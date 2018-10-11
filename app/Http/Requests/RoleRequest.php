<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
         
          // 'import_file' => 'required|mimes:xls,xlsx,docs,csv', 
 
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
      'name.required' => 'Name should not be blank', 
      'name.string' => 'Name can only character', 
      'description.required' => 'description should not be blank', 
      'description.string' => 'description can only character', 
     
    ]; 
  } 
}
