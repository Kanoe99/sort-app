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
        if ($request->filled('q')) {
            $searchTerm = $request->input('q');

            // Convert the search term to lowercase and then capitalize each word
            $searchTerm = mb_convert_case($searchTerm, MB_CASE_TITLE, "UTF-8");

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw("model LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("location LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("IP LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("comment LIKE ?", ['%' . $searchTerm . '%']);
            });
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
