<?php

namespace CodeProject\Services;

use CodeProject\Repositories\ProjectFileRepository;
use CodeProject\Repositories\ProjectNoteRepository;
use CodeProject\Validators\ProjectFileValidator;
use CodeProject\Validators\ProjectNoteValidator;
use Illuminate\Contracts\Filesystem\Factory as Storage;

class ProjectFileService
{
    /**
     * @var ProjectNoteRepository
     */
    protected $repository;
    /**
     * @var ProjectNoteValidator
     */
    protected $validator;
    /**
     * @var Storage
     */
    private $storage;

    private $filename;

    public function __construct(ProjectFileRepository $repository, ProjectFileValidator $validator, Storage $storage)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->storage = $storage;
        $this->filename = '';
    }

    public function checkPermission($id)
    {
        if ($this->checkProjectOwner($id) || $this->checkProjectMember($id)) {
            return true;
        }
        return false;
    }

    public function checkProjectOwner($id)
    {
        if ($this->repository->isOwner($id, Authorizer::getResourceOwnerId())) {
            return true;
        }
        return false;
    }

    public function checkProjectMember($id)
    {

        if ($this->repository->hasMember($id, Authorizer::getResourceOwnerId())) {
            return true;
        }
        return false;
    }

    public function getFileName(){
        return $this->filename;
    }

    public function getFilePath($id)
    {
        $project_file = $this->repository->skipPresenter()->find($id);
        return $this->getBaseUrl($project_file);
    }

    public function getBaseUrl($project_file)
    {
        $this->filename = $project_file->id . '.' . $project_file->extension;
        switch ($this->storage->getDefaultDriver()) {
            case 'local':
                return $this->storage->getDriver()->getAdapter()->getPathPrefix()
                . $project_file->id . '.' . $project_file->extension;
        }
    }
}