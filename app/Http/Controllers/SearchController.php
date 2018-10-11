<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\State;
use App\Models\BillingAddress;
use App\Models\AddressMaster;
use Form;

class SearchController extends Controller
{
	public function getstate(Request $request) {
		$country_id = $request->get('country_id');
		$state_data = State::where('country_id', $country_id)->pluck('title', 'id')->toArray();
		$states = ['' => 'Select State'] + $state_data;
		
		return Form::select('state_id', $states, old('state_id'), array('class' => 'form-control select2', 'id' => 'state_id'));
	}
    public function getcity(Request $request) {
		$state_id = $request->get('state_id');
		// dd($state_id);
		$city_data = City::where('state_id', $state_id)->pluck('title', 'id')->toArray();
		$cities = ['' => 'Select City'] + $city_data;

		return Form::select('city_id', $cities, old('city_id'), array('class' => 'form-control select2', 'id' => 'city_id'));
	}
	public function getbillingaddress(Request $request) {
		$customer_id = $request->get('customer_id');
		
		$address_data = AddressMaster::select('address_masters.*','countries.title as country_name','states.title as state_name','cities.title as city_name')->where('address_masters.customer_id',$customer_id)->leftjoin('countries','countries.id','=','address_masters.country_id')->leftjoin('states','states.id','=','address_masters.state_id')->leftjoin('cities','cities.id','=','address_masters.city_id')->get()->toArray();
		$arr=[];
		foreach ($address_data as $key => $value) {
			$arr[$value['id']] = $value['address'].','.$value['area'].','.$value['city_name'].','.$value['state_name'].','.$value['country_name'].','.$value['pincode'];
		}
		$address = ['' => 'Select Billing Address'] + $arr;
		return Form::select('billing_title', $address, old('billing_title'), array('class' => 'form-control select2', 'id' => 'billing_title'));
	}
	
}
