<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail,Log;
use App\Models\SalesOrder;

class XMLSOImportJob{

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
		Log::info('import');
		$ftp_server = 'ftp.projectdemo.website';
        $username = "erp_projectdemo@erp.projectdemo.website";
        $password = '7x{u%MN$GhT@BWuitH';

        $ft_connect = ftp_connect($ftp_server) or die("Can't connect");
        $ftp_connection = ftp_login($ft_connect, $username, $password)  or die("Can't login");
        ftp_pasv($ft_connect,true);
        $current_date_dir = date('d-m-y');

        //Triton
        $triton_local_dir_path = "file:///var/www/workspace/ERPProductSystem/public/Tally_local/Triton/SO/import/";
        if(is_dir($triton_local_dir_path.$current_date_dir)){
            $get_file_name = scandir($triton_local_dir_path.$current_date_dir);
            foreach ($get_file_name as $key => $value) {
                $get_file_extension = pathinfo($triton_local_dir_path.$current_date_dir.$value);
                if($get_file_extension['extension'] == "xml"){
                    file_get_contents($triton_local_dir_path.$current_date_dir.'/'.$value);
                    $server_full_file_path = ftp_nlist($ft_connect, "/public/Tally/Triton/SO/import/");
                    if(!in_array($current_date_dir,$server_full_file_path)){
                        ftp_mkdir($ft_connect,"/public/Tally/Triton/SO/import/".$current_date_dir);
                    }
                    ftp_put($ft_connect,"/public/Tally/SO/import/".$current_date_dir.'/'.$value,'file:///var/www/workspace/ERPProductSystem/public/Tally_local/Triton/SO/import/'.$current_date_dir.'/'.$value,FTP_BINARY);
                }
            }
        }

        //Stellar
        $stellar_local_dir_path = "file:///var/www/workspace/ERPProductSystem/public/Tally_local/Stellar/SO/import/";
        if(is_dir($stellar_local_dir_path.$current_date_dir)){
            $get_file_name = scandir($stellar_local_dir_path.$current_date_dir);
            foreach ($get_file_name as $key => $value) {
                $get_file_extension = pathinfo($stellar_local_dir_path.$current_date_dir.$value);
                if($get_file_extension['extension'] == "xml"){
                    file_get_contents($stellar_local_dir_path.$current_date_dir.'/'.$value);
                    $server_full_file_path = ftp_nlist($ft_connect, "/public/Tally/Stellar/SO/import/");
                    if(!in_array($current_date_dir,$server_full_file_path)){
                        ftp_mkdir($ft_connect,"/public/Tally/Stellar/SO/import/".$current_date_dir);
                    }
                    ftp_put($ft_connect,"/public/Tally/SO/import/".$current_date_dir.'/'.$value,'file:///var/www/workspace/ERPProductSystem/public/Tally_local/Stellar/SO/import/'.$current_date_dir.'/'.$value,FTP_BINARY);
                }
            }
        }
	}
}
