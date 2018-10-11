<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Designation;
use App\Models\DesignationPermission;
use App\Models\AclPermission;
use App\Models\UserPermission;
use App\Models\Team;
use Event,Excel;
use Form;
use App\Events\SystemUser\InsertSystemUserEvent;
use App\Events\SystemUser\UpdateSystemUserEvent;
use App\Http\Requests\SystemUserRequest;

class SystemUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $where_str    = "1 = ?";
            $where_params = array(1); 

            if (!empty($request->input('sSearch')))
            {
                $search     = addslashes($request->input('sSearch'));
                $where_str .= " and (admins.name like \"%{$search}%\""
                . " or admins.email like \"%{$search}%\""
                . " or user_team.name like \"%{$search}%\""
                . " or designations.name like \"%{$search}%\""
                . " or admins.region like \"%{$search}%\""
                . " or admins.status like \"%{$search}%\""
                
                . ")";
            }                                            

            $columns = ['admins.id','admins.name','admins.email','user_team.name as team','designations.name as designation','admins.alternate_no','admins.region','admins.status'];

            $systemuser_columns_count = Admin::select($columns)
            ->leftjoin('designations','designations.id','=','admins.designation_id')
            ->leftjoin('user_team','user_team.id','=','admins.team_id')
            ->whereRaw($where_str, $where_params)
            ->count();

            $systemuser_list = Admin::select($columns)
            ->leftjoin('designations','designations.id','=','admins.designation_id')
            ->leftjoin('user_team','user_team.id','=','admins.team_id')
            ->whereRaw($where_str, $where_params);

            if($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != ''){
                $systemuser_list = $systemuser_list->take($request->input('iDisplayLength'))
                ->skip($request->input('iDisplayStart'));
            }          

            if($request->input('iSortCol_0')){
                $sql_order='';
                for ( $i = 0; $i < $request->input('iSortingCols'); $i++ )
                {
                    $column = $columns[$request->input('iSortCol_' . $i)];
                    if(false !== ($index = strpos($column, ' as '))){
                        $column = substr($column, 0, $index);
                    }
                    $systemuser_list = $systemuser_list->orderBy($column,$request->input('sSortDir_'.$i));   
                }
            } 


            $systemuser_list = $systemuser_list->get();

            $response['iTotalDisplayRecords'] = $systemuser_columns_count;
            $response['iTotalRecords'] = $systemuser_columns_count;
            $response['sEcho'] = intval($request->input('sEcho'));
            $response['aaData'] = $systemuser_list->toArray();

            return $response;
        }
        return view('admin.SystemUser.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designation_list = Designation::orderBy('name','asc')->pluck('name','id')->toArray();

        $team_list = Team::orderBy('name','asc')->pluck('name','id')->toArray();
        // dd($team_list);
        return view('admin.SystemUser.create',compact('designation_list','team_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SystemUserRequest $request)
    {
        $systemUser_data = $request->all();
        
        Event::fire(new InsertSystemUserEvent($systemUser_data));

        return response()->json(array('success' => true,'action'=>'added'),200);

        // if($request->save_button == 'save_new') 
        // {
        //     return back()->with('message','Record Added  Successfully')
        //     ->with('message_type','success');
        // }
        // return redirect()->route('systemuser.index')->with('message','Record Added Successfully')->with('message_type','success');
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
        $userupdate_data = Admin::find($id);
        $id = $userupdate_data['id'];

        $designation_list = (old('designation_id')) ? [""=>"Select Designatio"] + Designation::where("team_id",old('team_id'))->pluck("name","id")->toArray() :["" =>"Select Designation"] + Designation::where('team_id',$userupdate_data['team_id'])->pluck('name', 'id')->toArray();

        $team_list = Team::orderBy('name','asc')->pluck('name','id')->toArray();

        $user_current_permissions = UserPermission::where('user_id',$id)->pluck('permission_id')->toArray();
        
        $user_permission = AclPermission::getPermission();
         //dd($user_permission);
        return view('admin.SystemUser.edit',['userupdate_data'=>$userupdate_data,'id'=>$id,'designation_list'=>$designation_list,'team_list'=>$team_list,'user_current_permissions'=>$user_current_permissions,'user_permission'=>$user_permission]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SystemUserRequest $request, $id)
    {
        $userupdate_data = $request->all();
        
        $userupdate_data['id'] = $id;
        
        Event::fire(new UpdateSystemUserEvent($userupdate_data));

        return response()->json(array('success' => true,'action'=>'updated'),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getdesignation(Request $request)
    {
        $team_id = $request->get('team');

        $get_designationIn = Designation::where('team_id', $team_id)->pluck('name', 'id')->toArray();

        return Form::select('designation_id',[''=>'Select Designation'] + $get_designationIn, old('designation_id'), ['class' => 'form-control','id' => 'designation']);
    }

    public function getPermissionList(Request $request){
        $id = $request->designation_id;
   
        $designation_current_permissions = DesignationPermission::where('designation_id',$id)->pluck('permission_id')->toArray();

        $user_permission = AclPermission::getPermission();
        
        $get_html = view('admin.SystemUser.designation', compact('designation_current_permissions','user_permission'))->render();

        return response()->json(array('html'=>$get_html,'success' => true), 200);
    }
    public function export(){
        $user_data = Admin::select('admins.name','admins.email','user_team.name as team_name','designations.name as designation_name','admins.region','admins.status')
                                    ->leftjoin('user_team','user_team.id','=','admins.team_id')
                                    ->leftjoin('designations','designations.id','=','admins.designation_id')
                                    ->get()
                                    ->toArray();
        $user_csv_name = 'user_report_'.date('Y_m_d_H_i_s');
        

        Excel::create($user_csv_name, function($excel) use($user_data){
            $excel->sheet('Users Report', function($sheet) use($user_data){
                $sheet->cell('A1', function($cell){
                    $cell->setValue('User Values');
                });
                $sheet->row(1,['Id','Name','Username','Team','Designation','Region','Status']);
                $sheet->loadView('admin.csv.users')->with('user_data',$user_data);
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
