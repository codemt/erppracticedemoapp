<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $rules = [
            'company_name' => 'required',
            'billing_address' => 'required',
            'spoc_name' => 'required',
            'spoc_email' => 'required',
            'spoc_phone' => 'required',
            'gst_no' => 'required',
            'pan_no' => 'required',
            'bankname' => 'required',
            'ac_number' => 'required',
            'ifsc_code' => 'required',
            'billing_pincode' => 'required',
            'branch' => 'required',
            'shipping_name' => 'required',
            'shipping_email' => 'required',
            'shipping_phone' => 'required',
        ];
        foreach(Input::get('shipping.shipping') as $key => $val)
        {
            $rules['shipping.shipping.'.$key.'.title'] = 'required';
            $rules['shipping.shipping.'.$key.'.address'] = 'required';
            $rules['shipping.shipping.'.$key.'.country_id'] = 'required';
            $rules['shipping.shipping.'.$key.'.state_id'] = 'required';
            $rules['shipping.shipping.'.$key.'.city_id'] = 'required';
            $rules['shipping.shipping.'.$key.'.pincode'] = 'required';
        }
        return $rules;

    }
}
