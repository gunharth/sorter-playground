<?php

namespace Modules\Projects\Repositories\Cache;

use Modules\Projects\Repositories\ProjectRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheProjectDecorator extends BaseCacheDecorator implements ProjectRepository
{
    public function __construct(ProjectRepository $project)
    {
        parent::__construct();
        $this->entityName = 'projects.projects';
        $this->repository = $project;
    }
}
