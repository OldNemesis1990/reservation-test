<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\YourEmailTemplate;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $emailTemplate;

    /**
     * Create a new job instance.
     */
    public function __construct($data, $emailTemplate)
    {
        $this->data = $data;
        $this->emailTemplate = $emailTemplate;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->data['user'][0]->email)->send(new $this->emailTemplate($this->data));
    }
}
