<?php
use App\Enums\ProjectStatus;
use App\Enums\ProjectPriority;
use App\Http\Resources\V1\ProjectResource;
use App\Models\Folder;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

