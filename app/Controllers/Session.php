<?php

namespace App\Controllers;

use App\Libraries\DhonRestful;
use App\Models\SessionModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;

class Session extends ResourceController
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
     * @var \App\Models\SessionModel
     */
    protected $model;

    public function __construct()
    {
        $this->dhonrestful = new DhonRestful();
        $this->model = new SessionModel();
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
        //
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
    public function showListBySession($session)
    {
        $this->AutoWrapper(true);

        $response = $this->model->where('session', $session)->findAll();
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

    public function createIfEmpty($session = '')
    {
        $session = $session == '' ? $this->request->getPost('session') : $session;
        $sessions = json_decode($this->showListBySession($session)->getJSON(), true)['Data'];

        $response = (count($sessions) > 0) ? $sessions[0] : json_decode($this->create([
            'session' => $session
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
