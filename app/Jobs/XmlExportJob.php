<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Admin;

class XmlExportJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    // public $file;
    public function __construct()
    {
        // $this->file = $file;

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
        if(!is_dir(public_path()."/Tally_local/Stellar/PO/export/".$current_date_dir)){
            mkdir(public_path()."/Tally_local/Stellar/PO/export/".$current_date_dir);
        }
        $server_full_file_path = ftp_nlist($ft_connect, "/public/Tally/Stellar/PO/export/".$current_date_dir.'/*.*');//lyk rw  projyen path 
        foreach($server_full_file_path as $key=>$value){
            $get_server_path = explode('/public',$value);//remove public from path
            $get_xml_filename = strrchr($get_server_path[1] , '/');//filename
                $file = file_get_contents("http://erp.projectdemo.website".$get_server_path[1]);
                $tmp_file = fopen('file:///var/www/html/ERPProductSystem/public/Tally_local/Stellar/PO/export/'.$current_date_dir.$get_xml_filename,'w');
                file_put_contents('file:///var/www/html/ERPProductSystem/public/Tally_local/Stellar/PO/export/'.$current_date_dir.$get_xml_filename,$file);
        }


        if(!is_dir(public_path()."/Tally_local/Triton/PO/export/".$current_date_dir)){
            mkdir(public_path()."/Tally_local/Triton/PO/export/".$current_date_dir);
        }
        $server_full_file_path = ftp_nlist($ft_connect, "/public/Tally/Triton/PO/export/".$current_date_dir.'/*.*');//lyk rw  projyen path 
        foreach($server_full_file_path as $key=>$value){
            $get_server_path = explode('/public',$value);//remove public from path
            $get_xml_filename = strrchr($get_server_path[1] , '/');//filename
                $file = file_get_contents("http://erp.projectdemo.website".$get_server_path[1]);
                $tmp_file = fopen('file:///var/www/html/ERPProductSystem/public/Tally_local/Triton/PO/export/'.$current_date_dir.$get_xml_filename,'w');
                file_put_contents('file:///var/www/html/ERPProductSystem/public/Tally_local/Triton/PO/export/'.$current_date_dir.$get_xml_filename,$file);
        }
    }
}
