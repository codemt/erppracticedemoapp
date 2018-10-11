<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class generatePoRequest extends FormRequest
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
        // dd($data);
        switch ($this->method()) {

            case 'POST':
            {
                $rules = [
                    'delivery_terms' => 'required',
                ]; 
                return $rules;
            }
            case 'PATCH':
            {

                $rules = [
                    'delivery_terms' => 'required',
                ];          
                return $rules;
            }
        }
    }

    public function messages(){
        $messages = [
            'delivery_terms.required' => 'Delivery terms should not be blank.',
        ];
        return $messages;
    }
}
