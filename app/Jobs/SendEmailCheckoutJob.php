<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use PDF;

class SendEmailCheckoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to_email;
    protected $email_data;
    protected $pdf;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to_email, $email_data, $pdf)
    {
        $this->to_email = $to_email;
        $this->email_data = $email_data;
        $this->pdf = $pdf;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send([], [], function ($message) {
            $message->to($this->to_email)
                ->subject($this->email_data['title'])
                ->attachData($this->pdf->output(), "payment.pdf")
                ->setBody($this->email_data['body']);
        });
    }
}
