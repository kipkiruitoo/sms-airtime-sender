<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AfricasTalking\SDK\AfricasTalking;

use Maatwebsite\Excel\Facades\Excel;

use App\Imports\ListImport;
use App\Jobs\SendAirtime;
use App\Jobs\SendSms;

class AirtimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function importExcel(Request $request)
    {

        $array = Excel::toArray(new ListImport, $request->file);


        foreach ($array[0] as $key => $value) {
            if ($key === 0) {
                # code...
            } else {
                SendSms::dispatch($value[2])->delay(now()->addMinutes(0.5));
                // echo (json_encode($value[2]));
                // echo '<br/>';
            }
        }

        // $json = json_encode($array[0]);

        // echo $json;
        // foreach ($array as  $item) {

        //     echo ();
        //     echo ("<br/>");
        //     # code...
        // }



        echo "Queued";
    }
    public function sendAirtime($number)
    {
        // Set your app credentials
        $username   = "sandbox";
        $apiKey     = "0bf880c3ff9ad01b10e25c162c3914d84824e14b6f378edfe603c2a5f14ea10a";


        // Initialize the SDK
        $AT       = new AfricasTalking($username, $apiKey);

        // Get the airtime service
        $airtime  = $AT->airtime();

        // Set the phone number, currency code and amount in the format below
        $recipients = [[
            "phoneNumber"  => "+254715686316",
            "currencyCode" => "KES",
            "amount"       => 5
        ]];

        try {
            // That's it, hit send and we'll take care of the rest
            $results = $airtime->send([
                "recipients" => $recipients
            ]);

            print_r($results);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
