<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SystemUserRequest extends FormRequest
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
        if(!isset($data['id'])){
            return [
                'name' => 'required',
                'email' => 'required|email|unique:admins',
                'team_id' => 'required',
                'region' => 'required',
                'status' => 'required',
                'address' => 'required',
                'bloodgroup' => 'required',
                'alternate_no' => 'required|min:10|max:10',
                'pan_no' => 'required',
                'aadhar_no' => 'required',
                'image' => 'required|dimensions:width=200,height=200',
                'company_contact_no' => 'required|min:10|max:10',
                'company_property' => 'required',
                'date_of_joining' => 'required',
                'date_of_birth' => 'required',
            ]; 
        }else{
            
            $id = \Request::segment(3);

            return [
                'name' => 'required',
                'email' => 'required|email|unique:admins,email,'.$id,
                'team_id' => 'required',
                'region' => 'required',
                'status' => 'required',
                'address' => 'required',
                'bloodgroup' => 'required',
                'alternate_no' => 'required|min:10|max:10',
                'pan_no' => 'required',
                'aadhar_no' => 'required',
                'image' => 'nullable|dimensions:width=200,height=200',
                'company_contact_no' => 'required|min:10|max:10',
                'company_property' => 'required',
                'date_of_joining' => 'required',
                'date_of_birth' => 'required',
            ];  
        }
    }

    public function messages()
    {
        return[

            'name.required' => 'Name should not be blank.',
            'email.required' => 'Username should not be blank.',
            'email.email' => 'Username must be a valid username.',
            'designation_id.required' => 'Designation name should not be blank.',
            'team.required' => 'Team should not be blank.',
            'region.required' => 'Region should not be blank.',
            'status.required' => 'Status should not be blank.',
            'address.required' => 'Address should not be blank.',
            'bloodgroup.required' => 'Blood group should not be blank.',
            'alternate_no.required' => 'Alternate Contact no should not be blank.',
            'alternate_no.min' => 'Alternate Contact no must not be atleast 10 characters.',
            'alternate_no.max' => 'Alternate Contact no must not be atleast 10 characters.',
            'pan_no.required' => 'Pan no should not be blank.',
            'aadhar_no.required' => 'Aadhar card no should not be blank.',
            'image.required' => 'Image should not be blank.',
            'company_contact_no.required' => 'Company Contact Number should not be blank.',
            'company_property.required' => 'Company Property should not be blank.',
            'date_of_joining.required' => 'Date of joining should not be blank.',
            'date_of_birth.required' => 'Date of birth should not be blank.'
        ];
    }
}
