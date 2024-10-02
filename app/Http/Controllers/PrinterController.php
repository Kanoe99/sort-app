<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class PrinterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $printers = Printer::latest()->with(['tags'])->get()->groupBy('attention');



        return view('printers.index', [
            'printers' => $printers[0],
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('printers.create');
    }
    public function show(Printer $printer)
    {
        return view('printers.show', [
            'printer' => $printer
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'model' => ['required', 'string', 'max:255'],
            'number' => ['required', 'numeric', 'min:1', 'max:16777215'],
            'location' => ['required', 'string', 'max:255'],
            'IP' => ['required', 'ip'],
            'status' => ['required', 'string', 'max:255'],
            'comment' => ['required', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],
            'attention' => ['nullable', 'nullable'],
        ]);

        $attributes['attention'] = $request->has('attention');

        $printer = Auth::user()->printers()->create(Arr::except($attributes, 'tags'));

        if ($attributes['tags'] ?? false) {
            foreach (explode(',', $attributes['tags']) as $tag) {
                $printer->tag($tag);
            }
        }

        return redirect('/');
    }


    public function edit(Printer $printer)
    {
        return view(
            'printers.edit',
            [
                'printer' => $printer
            ]
        );
    }
    public function update(Printer $printer, Request $request)
    {
        // Validate the printer fields
        $attributes = $request->validate([
            'model' => ['required', 'string', 'max:255'],
            'number' => ['required', 'numeric', 'min:1', 'max:16777215'],
            'location' => ['required', 'string', 'max:255'],
            'IP' => ['required', 'ip'],
            'status' => ['required', 'string', 'max:255'],
            'comment' => ['required', 'string', 'max:255'],
            'attention' => ['nullable'],
        ]);

        // Update the printer
        $printer->update($attributes);

        // Handle the tags (comma-separated string)
        if ($request->filled('tags')) {
            // Split the tags by commas and trim whitespace
            $tags = array_map('trim', explode(',', $request->tags));

            // Find or create the tags, and get their IDs
            $tagIds = [];
            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]); // Create tag if it doesn't exist
                $tagIds[] = $tag->id; // Collect the tag IDs
            }

            // Sync the printer with the tag IDs
            $printer->tags()->sync($tagIds); // Update the pivot table
        }

        return redirect("/printers/" . $printer->id);
    }



    public function destroy(Printer $printer)
    {
        $printer->delete();

        return redirect("/");
    }
}
