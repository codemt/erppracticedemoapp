<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\State;
use App\Events\StateEvent;
use Event,Excel;
use App\Http\Requests\StateRequest; 

class StateController extends Controller
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
            $where_str .= " and (country_id = '101')";
            $columns = array('created_at','id','title');

            $user = State::select($columns)
                ->whereRaw($where_str, $where_params);  
                
            $user_count = State::select('id','updated_at','title')
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
        return view('admin/state/index');
       // return view('admin.users.index');
    }
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.state.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StateRequest $request)
    {
        $add_state = $request->all();
        // dd($add_state);
        Event::fire(new StateEvent($add_state));

        if($request->save_button=='save_new')
        {
        return back()->with('message', 'Record Added Successfully.')
        ->with('message_type', 'success');
        }
        return redirect()->route('state.index')->with('message', 'Record Added Successfully.')
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
        $state = State::find($id); 
        return view('admin.state.edit', compact('state'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StateRequest $request, $id)
    {
         $addstate = $request->all();
        // dd($request->all());

        Event::fire(new StateEvent($addstate));
        if($request->save_button=='save_new')
        {
            return back()->with('message', 'Record Updated Successfully.')
            ->with('message_type', 'success');
        }
        return redirect()->route('state.index')->with('message', 'Record Updated Successfully.')
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

        $state_delete = State::whereIn('id', $id)->delete();

        return response()->json(array('success' => true), 200);
    }
    public function export(){
        $state_data = State::select('title')->where('country_id','101')->get()->toArray();
        $state_csv_name = 'state_report_'.date('Y_m_d_H_i_s');
        

        Excel::create($state_csv_name, function($excel) use($state_data){
            $excel->sheet('State Report', function($sheet) use($state_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('State Values');
                });
                $sheet->row(1,['Id','State']);
                $sheet->loadView('admin.csv.state')->with('state_data',$state_data);
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
