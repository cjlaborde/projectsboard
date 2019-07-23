<?php

namespace App\Http\Controllers;

use App\User;
use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{

    public function index()
    {
        # Fetch all projects
//        $projects = Project::all();

        # Only see projects from selected logged in user
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    # Inject controller with Model
    public function show(Project $project)
    {
        if (auth()->user()->isNot($project->owner))
        {
            abort(403);
        }
//        $project = Project::findOrFail(request('project'));
        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        auth()->user()->projects()->create(request()->validate([
            'title' => 'required',
            'description' => 'required'
        ]));
//        dd($attributes);

        // redirect
        return redirect('/projects');
    }


}
