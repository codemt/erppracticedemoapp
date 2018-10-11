<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\State;


class SaveStateJob implements ShouldQueue
{
    public $add_state;

    public $id;
    use Dispatchable, InteractsWithQueue;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($add_state)
    {
        $this->add_state = $add_state;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $add_state = $this->add_state;
        $id = \Request::segment(3);
        if (isset($add_state['id'])) {
            $id = $add_state['id'];
        }
        $addstate = State::firstOrNew(['id' => $id]);
        $addstate->fill($add_state);
        //$addstate->title = $add_state['title'];
        $addstate->slug = str_slug($addstate->title);
        $addstate->country_id = '101';
        $addstate->save();
    }
}
