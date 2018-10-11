<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request) { 
    switch ($this->method()) { 
    case 'POST': 
      { 
        return [ 
          'title' => 'required|max:50|string|unique:states,title', 
         
          // 'import_file' => 'required|mimes:xls,xlsx,docs,csv', 
 
        ]; 
        break; 
      } 
    case 'PATCH': 
      { 
        $id = \Request::segment(3);
        return [ 
          'title' => 'required | string|unique:states,title,'.$id, 
          
 
        ]; 
        break; 
      } 
    } 
    return $rules; 
  } 
  public function messages() { 
    return [ 
      'title.required' => 'State Name should not be blank', 
      'title.string' => 'State Name can only character', 
      'title.unique' => 'State Name Should be unique', 
     
    ]; 
  } 
}
