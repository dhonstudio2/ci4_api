<?php

namespace App\Controllers;

use App\Libraries\DhonCurl;
use App\Libraries\DhonRestful;
use App\Models\AddressModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;

class Address extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @var \App\Libraries\DhonRestful
     */
    protected $dhonrestful;

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @var \App\Models\AddressModel
     */
    protected $model;

    public function __construct()
    {
        $this->dhonrestful = new DhonRestful();
        $this->model = new AddressModel();
    }

    public function __call($method, $args)
    {
        return $this->dhonrestful->$method($args[0]);
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function index()
    {
        $this->AutoWrapper(true);

        $response = $this->model->first();
        return $this->Ok($response);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function showListByIP($ip)
    {
        $this->AutoWrapper(true);

        $response = $this->model->where('ip_address', $ip)->findAll();
        return $this->Ok($response);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create($data = '')
    {
        $this->AutoWrapper(true);

        if ($data == '') {
            foreach ($this->model->allowedFields as $field) {
                $data[$field] = $this->request->getPost($field);
            }
        }

        try {
            $new_id = $this->model->insert($data);
            $response = $this->model->find($new_id);
            $this->AddMessage("POST Success");
            return $this->Ok($response);
        } catch (\Throwable $th) {
            return Services::response()
                ->setJSON(['Message' => $th->getMessage()])
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function createIfEmpty($ip_address = '')
    {
        $dhoncurl = new DhonCurl();

        $ip_address = $ip_address == '' ? $this->request->getPost('ip_address') : $ip_address;
        $addresses = json_decode($this->showListByIP($ip_address)->getJSON(), true)['Data'];

        $response = (count($addresses) > 0) ? $addresses[0] : json_decode($this->create([
            'ip_address' => $ip_address,
            'ip_info' => json_encode($dhoncurl->curl_get("http://ip-api.com/json/{$ip_address}"))
        ])->getJSON(), true)['Data'];

        return $this->Ok($response);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
