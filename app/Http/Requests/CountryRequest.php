<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
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
    public function rules()
    {
        switch ($this->method()) { 
            case 'POST': 
            { 
                return [ 
                    'title' => 'required|max:50|alpha'

                ]; 
                break; 
            } 
            case 'PATCH': 
            { 
                return [ 
                    'title' => 'required | alpha'  

                ]; 
                break; 
            } 
        } 
        return $rules; 
    } 

    public function messages() { 
        return [ 
            'title.required' => 'country should not be blank', 
            'title.alpha' => 'country can only character'
        ]; 
    } 
}
