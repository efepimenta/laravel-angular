<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ClientRepository;
use CodeProject\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * @var ClientRepository
     */
    private $repository;
    /**
     * @var ClientService
     */
    private $service;

    public function __construct(ClientRepository $repository, ClientService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(){
        return $this->repository->all();
    }

    public function show($id){
        return $this->repository->find($id);
    }

    public function create(Request $request){
        return $this->service->create($request->all());
    }

    public function update(Request $request, $id){
        return $this->service->update($request->all(), $id);
    }

    public function delete($id){
        $this->service->delete($id);
    }
}
