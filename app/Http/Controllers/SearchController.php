<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        // Get the query parameters
        $query = Printer::query();

        // Search by general query (e.g., model name)
        if ($request->filled('q')) {
            $query->whereRaw("LOWER(comment) LIKE ?", ['%' . strtolower($request->input('q')) . '%']);
        }

        // Filter by model if provided
        if ($request->filled('model')) {
            $query->where('model', $request->input('model'));
        }

        // Filter by location if provided
        if ($request->filled('location')) {
            $query->where('location', $request->input('location'));
        }

        // Execute the query with the filters
        $printers = $query->with('tags')->get();

        // Return the results view
        return view('results', ['printers' => $printers]);
    }

}
