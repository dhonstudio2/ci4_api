<?php

namespace App\Libraries;

use CodeIgniter\Controller;
use CodeIgniter\RESTful\ResourceController;

use function PHPUnit\Framework\isJson;

class DhonRestful extends ResourceController
{
    protected $response;
    protected $autowrapper;

    public function __construct()
    {
        $this->response = service('response');
    }

    public function AutoWrapper($bool)
    {
        $this->autowrapper = $bool;
        return;
    }

    public function Ok($response)
    {
        if ($this->autowrapper) {
            if (count($response) != count($response, COUNT_RECURSIVE)) $result["Total"] = count($response);
            $result["Data"] = $response;
        } else $result = $response;
        return $this->respond($result);
    }
}
