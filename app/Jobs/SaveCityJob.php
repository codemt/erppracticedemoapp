<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\City;

class SaveCityJob implements ShouldQueue
{

    public $request_add_city;

    public $id;

    use Dispatchable, InteractsWithQueue;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request_add_city)
    {
        $this->request_add_city = $request_add_city;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {

        $request_add_city = $this->request_add_city;
        $id = \Request::segment(3);
        // dd($id);
        //dd($request_add_city);  
        if (isset($request_add_city['id'])) {
            $id = $request_add_city['id'];
        }
        $addcity = City::firstOrNew(['id' => $id]);
        $addcity->fill($request_add_city);
        //$addcity->title = $request_add_city['title'];
         $addcity->slug = str_slug($request_add_city['title']); 
        $addcity->state_id = $request_add_city['state']; 
        $addcity->save();
    }
}
