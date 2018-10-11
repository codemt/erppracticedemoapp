<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class PurchaseRequisitionRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $data = $request->all();
        switch ($this->method()) {
           case 'POST':
            {
                $rules = [
                    'company_id' => 'required',
                    'supplier_id' => 'required',
                    'delivery_terms' => 'required'
                ];

                foreach(Input::get('shipping.shipping') as $key => $val)
                {
                    $rules['shipping.shipping.'.$key.'.qty'] = 'required';
                    if($data['company_id'] != '' && $data['supplier_id'] != ''){
                        $rules['shipping.shipping.'.$key.'.model_no'] = 'required';
                    }
                }
                return $rules;
            }

            case 'PATCH':
            {
                $rules = [
                    'delivery_terms' => 'required'
                ];

                foreach(Input::get('shipping.shipping') as $key => $val)
                {
                    $rules['shipping.shipping.'.$key.'.qty'] = 'required';
                    $rules['shipping.shipping.'.$key.'.model_no'] = 'required';
                }
                return $rules;
            }

       }
    }

    public function messages(){
        $messages = [
            'company_id.required' => 'Select Company Name',
            'supplier_id.required' => 'Select Manufacturer Name',
            'delivery_terms.required' => 'Delivery terms should not be blank.'
        ];
        foreach(Input::get('shipping.shipping') as $key => $val)
        {
            $messages['shipping.shipping.'.$key.'.qty.required'] = 'Select Qty';
            $messages['shipping.shipping.'.$key.'.model_no.required'] = 'Select Model No';
        }
        return $messages;
    }
}
