<?php

namespace Modules\Projects\Http\Controllers;

use Illuminate\Support\Facades\App;
use Modules\Projects\Repositories\ProjectRepository;
use Modules\Core\Http\Controllers\BasePublicController;

class PublicController extends BasePublicController
{
    /**
     * @var PostRepository
     */
    private $project;

    public function __construct(ProjectRepository $project)
    {
        parent::__construct();
        $this->project = $project;
    }

    public function index()
    {
        $projects = $this->project->allTranslatedIn(App::getLocale());

        return view('projects.index', compact('projects'));
    }

    public function show($slug)
    {
        $project = $this->project->findBySlug($slug);

        return view('projects.show', compact('project'));
    }
}
