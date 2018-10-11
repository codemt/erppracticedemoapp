<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class CompanyMasterRequest extends FormRequest
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

    public function messages()
    {
        $messages = [
            'company_name.required' => 'Company name should not be blank.',
            'billing_address.required' => 'Address should not be blank.',
            'spoc_name.required' => 'SPOC name should not be blank.',
            'spoc_email.required' => 'SPOC email should not be blank.',
            'spoc_phone.required' => 'SPOC phone should not be blank.',
            'gst_no.required' => 'Gst no should not be blank.',
            'pan_no.required' => 'Pan no should not be blank.',
            'bankname.required' => 'Bank name should not be blank.',
            'ac_number.required' => 'AC no should not be blank.',
            'ifsc_code.required' => 'IFSC code should not be blank.',
            'branch.required' => 'Branch should not be blank.',
            'billing_pincode' => 'Pincode should not be blank.',
            'shipping_name.required' => 'SPOC name should not be blank.',
            'shipping_email.required' => 'SPOC email should not be blank.',
            'shipping_phone.required' => 'SPOC phone should not be blank.',
        ];
        foreach(Input::get('shipping.shipping') as $key => $val)
        {
            $messages['shipping.shipping.'.$key.'.title.required'] = 'Title should not be blank.';
            $messages['shipping.shipping.'.$key.'.address.required'] = 'Address should not be blank.';
            $messages['shipping.shipping.'.$key.'.country_id.required'] = 'Country must be select.';
            $messages['shipping.shipping.'.$key.'.state_id.required'] = 'State must be select.';
            $messages['shipping.shipping.'.$key.'.city_id.required'] = 'State must be select.';
            $messages['shipping.shipping.'.$key.'.pincode.required'] = 'Pincode should not be blank.';
        }
        return $messages;
    }
}
