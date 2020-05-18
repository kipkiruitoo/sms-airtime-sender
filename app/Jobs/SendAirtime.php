<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Sent;
use Illuminate\Support\Facades\Storage;
use AfricasTalking\SDK\AfricasTalking;


class SendAirtime implements ShouldQueue
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
        $AT       = new AfricasTalking($username, $apiKey);

        // Get the airtime service
        $airtime  = $AT->airtime();

        // Set the phone number, currency code and amount in the format below
        $recipients = [[
            "phoneNumber"  => "+254" . $this->number,
            "currencyCode" => "KES",
            "amount"       => 50
        ]];

        try {
            // That's it, hit send and we'll take care of the rest
            $results = $airtime->send([
                "recipients" => $recipients
            ]);

            // $back = $results['data']->responses;

            $contents = Storage::get('results.json');
            $tempArray = json_decode($contents);
            $results1 = (array) $results;
            array_push($tempArray,   $results1);
            $jsonData = json_encode($tempArray);
            Storage::put('results.json', $jsonData);
            // print_r($back);
            // $record = new Sent();
            // $record->errorMessage = $back->errorMessage;
            // $record->amount = $back->amount;
            // $record->discount = $back->discount;
            // $record->phoneNumber = $back->phoneNumber;
            // $record->requestId = $back->requestId;
            // $record->status = $back->status;

            // $record->save();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
