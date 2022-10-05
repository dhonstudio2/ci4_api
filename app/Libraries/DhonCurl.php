<?php

namespace App\Libraries;

use CodeIgniter\Controller;

class DhonCurl extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = \Config\Services::curlrequest();
    }

    public function curl_get($url)
    {
        $result = $this->client->request('GET', $url)->getJSON();
        return json_decode(json_decode($result, true), true);
    }
}
