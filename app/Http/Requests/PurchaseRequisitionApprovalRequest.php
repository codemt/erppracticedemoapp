<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class PurchaseRequisitionApprovalRequest extends FormRequest
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
            case 'PATCH':
            {
                if($data['approve'] != 'cancel' && $data['approve'] != 'onhold'){
                    $rules = [
                        'project_name' => 'required',
                        'payment_terms' => 'required',
                        'company_shipping_add' => 'required',
                        'supplier_billing_add' => 'required',
                        'dispatch_through' => 'required',
                        'spoc_name' => 'required',
                        'spoc_email' => 'required',
                        'spoc_phone' => 'required'
                    ];
                    foreach(Input::get('shipping.shipping') as $key => $val)
                    {
                        $rules['shipping.shipping.'.$key.'.unit_price'] = 'required|gt:0|regex:'.config('regex.product.price');
                        $rules['shipping.shipping.'.$key.'.qty'] = 'required|gt:0';
                    }
                }
                else{
                    $rules = [
                        'project_name' => 'nullable',
                        'payment_terms' => 'nullable',
                        'company_shipping_add' => 'nullable',
                        'supplier_billing_add' => 'nullable',
                        'dispatch_through' => 'nullable',
                        'spoc_name' => 'required',
                        'spoc_email' => 'required',
                        'spoc_phone' => 'required'
                    ];
                    foreach(Input::get('shipping.shipping') as $key => $val)
                    {
                        $rules['shipping.shipping.'.$key.'.unit_price'] = 'nullable';
                        $rules['shipping.shipping.'.$key.'.qty'] = 'nullable';
                    }
                }
                return $rules;
            }

       }
    }
    public function messages(){
        $messages = [
            'project_name.required' => 'Project Name should not be blank.',
            'payment_terms.required' => 'Payment Terms should not be blank.',
            'company_shipping_add.required' => 'Company Shipping Address should not be blank.',
            'supplier_billing_add.required' => 'Supplier Billing Address should not be blank.',
            'dispatch_through.required' => 'Dispatch Through should not be blank.',
            'spoc_name.required' => 'SPOC name should not be blank.',
            'spoc_email.required' => 'SPOC email should not be blank.',
            'spoc_phone.required' => 'SPOC phone should not be blank.'
        ];
        foreach(Input::get('shipping.shipping') as $key => $val)
        {
            $messages['shipping.shipping.'.$key.'.unit_price.required'] = 'Price should not be blank.';
            $messages['shipping.shipping.'.$key.'.unit_price.regex'] = 'Price format is invalid.';
            $messages['shipping.shipping.'.$key.'.unit_price.gt'] = 'Price shold greater than 0.';
            $messages['shipping.shipping.'.$key.'.qty.required'] = 'Qty should not be blank.';
            $messages['shipping.shipping.'.$key.'.qty.gt'] = 'Qty shold greater than 0.';
        }
        return $messages;
    }
}
