<?php

namespace App\Jobs;

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $number;
    public function __construct($number)
    {
        $this->number = $number;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Set your app credentials
        $username   = "";
        $apiKey     = "";


        // Initialize the SDK
        $AT         = new AfricasTalking($username, $apiKey);

        // Get the SMS service
        $sms        = $AT->sms();

        // Set the numbers you want to send to in international format
        $recipients = "+254" . $this->number;

        // Set your message
        $message    = "Thank you for participating in our TIFA research poll, we  sent you 50/= airtime. We will contact you in the near future for more polls.";

        // Set your shortCode or senderId
        $from       = "";
        $keyword = "";
        try {
            // Thats it, hit send and we'll take care of the rest
            $result = $sms->send([
                'to'      => $recipients,
                'message' => $message,
                'from'    => $from,
                'keyword' => $keyword
            ]);

            print_r($result);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
