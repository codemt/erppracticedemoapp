<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\State; 
use App\Models\City; 
use App\Events\CityEvent;
use App\Http\Requests\CityRequest; 
use Event,Excel;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
     {
        if ($request->ajax()) {
            $where_str = "1 = ?";
            $where_params = array(1);

            if (!empty($request->input('sSearch'))) {
                $search = addslashes($request->input('sSearch'));
                $where_str .= " and ( cities.title like \"%{$search}%\""
                    . " or states.title like \"%{$search}%\""
                    . ")";
            }
            $columns = array('cities.updated_at as updated_at','cities.id as id' ,'cities.title as title','states.title as state_name');

            $user = City::select($columns)
                ->leftjoin('states','states.id','=','cities.state_id')
                ->where('states.country_id','=','101')
                ->whereRaw($where_str, $where_params);  
                
            $user_count = City::select('cities.id as id' ,'cities.updated_at as updated_at','cities.title as title','states.title as state_name')
                ->leftjoin('states','states.id','=','cities.state_id')
                ->where('states.country_id','=','101')
                ->whereRaw($where_str, $where_params)
                ->count();

            if ($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != '') {
                $user = $user->take($request->input('iDisplayLength'))
                    ->skip($request->input('iDisplayStart'));
            }
            if ($request->input('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->input('iSortingCols'); $i++) {
                    $column = $columns[$request->input('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $user = $user->orderBy($column, $request->input('sSortDir_' . $i));
                }
            }
            $user = $user->get();

            $response['iTotalDisplayRecords'] = $user_count;
            $response['iTotalRecords'] = $user_count;
            $response['sEcho'] = intval($request->input('sEcho'));
            $response['aaData'] = $user->toArray();

            return $response;
        }
        return view('admin/city/index');
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $state = State::orderBy('title','asc')->where('country_id','101')->pluck('title','id')->toArray(); 
                
        return view('admin/city/create',compact('state')); 
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request)
    {

        //dd($request->all());
        $request_add_city = $request->all();
        // dd($request_add_city);
        Event::fire(new CityEvent($request_add_city)); 

        if($request->save_button=='save_new')
        {
        return back()->with('message', 'Record Added Successfully.')
        ->with('message_type', 'success');
        }
        return redirect()->route('cities.index')->with('message', 'Record Added Successfully.')
        ->with('message_type', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cities =  City::where('id',$id)->get()->toArray(); 
        $cities = $cities[0]; 
        $sid = $cities['state_id']; 
        $state = State::orderBy('title','asc')->where('country_id','101')->pluck('title','id')->toArray(); 

        $exist_data = City::find($id);  
        return view('admin.city.edit', compact('exist_data','state')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CityRequest $request,$id)
    {
        $request_add_city = $request->all(); 
         Event::fire(new CityEvent($request_add_city)); 
        if($request->save_button=='save_new')
        {
            return back()->with('message', 'Record Updated Successfully.')
            ->with('message_type', 'success');
        }
        return redirect()->route('cities.index')->with('message', 'Record Updated Successfully.')
        ->with('message_type', 'success');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->get('id');

        if (!is_array($id)) {
            $id = array($id);
        }

        $city_delete = City::whereIn('id', $id)->delete();

        return response()->json(array('success' => true), 200);
        
    }
    public function export(){
        $city_data = City::select('cities.id as id' ,'cities.title as title','states.title as state_name')
                        ->leftjoin('states','states.id','=','cities.state_id')
                        ->where('states.country_id','=','101')
                        ->get()
                        ->toArray();
        
        $city_csv_name = 'city_report_'.date('Y_m_d_H_i_s');
        

        Excel::create($city_csv_name, function($excel) use($city_data){
            $excel->sheet('Product Report', function($sheet) use($city_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('Customer Values');
                });
                $sheet->row(1,['Id','City Name','State Name']);
                $sheet->loadView('admin.csv.cities')->with('city_data',$city_data);
                $sheet->cell('A1:F1', function($cell) {
                // Set font
                    $cell->setFont(array(
                        'bold' => true
                    ));
                });
                
            });

        })->export('csv');
    }
}
