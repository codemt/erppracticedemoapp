<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class PORequest extends FormRequest
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
    public function rules(Request $request){


        $data = $request->all();
        //print_r($data);
        // exit();     

        switch ($this->method()) {
            case 'POST':
             {
                 $rules = [
                     'purchase_requisition_data.company_id' => 'required',
                     'purchase_requisition_data.supplier_id' => 'required',
                     'purchase_requisition_data.delivery_terms' => 'required'
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
                     'purchase_requisition_data.delivery_terms' => 'required'
                 ];
 
                 foreach(Input::get('purchase_requisition_details') as $key => $val)
                 {
                     $rules['purchase_requisition_details.'.$key.'.qty'] = 'required';
                     $rules['purchase_requisition_details.'.$key.'.model_no'] = 'required';
                 }
                 return $rules;
             }
 
        }
    }
}
