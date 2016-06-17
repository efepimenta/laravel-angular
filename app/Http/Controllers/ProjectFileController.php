<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectFileRepository;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectFileService;
use CodeProject\Services\ProjectService;
use CodeProject\Validators\ProjectFileValidator;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectFileController extends Controller
{
    /**
     * @var ProjectRepository
     */
    private $repository;
    /**
     * @var ProjectService
     */
    private $service;
    /**
     * @var ProjectFileValidator
     */
    private $validator;
    /**
     * @var ProjectRepository
     */
    private $projectRepository;
    /**
     * @var ProjectService
     */
    private $projectService;

    public function __construct(ProjectFileRepository $repository, ProjectFileService $service, ProjectFileValidator $validator,
                                ProjectRepository $projectRepository, ProjectService $projectService)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->validator = $validator;
        $this->projectRepository = $projectRepository;
        $this->projectService = $projectService;
    }

    public function index($project_id)
    {
        return $this->repository->skipPresenter()->findWhere(['project_id' => $project_id]);
    }

    public function show($file_id)
    {
        return $this->repository->find($file_id);
    }

    public function update(Request $request, $file_id)
    {
        return $this->repository->update($request->all(), $file_id);
    }

    public function download($file_id)
    {
        return response()->download($this->service->getFilePath($file_id));
    }

    public function store(Request $request, $project_id)
    {
        $file = $request->file('file');
        if ($file->getError()) {
            return [
                'error' => true,
                'message' => $file->getErrorMessage(),
            ];
        }
        $extension = $file->getClientOriginalExtension();
        $data['file'] = $file;
        $data['extension'] = $extension;
        $data['name'] = $request->name;
        $data['project_id'] = $project_id;
        $data['description'] = $request->description;

        try {
            $this->validator->with($data)->passesOrFail();
        } catch (ValidatorException $e) {
            return [
                'error' => true,
                'message' => $e->getMessageBag(),
            ];
        }
        if ($this->projectService->createFile($data)) {
            return [
                'success' => true,
                'message' => 'Envio de arquivo completo'
            ];
        }
        return [
            'error' => true,
            'message' => 'Falha no Envio de arquivo'
        ];
    }

    public function destroy(Request $request, $project_id)
    {
        $data['name'] = $request->name;
        $data['project_id'] = $project_id;
        $data['extension'] = $request->extension;
        if ($this->projectService->deleteFile($data)) {
            return [
                'success' => true,
                'message' => 'Remoção de arquivo completo'
            ];
        }
        return [
            'error' => true,
            'message' => 'Falha na remoção de arquivo ou arquivo não encontrado'
        ];
    }

}
