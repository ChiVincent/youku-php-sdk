<?php

namespace Chivincent\Youku;

use GuzzleHttp\Client;
use stdClass;

class Http
{
    public static function get(string $url, array $params = []): string
    {
        $client = new Client();

        $response = $client->get($url, [
            'query' => $params,
        ]);

        return $response->getBody()->getContents();
    }

    public static function post(string $url, array $params = [])
    {
        $client = new Client();

        $response = $client->post($url, [
            'form_params' => $params,
        ]);

        return $response->getBody()->getContents();
    }

    public static function doPostRequest(string $url, array $postData, string $data): stdClass
    {
        $client = new Client();

        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/octet-stream',
                'Content-Length' => strlen($data),
            ],
            'query' => $postData,
            'body' => $data,
        ]);

        return json_decode($response->getBody()->getContents());
    }
}
