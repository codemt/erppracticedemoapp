<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Events\ErpCityEvent;
use Event;

class ErpCityController extends Controller
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
                $where_str .= " and ( city like \"%{$search}%\""
               
                    . ")";
            }
            $columns = array('id', 'city');


            $user = City::select($columns)
                ->whereRaw($where_str, $where_params);  
                
            $user_count = City::select('id', 'city')
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
        return view('admin/city/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request_add_city = $request->all();
        // dd($request_add_city);
        Event::fire(new ErpCityEvent($request_add_city));

        if ($request->save_button == 'save_new') {
            return back()->with('message', 'city added successfully')
                ->with('message_type', 'success');
        }
        return redirect()->route('cities.index')->with('message', 'city added successfully')
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

        $city = City::find($id); 
        return view('admin.city.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {   
        $request_add_city = $request->all();
        // dd($request->all());

        Event::fire(new ErpCityEvent($request_add_city));
        return redirect()->route('cities.index');

        
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
}
