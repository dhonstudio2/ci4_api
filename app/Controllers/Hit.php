<?php

namespace App\Controllers;

use App\Libraries\DhonRestful;
use App\Models\HitModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;

class Hit extends ResourceController
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
     * @var \App\Models\HitModel
     */
    protected $model;

    public function __construct()
    {
        $this->dhonrestful = new DhonRestful();
        $this->model = new HitModel();
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

        $response = $this->model->findAll();
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
    public function create()
    {
        $this->AutoWrapper(true);

        foreach ($this->model->allowedFields as $field) {
            $data[$field] = $this->request->getPost($field);
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
