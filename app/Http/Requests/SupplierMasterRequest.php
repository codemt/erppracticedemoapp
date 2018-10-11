<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class SupplierMasterRequest extends FormRequest
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
        //dd($this->all());
        switch ($this->method()) {

            case 'POST':
            {
                $rules = [
                'supplier_name' => 'required|unique:supplier_masters,supplier_name,NULL,id,deleted_at,NULL|regex:'.config('regex.name'),
                'spoc_name' => 'required',
                'spoc_email' => 'required',
                'spoc_phone' => 'required',
                'gst_no' => 'required',
                'pan_no' => 'required',
                'bankname' => 'required',
                'ac_number' => 'required',
                'ifsc_code' => 'required',
                'branch' => 'required',
                'company_id' => 'required',
                // 'shipping.shipping.*.address' => 'required',
                // 'shipping.shipping.*.country_id' => 'required',
                // 'shipping.shipping.*.state_id' => 'required',
                // 'shipping.shipping.*.city_id' => 'required',
                // 'shipping.shipping.*.pincode' => 'required|numeric:6',
                ]; 
                foreach(Input::get('shipping.shipping') as $key => $val)
                {
                    // dd($val);
                    $rules['shipping.shipping.'.$key.'.title'] = 'required';
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
                'supplier_name' => 'required|unique:supplier_masters,supplier_name,'.$this->segment(3).',id,deleted_at,NULL|regex:'.config('regex.name'),
                'spoc_name' => 'required',
                'spoc_email' => 'required',
                'spoc_phone' => 'required',
                'gst_no' => 'required',
                'pan_no' => 'required',
                'bankname' => 'required',
                'ac_number' => 'required',
                'ifsc_code' => 'required',
                'company_id' => 'required',
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
    }

    public function messages()
    {
        $messages = [
            'supplier_name.required' => 'Manufacturer name should not be blank.',
            'supplier_name.regex' => 'Manufacturer name must be character.',
            'spoc_name.required' => 'SPOC name should not be blank.',
            'spoc_email.required' => 'SPOC email should not be blank.',
            'spoc_phone.required' => 'SPOC phone should not be blank.',
            'gst_no.required' => 'Gst no should not be blank.',
            'pan_no.required' => 'Pan no should not be blank.',
            'bankname.required' => 'Bank name should not be blank.',
            'ac_number.required' => 'AC no should not be blank.',
            'ifsc_code.required' => 'IFSC code should not be blank.',
            'branch.required' => 'Branch should not be blank.',
            'company_id.required' => 'Company should not be blank.'

        ];

        foreach(Input::get('shipping.shipping') as $key => $val)
        {
            $messages['shipping.shipping.'.$key.'.title.required'] = 'Title should not be blank.';
            $messages['shipping.shipping.'.$key.'.address.required'] = 'Address should not be blank.';
            $messages['shipping.shipping.'.$key.'.country_id.required'] = 'Country must be select.';
            $messages['shipping.shipping.'.$key.'.state_id.required'] = 'State must be select.';
            $messages['shipping.shipping.'.$key.'.city_id.required'] = 'City must be select.';
            $messages['shipping.shipping.'.$key.'.pincode.required'] = 'Pincode should not be blank.';
        }
        return $messages;
    }
}
