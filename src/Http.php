<?php

namespace Chivincent\Youku;

use stdClass;

class Http
{
    public static function get(string $url, array $params = [])
    {
        return self::request('GET', $url, $params);
    }

    public static function post(string $url, array $params = [])
    {
        return self::request('POST', $url, $params);
    }

    public static function request(string $method, string $url, array $params = [])
    {
        $params = http_build_query($params);
        $ch = curl_init();

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        } else {
            $url .= "?$params";
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    public static function do_post_request(string $url, array $postData, string $data): stdClass
    {
        $str = '';
        foreach ($postData as $key => $value) {
            $str .= "$key=$value&";
        }
        $url = "$url?".rtrim($str, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/octet-stream',
            'Content-Length: '.strlen($data),
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
    }
}
