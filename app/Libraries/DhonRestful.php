<?php

namespace App\Libraries;

use CodeIgniter\RESTful\ResourceController;

class DhonRestful extends ResourceController
{
    protected $response;
    protected $autowrapper;
    protected $message;

    public function __construct()
    {
        $this->response = service('response');
    }

    public function AutoWrapper($bool)
    {
        $this->autowrapper = $bool;
        return;
    }

    public function AddMessage($msg)
    {
        $this->message = $msg;
        return;
    }

    public function Ok($response)
    {
        if ($this->autowrapper) {
            if (is_array($response) && count($response) != count($response, COUNT_RECURSIVE)) $result["Total"] = count($response);
            $result["Data"] = $response;
            if ($this->message) $result["Message"] = $this->message;
        } else $result = $response;

        return $this->respond($result);
    }
}
