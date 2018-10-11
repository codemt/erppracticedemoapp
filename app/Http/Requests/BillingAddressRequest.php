<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingAddressRequest extends FormRequest
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
                'title' => 'required',
                'address' => 'required',
                'area' => 'required',
                'state_id' => 'required',
                'city_id' => 'required',
                'country_id' => 'required',
                'pincode' => 'required'
                ]; 
            }
            case 'PATCH':
            {
                return [
                'title' => 'required',
                'address' => 'required',
                'area' => 'required',
                'state_id' => 'required',
                'city_id' => 'required',
                'country_id' => 'required',
                'pincode' => 'required'
                ]; 
            }
            default : break;
        }
    }

    public function messages()
    {
        return[
            'title.required' => 'Title should not be blank.',
            'address.required' => 'Address should not be blank.',
            'area.required' => 'Area should not be blank.',
            'company_id.required' => 'Company name should not be blank.',
            'state_id.required' => 'State should not be blank.',
            'city_id.required' => 'City should not be blank.',
            'pincode.required' => 'Pincode should not be blank.',
            'country_id.required' => 'Country should not be blank.',

        ];
    }

}
