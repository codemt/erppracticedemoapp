<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use File;

class ImageUploadJob
{
    // use SerializesModels;

    /**
    * Create a new job instance.
    *
    * @return void
    */
    public $oldimage;
    public $imagedata;
    public $foldername;
    public function __construct($oldimage,$foldername,$imagedata)
    {
        $this->oldimage    = $oldimage;
        $this->imagedata   = $imagedata;
        $this->foldername = $foldername;
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle()
    {
        $oldimage = $this->oldimage;
        $imagedata = $this->imagedata;
        $foldername = $this->foldername;
        // dd($imagedata);
        if (!empty($oldimage))
        {
            $path = LOCAL_UPLOAD_PATH.$foldername."/".$oldimage;
            if(file_exists($path))
            {
                unlink($path);
            }
        }

        if(isset($imagedata))
        {    
            $i=$imagedata;

            $filename = time()."_".$i->getClientOriginalName();
            dd($filename);
            $i->move(public_path().'/upload/'.$foldername,$filename);

        }

        return $filename;
    }
}