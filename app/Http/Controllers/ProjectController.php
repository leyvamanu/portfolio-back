<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ProjectResource::collection(Project::all());
    }
    public function featuredProjects(): AnonymousResourceCollection
    {
        return ProjectResource::collection(
            Project::query()->where('featured', true)->get()
        );

    }
}
