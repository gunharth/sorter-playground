<?php

namespace Modules\Projects\Composer;

use Illuminate\Contracts\View\View;
use Modules\Projects\Repositories\ProjectRepository;

class HomeProjectsComposer
{
    private $projects;

    public function __construct(ProjectRepository $projects)
    {
        $this->projects = $projects;
    }

    public function compose(View $view)
    {
        $view->with('projects', $this->projects->all());
    }
}
