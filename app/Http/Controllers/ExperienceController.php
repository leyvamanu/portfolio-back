<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExperienceController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ExperienceResource::collection(Experience::all()->sortByDesc('start_date'));
    }
}
