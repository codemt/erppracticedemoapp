<?php
namespace App\Helpers;

class FileHelp{
	public static function getfilename($file){
        $microtime       = microtime();
        $search          = array('.',' ');
        $microtime       = str_replace($search, "_", $microtime);
        $fileName        = $microtime.'_'.$file->getClientOriginalName();

        return $fileName;
    }
    public static function getfolderpathexit($filepath,$imagename){
        $file_exit = $filepath.'/'.$imagename;
        if (empty($imagename)) {
            return "no";
        }else{
            if (file_exists($file_exit)) {
                return "yes";
            }else{
                return "no";
            }
        }
    }

    public static function UnlinkImage($filepath,$fileName){
        $old_image = $filepath.$fileName;
        // dd($old_image);
        if (file_exists($old_image)) {
            @unlink($old_image);
        }
    }
}