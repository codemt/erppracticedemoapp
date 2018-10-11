<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class CustomerMasterRequest extends FormRequest
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
                $rules = [
                    'name' => 'required|unique:customer_masters,name,NULL,id,deleted_at,NULL',
                    'person_name' => 'required',
                    'person_email' => 'required',
                    'person_phone' => 'required',
                    'gst_no' => 'required',
                    'pan_no' => 'required',
                    'company_id' => 'required',
                ]; 
                foreach(Input::get('shipping.shipping') as $key => $val)
                {
                    $rules['shipping.shipping.'.$key.'.title'] = 'required';
                    $rules['shipping.shipping.'.$key.'.area'] = 'required';
                    $rules['shipping.shipping.'.$key.'.address'] = 'required';
                    $rules['shipping.shipping.'.$key.'.country_id'] = 'required';
                    $rules['shipping.shipping.'.$key.'.state_id'] = 'required';
                    $rules['shipping.shipping.'.$key.'.city_id'] = 'required';
                    $rules['shipping.shipping.'.$key.'.pincode'] = 'required';
                }
                return $rules;
            }
            case 'PATCH':
            {

                $rules = [
                    'name' => 'required|unique:customer_masters,name,'.$this->segment(3).',id,deleted_at,NULL',
                    'person_name' => 'required',
                    'person_email' => 'required',
                    'person_phone' => 'required',
                    'gst_no' => 'required',
                    'pan_no' => 'required',
                    'company_id' => 'required',
                ]; 
                foreach(Input::get('shipping.shipping') as $key => $val)
                {
                    $rules['shipping.shipping.'.$key.'.title'] = 'required';
                    $rules['shipping.shipping.'.$key.'.area'] = 'required';
                    $rules['shipping.shipping.'.$key.'.address'] = 'required';
                    $rules['shipping.shipping.'.$key.'.country_id'] = 'required';
                    $rules['shipping.shipping.'.$key.'.state_id'] = 'required';
                    $rules['shipping.shipping.'.$key.'.city_id'] = 'required';
                    $rules['shipping.shipping.'.$key.'.pincode'] = 'required';
                }
                return $rules;
            }
        }
    }

    public function messages()
    {
        $messages = [
            'name.required' => 'Name should not be blank.',
            'person_name.required' => 'Contact person name should not be blank.',
            'person_email.required' => 'Contact person email should not be blank.',
            'person_phone.required' => 'Contact person phone should not be blank.',
            'gst_no.required' => 'Gst no should not be blank.',
            'pan_no.required' => 'Pan no should not be blank.',
            'company_id.required' => 'Company should not be blank.'
        ];

        foreach(Input::get('shipping.shipping') as $key => $val)
        {
            $messages['shipping.shipping.'.$key.'.title.required'] = 'Title should not be blank.';
            $messages['shipping.shipping.'.$key.'.area.required'] = 'Area should not be blank.';
            $messages['shipping.shipping.'.$key.'.address.required'] = 'Address should not be blank.';
            $messages['shipping.shipping.'.$key.'.country_id.required'] = 'Country must be select.';
            $messages['shipping.shipping.'.$key.'.state_id.required'] = 'State must be select.';
            $messages['shipping.shipping.'.$key.'.city_id.required'] = 'City must be select.';
            $messages['shipping.shipping.'.$key.'.pincode.required'] = 'Pincode should not be blank.';
        }
        return $messages;
    }
}
