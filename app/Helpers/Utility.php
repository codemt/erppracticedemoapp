<?php 

namespace App\Helpers;
use Input;

class Utility {

public static function  getRandomCharString($length, $lower = true, $upper = true, $nums = true, $special = true)
    {
        $pool_lower = 'abcdefghijklmopqrstuvwxyz';
        $pool_upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pool_nums = '0123456789';
        $pool_special = '!"#$%&()*+,-./:;<=>?@[\]^_`{|}~';
        
        $pool_char_idx = rand() % strlen($pool_special);
        $char_special = substr($pool_special, $pool_char_idx, 2);
       
       
        $pool = '';
        $res = '';
        $finalpwd = '';
       
        if ($lower === true) {
            $pool .= $pool_lower;
        }
        if ($upper === true) {
            $pool .= $pool_upper;
        }
        if ($nums === true) {
            $pool .= $pool_nums;
        }
        
        $length =6;
        srand((double) microtime() * 1000000);
       
        for ($i = 0; $i < $length; $i++) {
            $charidx = rand() % strlen($pool);
            $char = substr($pool, $charidx, 1);
            $finalpwd .= $char;
        }
        $res = $finalpwd.$char_special;
        // $res = $finalpwd;
        // return $res;

        // $pool = 'abcdefghijklmopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!$%^&*+#~/|';
        // $res = '';
        // $length = 0;

        // srand((double) microtime() * 1000000);
       
        // for ($i = 0; $i < $length; $i++) {
        //     $charidx = rand() % strlen($pool);
        //     $char = substr($pool, $charidx, 1);
        //     $res .= $char;
        // }
        return $res;
    }
}
