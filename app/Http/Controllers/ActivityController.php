<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function __invoke(): View
    {
        return view('activity.index', [
            'activities' => Activity::with('user')->latest()->paginate(20),
        ]);
    }
}

