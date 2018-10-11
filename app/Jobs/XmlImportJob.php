<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class XmlImportJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ftp_server = 'ftp.projectdemo.website';
        $username = "erp_projectdemo@erp.projectdemo.website";
        $password = '7x{u%MN$GhT@BWuitH';

        $ft_connect = ftp_connect($ftp_server) or die("Can't connect");
        $ftp_connection = ftp_login($ft_connect, $username, $password)  or die("Can't login");
        ftp_pasv($ft_connect,true);
        $current_date_dir = date('d-m-y');

        $local_dir_path_stellar = "file:///var/www/html/ERPProductSystem/public/Tally_local/Stellar/PO/import/";
        if(is_dir($local_dir_path_stellar.$current_date_dir)){
            // dd('hi');
            $get_file_name = scandir($local_dir_path_stellar.$current_date_dir);
            // dd($get_file_name);
            foreach ($get_file_name as $key => $value) {
                $get_file_extension = pathinfo($local_dir_path_stellar.$current_date_dir.$value);
                if($get_file_extension['extension'] == "xml"){
                    file_get_contents($local_dir_path_stellar.$current_date_dir.'/'.$value);

                    $server_full_file_path = ftp_nlist($ft_connect, "/public/Tally/Stellar/PO/import/");
                    //lyk rw
                    $import_file_path = ftp_nlist($ft_connect, "/public/Tally/Stellar/PO/import/".$current_date_dir);  
                    // dd($ft_connect);
                    if(!in_array($current_date_dir,$server_full_file_path)){
                        ftp_mkdir($ft_connect,"/public/Tally/Stellar/PO/import/".$current_date_dir);
                    }
                    ftp_put($ft_connect,"/public/Tally/Stellar/PO/import/".$current_date_dir.'/'.$value,'file:///var/www/html/ERPProductSystem/public/Tally_local/Stellar/PO/import/'.$current_date_dir.'/'.$value,FTP_BINARY);
                }
            }
        }


        $local_dir_path_triton = "file:///var/www/html/ERPProductSystem/public/Tally_local/Triton/PO/import/";
        if(is_dir($local_dir_path_triton.$current_date_dir)){
            // dd('hi');
            $get_file_name = scandir($local_dir_path_triton.$current_date_dir);
            // dd($get_file_name);
            foreach ($get_file_name as $key => $value) {
                $get_file_extension = pathinfo($local_dir_path_triton.$current_date_dir.$value);
                if($get_file_extension['extension'] == "xml"){
                    file_get_contents($local_dir_path_triton.$current_date_dir.'/'.$value);

                    $server_full_file_path = ftp_nlist($ft_connect, "/public/Tally/Triton/PO/import/");
                    //lyk rw
                    $import_file_path = ftp_nlist($ft_connect, "/public/Tally/Triton/PO/import/".$current_date_dir);  
                    // dd($ft_connect);
                    if(!in_array($current_date_dir,$server_full_file_path)){
                        ftp_mkdir($ft_connect,"/public/Tally/Triton/PO/import/".$current_date_dir);
                    }
                    ftp_put($ft_connect,"/public/Tally/Triton/PO/import/".$current_date_dir.'/'.$value,'file:///var/www/html/ERPProductSystem/public/Tally_local/Triton/PO/import/'.$current_date_dir.'/'.$value,FTP_BINARY);
                }
            }
        }
    }
}
