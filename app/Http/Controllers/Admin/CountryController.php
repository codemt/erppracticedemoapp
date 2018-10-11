<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Events\CountryEvent;
use Event;
use App\Http\Requests\CountryRequest;

class CountryController extends Controller
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
                $where_str .= " and ( title like \"%{$search}%\""
               
                    . ")";
            }
            $columns = array('id','updated_at','title');


            $user = Country::select($columns)
                ->whereRaw($where_str, $where_params);  
                
            $user_count = Country::select('id','updated_at','title')
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
        return view('admin.Country.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.Country.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CountryRequest $request)
    {
        $add_country = $request->all();
        // dd($add_country);
        Event::fire(new CountryEvent($add_country));

        if ($request->save_button == 'save_new') {
            return back()->with('message', 'Country added successfully')
                ->with('message_type', 'success');
        }
        return redirect()->route('country.index')->with('message', 'Country added successfully')
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
        $country = Country::find($id); 
        return view('admin.Country.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CountryRequest $request, $id)
    {
        $addcountry = $request->all();
        // dd($request->all());

        Event::fire(new CountryEvent($addcountry));
        
        if ($request->save_button == 'save_new') {
            return back()->with('message', 'Country updated successfully')
                ->with('message_type', 'success');
        }
        return redirect()->route('country.index')->with('message', 'Country updated successfully')
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

        $country_delete = Country::whereIn('id', $id)->delete();

        return response()->json(array('success' => true), 200);
    }
}
