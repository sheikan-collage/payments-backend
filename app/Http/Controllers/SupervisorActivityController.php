<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupervisorActivity\IndexActivitiesRequest;
use Spatie\Activitylog\Models\Activity;

class SupervisorActivityController extends Controller
{
    public function index(IndexActivitiesRequest $request)
    {
        $this->logSuccess('Retrieve Activities');
        return Activity::all();
    }
}
