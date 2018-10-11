<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Mail,Log;

class SendMailJob implements ShouldQueue{

	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public $view;
	public $emails;
	public $subject;
	public $data;

	public function __construct($view, $emails, $subject, $data) {
		$this->view = $view;
		$this->emails = $emails;
		$this->subject = $subject;
		$this->data = $data;
		// dd($data);
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {
		$view = $this->view;
		$emails = $this->emails;
		$subject = $this->subject;
		$data = $this->data;

		Mail::send($view, ['data'=>$data], function ($message) use ($emails,$subject) {
            foreach ($emails as $email=>$email_value) {
                $message->to($email_value);
                $message->cc('karishma.thinktanker@gmail.com');
	            $message->subject($subject);
	            $message->from('divyapatel2109@gmail.com');
            }
        });
	}
}
