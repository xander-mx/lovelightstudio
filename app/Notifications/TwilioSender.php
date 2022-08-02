<?php

namespace App\Notifications;

use Twilio\Rest\Client;

class TwilioSender
{

  public static function sms(string $message, string $client_phone) {
    $SID = env("TWILIO_SID");
    $TOKEN = env("TWILIO_TOKEN");
    $FROM = env("TWILIO_PHONE");
    $client = new Client($SID, $TOKEN);

    $client->messages->create($client_phone,
      [
        'from' => $FROM,
        'body' => $message
      ]
    );
  }

}