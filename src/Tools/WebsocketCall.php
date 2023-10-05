<?php

namespace App\Tools;

class WebsocketPush {
    public static function channel(String $channelUuid)
    {
        $data = array(
            'channel' => $channelUuid,
        );

        $payload = json_encode($data);

        $ch = curl_init('http://localhost:3010/pusher');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload)
            )
        );

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // dump('Erreur cURL : ' . curl_error($ch));
        }

        curl_close($ch);

        // dump($response);
    }
}