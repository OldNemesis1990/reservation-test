<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    public function index() {
        $envName = config('app.name');

        return Inertia::render('Dashboard/Staff/Dashboard', [
            'envName' => $envName
        ]);
    }
}
