<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function demo(){


    	$ftp_server = 'ftp.projectdemo.website';
        $username = "erp_projectdemo@erp.projectdemo.website";
        $password = "~LTE0~&-co}f";

        $ft_connect = ftp_connect($ftp_server) or die("Can't connect");
        $ftp_connection = ftp_login($ft_connect, $username, $password)  or die("Can't login");
        ftp_pasv($ft_connect,true);
        $current_date_dir = date('d-m-y');
        $local_dir_path_triton = "D:/workspace/ERPProductSystem/public/Tally_local/Triton/PO/import/";
        if(is_dir($local_dir_path_triton.$current_date_dir)){
            // dd('hi');
            $get_file_name = scandir($local_dir_path_triton.$current_date_dir);
            // dd($get_file_name);
            foreach ($get_file_name as $key => $value) {
                $get_file_extension = pathinfo($local_dir_path_triton.$current_date_dir.$value);
                if($get_file_extension['extension'] == "xml"){
                    file_get_contents($local_dir_path_triton.$current_date_dir.'/'.$value);
                    
                    $server_full_file_path = ftp_nlist($ft_connect, "/public/Tally/PO/import/");
                    //lyk rw
                    $import_file_path = ftp_nlist($ft_connect, "/public/Tally/PO/import/".$current_date_dir);  
                    // dd($ft_connect);
                    if(!in_array($current_date_dir,$server_full_file_path)){
                        ftp_mkdir($ft_connect,"/public/Tally/PO/import/".$current_date_dir);
                    }
                    ftp_put($ft_connect,"/public/Tally/PO/import/".$current_date_dir.'/'.$value,'D:/workspace/ERPProductSystem/public/Tally_local/PO/import/'.$current_date_dir.'/'.$value,FTP_BINARY);
                }
            }
        }
    }
}
