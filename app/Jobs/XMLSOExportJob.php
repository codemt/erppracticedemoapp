<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Mail,Log;
use App\Models\SalesOrder;

class XMLSOExportJob{

	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */

	public function __construct() {
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {
		Log::info('hi');
		$ftp_server = 'ftp.projectdemo.website';
        $username = "erp_projectdemo@erp.projectdemo.website";
        $password = '7x{u%MN$GhT@BWuitH';

        $ft_connect = ftp_connect($ftp_server) or die("Can't connect");
        $ftp_connection = ftp_login($ft_connect, $username, $password)  or die("Can't login");
        ftp_pasv($ft_connect,true);
        $current_date_dir = date('d-m-y');

        //Triton
        if(!is_dir(public_path()."/Tally_local/Triton/SO/export/".$current_date_dir)){
            mkdir(public_path()."/Tally_local/Triton/SO/export/".$current_date_dir);
        }
        $server_full_file_path = ftp_nlist($ft_connect, "/public/Tally/Triton/SO/export/".$current_date_dir.'/*.*');//lyk rw  projyen path 

        foreach($server_full_file_path as $key=>$value){
            $get_server_path = explode('/public',$value);//remove public from path
            $get_xml_filename = strrchr($get_server_path[1] , '/');//filename
            Log::info($get_xml_filename);
                $file = file_get_contents("http://erp.projectdemo.website".$get_server_path[1]);
                $tmp_file = fopen('file:///var/www/workspace/ERPProductSystem/public/Tally_local/Triton/SO/export/'.$current_date_dir.$get_xml_filename,'w');
                file_put_contents('file:///var/www/workspace/ERPProductSystem/public/Tally_local/Triton/SO/export/'.$current_date_dir.$get_xml_filename,$file);
        }

        //Stellar
        if(!is_dir(public_path()."/Tally_local/Stellar/SO/export/".$current_date_dir)){
            mkdir(public_path()."/Tally_local/Stellar/SO/export/".$current_date_dir);
        }
        $server_full_file_path = ftp_nlist($ft_connect, "/public/Tally/Stellar/SO/export/".$current_date_dir.'/*.*');//lyk rw  projyen path 

        foreach($server_full_file_path as $key=>$value){
            $get_server_path = explode('/public',$value);//remove public from path
            $get_xml_filename = strrchr($get_server_path[1] , '/');//filename
            Log::info($get_xml_filename);
                $file = file_get_contents("http://erp.projectdemo.website".$get_server_path[1]);
                $tmp_file = fopen('file:///var/www/workspace/ERPProductSystem/public/Tally_local/Stellar/SO/export/'.$current_date_dir.$get_xml_filename,'w');
                file_put_contents('file:///var/www/workspace/ERPProductSystem/public/Tally_local/Stellar/SO/export/'.$current_date_dir.$get_xml_filename,$file);
        }
	}
}
