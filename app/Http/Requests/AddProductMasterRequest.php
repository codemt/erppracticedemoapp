<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class AddProductMasterRequest extends FormRequest
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
                        'product_type' => 'required',
                        'model_no' => 'required|unique:product_master,model_no',
                        'name_description' => 'required',
                        'price' => 'required|regex:'.config('regex.product.price'),
                        'unit' => 'nullable|regex:'.config('regex.product.price'),
                        'max_discount' => 'required|regex:'.config('regex.product.price'),
                        'tax' => 'required',
                        'qty' => 'required|regex:'.config('regex.product.price'),
                        'min_qty' => 'required|regex:'.config('regex.product.price'),
                        'image' => 'nullable|dimensions:width=200,height=200',
                        'hsn_code' => 'required',
                        'weight' => 'nullable|regex:'.config('regex.product.weight')
                    ]; 
                    if($data['product_type'] == 'bundle'){
                        $rules['combo_product'] = 'required';
                    } 
                return $rules;
                }
                case 'PATCH' :
                 {
                    $rules = [
                        'company_id' => 'nullable',
                        'supplier_id' => 'required',
                        'product_type' => 'required',
                        'model_no' => 'required|unique:product_master,model_no,'.$this->segment(3),
                        'name_description' => 'required',
                        'price' => 'required|regex:'.config('regex.product.price'),
                        'unit' => 'nullable|regex:'.config('regex.product.price'),
                        'max_discount' => 'required|regex:'.config('regex.product.price'),
                        'tax' => 'required',
                        'qty' => 'required|regex:'.config('regex.product.price'),
                        'min_qty' => 'required|regex:'.config('regex.product.price'),
                        'image' => 'nullable|dimensions:width=200,height=200',
                        'hsn_code' => 'required',
                        'weight' => 'nullable|regex:'.config('regex.product.weight')
                    ]; 
                    if($data['product_type'] == 'bundle'){
                        $rules['combo_product'] = 'required';
                        
                    } 
                return $rules;
                }
        }
    }
    public function messages(){
        return [
            'company_id.required' => 'Company should not be blank.',
            'supplier_id.required' => 'Manufacturer should not be blank.',
            'product_type.required' => 'Product type should not be blank.',
            'model_no.required' => 'Model no should not be blank.',
            'name_description.required' => 'Name & Description should not be blank.',
            'price.required' => 'Price should not be blank.',
            'max_discount.required' => 'Maximum discount should not be blank.',
            'max_discount.regex' => 'Maximum discount format is invalid.',
            'tax.required' => 'Tax should not be blank.',
            'qty.required' => 'Qty should not be blank.',
            'min_qty.required' => 'Minimum Qty should not be blank.',
            'image.required' => 'Image should not be blank.',
            'hsn_code.required' => 'HSN Code should not be blank.',
            'price.regex' => 'Price format is invalid.',
            'unit.regex' => 'Unit format is invalid.',
            'qty.regex' => 'Qty format is invalid.',
            'min_qty.regex' => 'Minimum Qty format is invalid.',
            'image.dimensions' => 'Image has invalid dimensions.',
            'weight.regex' => 'Weight format is invalid.',
            'combo_product.required' => 'Products should not be blank.'
        ]; 
        
    }
}
