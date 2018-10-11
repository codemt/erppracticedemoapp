<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\Team;
use App\Models\AclPermission;
use App\Models\DesignationPermission;
use App\Events\SystemUser\DesignationEvent;
use Event,Excel;
use App\Http\Requests\DesignationRequest; 


class DesignationController extends Controller
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
                $where_str .= " and ( designations.name like \"%{$search}%\""
                    . " or user_team.name like \"%{$search}%\""
                    . ")";
            }
            $columns = array('designations.id','designations.updated_at','designations.name','user_team.name as team','designations.status');


            $designation = Designation::select($columns)
                ->leftjoin('user_team','user_team.id','=','designations.team_id')
                ->whereRaw($where_str, $where_params);  
                
            $designation_count = Designation::select('id','updated_at','name','status')
                ->leftjoin('user_team','user_team.id','=','designations.team_id')
                ->whereRaw($where_str, $where_params)
                ->count();

            if ($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != '') {
                $designation = $designation->take($request->input('iDisplayLength'))
                    ->skip($request->input('iDisplayStart'));
            }
            if ($request->input('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->input('iSortingCols'); $i++) {
                    $column = $columns[$request->input('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $designation = $designation->orderBy($column, $request->input('sSortDir_' . $i));
                }
            }
            $designation = $designation->get();

            $response['iTotalDisplayRecords'] = $designation_count;
            $response['iTotalRecords'] = $designation_count;
            $response['sEcho'] = intval($request->input('sEcho'));
            $response['aaData'] = $designation->toArray();

            return $response;
        }
        return view('admin.SystemUser.designationindex');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_permission = AclPermission::getPermission();

        $team = Team::orderBy('name','asc')->pluck('name','id')->toArray();
        return view('admin.SystemUser.designationcreate',compact('team','user_permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DesignationRequest $request)
    {
        $add_designation = $request->all();
        // dd($add_designation);
        Event::fire(new DesignationEvent($add_designation));

        if ($request->save_button == 'save_new') {
            return back()->with('message', 'Designation added successfully')
                ->with('message_type', 'success');
        }
        return redirect()->route('designation.index')->with('message', 'Designation added successfully')
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
        $user_permission = AclPermission::getPermission();

        $designation = Designation::find($id); 
        
        $team = Team::orderBy('name','asc')->pluck('name','id')->toArray();

        $designation_current_permissions = DesignationPermission::where('designation_id',$id)->pluck('permission_id')->toArray();

        return view('admin.SystemUser.designationedit', compact('designation','team','designation_current_permissions','user_permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DesignationRequest $request, $id)
    {
         $adddesignation = $request->all();
        //dd($adddesignation);

        Event::fire(new DesignationEvent($adddesignation));
        
        if ($request->save_button == 'save_new') {
            return back()->with('message', 'Designation updated successfully')
                ->with('message_type', 'success');
        }
        return redirect()->route('designation.index')->with('message', 'Designation updated successfully')
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

        $designation_delete = Designation::whereIn('id', $id)->delete();

        return response()->json(array('success' => true), 200);
    }
    public function export(){

        $designation_data = Designation::select('designations.name as designation_name','user_team.name','designations.status')
                                    ->leftjoin('user_team','user_team.id','=','designations.team_id')
                                    ->get()
                                    ->toArray();
        $designation_csv_name = 'designation_report_'.date('Y_m_d_H_i_s');
        

        Excel::create($designation_csv_name, function($excel) use($designation_data){
            $excel->sheet('Designation Report', function($sheet) use($designation_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('Designtion Values');
                });
                $sheet->row(1,['Id','Designation Name','Team','Action']);
                $sheet->loadView('admin.csv.designation')->with('designation_data',$designation_data);
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