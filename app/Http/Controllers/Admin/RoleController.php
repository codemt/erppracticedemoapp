<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Events\RoleEvent;
use Event;
use App\Http\Requests\RoleRequest; 


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
     {
        // $type = $request->get('type');
        

        // if($type == 'in-active')
        // {
        //     $active_val = '0';
        // }
        // else
        // {
        //     $active_val = '1';
        // }
        if ($request->ajax()) {
            $where_str = "1 = ?";
            $where_params = array(1);

            if (!empty($request->input('sSearch'))) {
                $search = addslashes($request->input('sSearch'));
                $where_str .= " and ( name like \"%{$search}%\""
               
                    . ")";
            }
            $columns = array('id','name','status');


            $user = Role::select($columns)
                ->whereRaw($where_str, $where_params);  
                
            $user_count = Role::select('id','name','status')
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
        return view('admin.role.index');
       // return view('admin.users.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $add_role = $request->all();
        // dd($request_add_city);
        Event::fire(new RoleEvent($add_role));

        if ($request->save_button == 'save_new') {
            return back()->with('message', 'Role added successfully')
                ->with('message_type', 'success');
        }
        return redirect()->route('role.index')->with('message', 'Role added successfully')
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

        $role = Role::find($id); 

        return view('admin.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
         $addrole = $request->all();
        // dd($request->all());

        Event::fire(new RoleEvent($addrole));
        if ($request->save_button == 'save_new') {
             return redirect()->route('role.index')->with('message', 'Role Updated successfully')
            ->with('message_type', 'success');
        }
       
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

        $role_delete = Role::whereIn('id', $id)->delete();

        return response()->json(array('success' => true), 200);
    }
}
