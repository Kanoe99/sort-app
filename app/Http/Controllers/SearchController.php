<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke()
    {
        $jobs = Job::query()
            ->with(['employer', 'tags'])
            ->whereRaw("LOWER(title) LIKE ?", '%' . strtolower(request('q')) . '%')
            ->get();

        return view('results', ['jobs' => $jobs]);
    }

}
