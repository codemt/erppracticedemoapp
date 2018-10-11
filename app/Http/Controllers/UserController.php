<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
     {
        $type = $request->get('type');
        

        if($type == 'in-active')
        {
            $active_val = '0';
        }
        else
        {
            $active_val = '1';
        }
        if ($request->ajax()) {
            $where_str = "1 = ?";
            $where_params = array(1);

            if (!empty($request->input('sSearch'))) {
                $search = $request->input('sSearch');
                $where_str .= " and ( firstname like \"%{$search}%\""
                ."or lastname like \"%{$search}%\""
                ."or email like \"%{$search}%\""
                ."or department like \"%{$search}%\""
                ."or salary like \"%{$search}%\""
                    . ")";
            }
            $columns = array('id', 'firstname','lastname','email','phone','salary','status');


            $user = User::select($columns)
                ->whereRaw($where_str, $where_params)
                ->where('status',$active_val);

            $user_count = User::select('id', 'firstname','lastname','email','phone','salary','status')
                ->whereRaw($where_str, $where_params)
                ->where('status',$active_val)
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
        return view('admin.users.index');
    }
 public function data(Request $request)
 {
    if ($request->ajax()) {

            $where_str = "1 = ?";
            $where_params = array(1);

            if (!empty($request->input('sSearch'))) {
                $search = $request->input('sSearch');
                $where_str .= " and ( firstname like \"%{$search}%\""
                    . ")";
            }
            // if ($request->get('yoy')[0] != "") {
            //     $date_range_str = $request->get('yoy');
            //     $date_range_arr = explode(" - ", $date_range_str[0]);
            //     $range_from = date('Y-m-d', strtotime($date_range_arr[0]));
            //     $range_from = str_replace('/', '-', $range_from);
            //     $range_to = date('Y-m-d', strtotime($date_range_arr[1]));
            //     $range_to = str_replace('/', '-', $range_to);

            //     $range_condition = " AND (DATE_FORMAT(lifafa.created_at,'%Y-%m-%d') BETWEEN '$range_from' AND '$range_to') ";
            //     $where_str .= $range_condition;
            // }

            $columns = array('id', 'firstname');

            // $now = new \Carbon();

            // $date = $now->toDateString();

            $lifafa_count = User::select('id')
                ->whereRaw($where_str, $where_params)
                // ->where('is_publish', 1)
                // ->where('event_end_date', '>=', $date)
                ->count();

            $lifafa = User::select($columns)
                ->whereRaw($where_str, $where_params);
                // ->where('event_end_date', '>=', $date)
                // ->where('is_publish', 1);

            if ($request->get('iDisplayStart') != '' && $request->get('iDisplayLength') != '') {
                $lifafa = $lifafa->take($request->input('iDisplayLength'))
                    ->skip($request->input('iDisplayStart'));
            }

            if ($request->input('iSortCol_0')) {
                $sql_order = '';
                for ($i = 0; $i < $request->input('iSortingCols'); $i++) {
                    $column = $columns[$request->input('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $lifafa = $lifafa->orderBy($column, $request->input('sSortDir_' . $i));
                }
            }
            $lifafa = $lifafa->get();
            $response['iTotalDisplayRecords'] = $lifafa_count;
            $response['iTotalRecords'] = $lifafa_count;
            $response['sEcho'] = intval($request->input('sEcho'));
            $response['aaData'] = $lifafa->toArray();

            return $response;
        }
     return view('admin.fitness_center.index');
 }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.new');
    }
     public function store()
    {
         return view('admin.users.index');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $user = User::find($id); 
        return view('admin.users.edit', compact('user'));
    }

  
}
