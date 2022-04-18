<?php

namespace App\Listeners;

use GuzzleHttp\Client;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use WetherController;

class NotificationListeners extends Injectable
{








    public function beforeHandleRequest(Event $event, \Phalcon\Mvc\Application $application)
    {
        if (!isset($this->session->exptime)) {
            return;
        }

        $t = time();
        $exptime = $this->session->exptime;
        if ($t > $exptime) {

            // $obj=new WetherController();

            // $obj->locationAction(1);


            // $key = $this->session->refresh_token;
            echo "expire token";
            die;

            // $clientId = '7a77bf68a8714904b8bfc541bfe37030';
            // $clientSecret = 'c6ae9bce2a8a466c86c4586929852945';
            // $url = "https://accounts.spotify.com";

            // $headers = [
            //     'Content-Type' => 'application/x-www-form-urlencoded',
            //     'Authorization' => 'Basic ' . base64_encode($clientId . ":" . $clientSecret)
            // ];

            // $client = new Client(
            //     [

            //         'base_uri' => $url,
            //         'headers' => $headers
            //     ]
            // );
            // $query = ["grant_type" => 'refresh_token', 'refresh_token' => $key];
            // $response = $client->request('POST', '/api/token', ['form_params' => $query]);

            // $response =  $response->getBody();
            // $response = json_decode($response, true);

            // $token = $response['access_token'];

            // $t = time();
            // $exptime = $t + 3500;

            // $refresh_token = $response['refresh_token'];

            // $this->session->exptime = $exptime;

            // $this->session->token = $token;
            // $this->session->refresh_token = $refresh_token;

            return;
        }
    }
}
