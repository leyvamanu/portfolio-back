<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudyResource;
use App\Models\Study;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StudyController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return StudyResource::collection(Study::all()->sortByDesc('start_date'));
    }
}
