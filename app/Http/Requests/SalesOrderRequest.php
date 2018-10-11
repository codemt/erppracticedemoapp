<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SalesOrderRequest extends FormRequest
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
        $data = $request->all();
        // print_r($data);
        // exit();
        switch ($this->method()) {
        case 'POST':
            {
                $rules = [
                    // 'po_no'             =>   'required',
                    // // 'po_no'             =>   'required',
                    // 'order_date'        =>   'required',
                    // 'customer_id'        =>   'required',
                    // 'billing_title'   =>   'required',
                    // 'customer_contact_name'      =>   'required',
                    // 'customer_contact_email'     =>   'required|email',
                    // 'customer_contact_no'        =>   'required|min:0|max:10|regex:'.config('regex.product.contact_no'),
                    // 'contact_name'      =>   'required',
                    // 'contact_email'     =>   'required|email',
                    // 'contact_no'        =>   'required|min:0|max:10|regex:'.config('regex.product.contact_no'),
                    // 'company_id'        =>   'required',
                    // 'payment_terms'     =>   'required',
                    // 'delivery'          =>    'required',
                    // // 'advanced_received' =>    'required|regex:'.config('regex.product.price'),
                    // 'part_shipment'     =>    'required',
                    // 'trasport'     =>    'required',
                    // // 'pkg_fwd'     =>    'required|regex:'.config('regex.product.price'),
                    // 'reason_for_other_expense'     =>    'required_with:other_expense',
                    // 'fright'     =>    'required|regex:'.config('regex.product.price'),
                    // 'tax_subtotal'     =>    'required|regex:'.config('regex.product.price'),
                ];
                if(isset($data['check_billing'])){
                    if($data['check_billing'] == false){
                        // $rules['shipping_address'] =   'required';
                        // $rules['countryid']        =   'required';
                        // $rules['pin_code']          =   'required';
                        // $rules['stateid']          =   'required';
                        // $rules['cityid']           =   'required';
                    }
                }else{
                    // $rules['shipping_address'] =   'required';
                    // $rules['countryid']        =   'required';
                    // $rules['pin_code']          =   'required';
                    // $rules['stateid']          =   'required';
                    // $rules['cityid']           =   'required';
                }
                $arr  = ['image/png','image/jpeg','application/pdf','application/docx'];
                if(isset($data['product_image']) == false){
                    $rules['product_image'] =   'required';

                }else if(!in_array($data['product_image']['type'],$arr)){
                    $rules['product_image'] =   'mimes:jpeg,image/png,pdf,docx';
                }
                return $rules;
            }
        case 'PATCH':
            {
                $rules = [
                    'po_no'             =>   'required',
                    'order_date'        =>   'required',
                    'customer_id'        =>   'required',
                    'billing_title'   =>   'required',
                    'customer_contact_name'      =>   'required',
                    'customer_contact_email'     =>   'required|email',
                    'customer_contact_no'        =>   'required|min:0|max:10|regex:'.config('regex.product.contact_no'),
                    'contact_name'      =>   'required',
                    'contact_email'     =>   'required|email',
                    'contact_no'        =>   'required',
                    'company_id'        =>   'required',
                    'payment_terms'     =>   'required',
                    'delivery'          =>    'required',
                    'advanced_received' =>    'required|regex:'.config('regex.product.price'),
                    'part_shipment'     =>    'required',
                    'trasport'     =>    'required',
                    'pkg_fwd'     =>    'required|regex:'.config('regex.product.price'),
                    'reason_for_other_expense'     =>    'required_with:other_expense',
                    'fright'     =>    'required|regex:'.config('regex.product.price'),
                    'tax_subtotal'     =>    'required|regex:'.config('regex.product.price'),
                ];
                if(isset($data['check_billing'])){
                    if($data['check_billing'] == false){
                        $rules['shipping_address'] =   'required';
                        $rules['countryid']        =   'required';
                        $rules['pin_code']          =   'required';
                        $rules['stateid']          =   'required';
                        $rules['cityid']           =   'required';
                    }
                }else{
                    $rules['shipping_address'] =   'required';
                    $rules['countryid']        =   'required';
                    $rules['pin_code']          =   'required';
                    $rules['stateid']          =   'required';
                    $rules['cityid']           =   'required';
                } 
                $arr  = ['image/png','image/jpeg','application/pdf','application/docx'];
                
                if(isset($data['product_image']) and !in_array($data['product_image']['type'],$arr)){
                    $rules['product_image'] =   'mimes:jpeg,image/png,pdf,docx';
                }
                return $rules;
            }
        default:break;
        }
    }
    public function messages() {
        $messages = [
            'po_no.unique' => "Purchase Order Number must be unique",
            'po_no.required' => "Purchase Order Number should not be blank",
            'order_date.required' => "Purchase Order Date should not be blank",
            'customer_id.required' => "Customer should not be blank",
            'billing_title.required' => "Billing Address should not be blank",
            'shipping_address.required' => "Shipping Address should not be blank",
            'areaname.required' => "Area should not be blank",
            'pin_code.required' => "Pincode should not be blank",
            'customer_contact_name.required' => "Customer Contact Name should not be blank",
            'customer_contact_email.required' => "Customer Contact Email should not be blank",
            'customer_contact_email.email' => "Customer Contact Email format is invalid",
            'customer_contact_no.required' => "Customer Contact Number should not be blank",
            'customer_contact_no.regex' => "Customer Contact Number format is invalid",

            'contact_name.required' => "Contact Name should not be blank",
            'contact_email.required' => "Contact Email should not be blank",
            'contact_email.email' => "Contact Email format is invalid",
            'contact_no.required' => "Contact Number should not be blank",
            'contact_no.regex' => "Contact Number format is invalid",
            'sales_person_id.required' => "Sales Person should not be blank",
            'company_id.required' => "Company should not be blank",
            'countryid.required' => "Country should not be blank",
            'stateid.required' => "State should not be blank",
            'cityid.required' => "City should not be blank",
            'payment_terms.required' => "Payment Terms should not be blank",
            'delivery.required' => "Delivery should not be blank",
            'advanced_received.required' => "Advanced Received should not be blank",
            'advanced_received.regex' => "Advanced Received format is invalid",
            'part_shipment.required' => "Part Shipment should not be blank",
            'pkg_fwd.required' => "PKG and FWD charge should not be blank",
            'pkg_fwd.regex' => "PKG and FWD charge format is invalid",
            'reason_for_other_expense.required' => "Reason For other expense should not be blank",
            'tax_subtotal.required' => "Tax On Subtotal should not be blank",
            'product_image.required' => "File should not be blank",
            'product_image.mimes' => "File must be a file of type: jpeg, png, pdf, docx",
            'fright.required' => "Freight Charge should not be blank",
            'fright.regex' => "Freight Charge format is invalid",
            'tax_subtotal.required' => "Tax On Subtotal should not be blank",
            'tax_subtotal.regex' => "Tax On Subtotal format is invalid",  
        ];
        return $messages;
    }
}
