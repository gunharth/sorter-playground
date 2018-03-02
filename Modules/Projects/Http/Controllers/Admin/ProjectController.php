<?php

namespace Modules\Projects\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Projects\Entities\Project;
use Modules\Projects\Http\Requests\CreateProjectRequest;
use Modules\Projects\Http\Requests\UpdateProjectRequest;
use Modules\Projects\Repositories\ProjectRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class ProjectController extends AdminBaseController
{
    /**
     * @var ProjectRepository
     */
    private $project;

    public function __construct(ProjectRepository $project)
    {
        parent::__construct();

        $this->project = $project;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $projects = $this->project->all();

        return view('projects::admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('projects::admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProjectRequest $request
     * @return Response
     */
    public function store(CreateProjectRequest $request)
    {
        $this->project->create($request->all());

        return redirect()->route('admin.projects.project.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('projects::projects.title.projects')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Project $project
     * @return Response
     */
    public function edit(Project $project)
    {
        return view('projects::admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Project $project
     * @param  UpdateProjectRequest $request
     * @return Response
     */
    public function update(Project $project, UpdateProjectRequest $request)
    {
        $this->project->update($project, $request->all());

        return redirect()->route('admin.projects.project.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('projects::projects.title.projects')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Project $project
     * @return Response
     */
    public function destroy(Project $project)
    {
        $this->project->destroy($project);

        return redirect()->route('admin.projects.project.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('projects::projects.title.projects')]));
    }
}
