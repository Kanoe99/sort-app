<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PrinterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch printers with 'attention' set to true
        $aprinters = Printer::where('attention', true)->with(['tags'])->latest()->get();

        // Optionally, fetch printers with 'attention' set to false
        $printers = Printer::where('attention', false)->with(['tags'])->latest()->get();

        return view('printers.index', [
            'aprinters' => $aprinters,
            'printers' => $printers,
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
            'IP' => ['required', 'unique:printers,IP', 'ip'],
            'status' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],
            'attention' => ['nullable'],
            'logo.*' => ['nullable', 'mimes:jpg,jpeg,png',],
        ], [
            'model.required' => 'Укажите модель принтера.',
            'model.max' => 'Максимум 255 символов.',

            'number.required' => 'Укажите номер принтера.',
            'number.max' => 'Номер не должен превышать 16777215 символов.',

            'location.required' => 'Укажите локацию принтера.',
            'location.max' => 'Максимум 255 символов.',

            'IP.required' => 'Укажите IP адрес принтера.',
            'IP.unique' => 'Данный IP адрес уже занят.',
            'IP.ip' => 'IP адрес должен быть верным.',

            'status.required' => 'Укажите статус принтера.',
            'status.max' => 'Максимум 255 символов.',

            'comment.max' => 'Максимум 255 символов.',

            'logo.mimes' => 'Только PNG, JPG или JPEG!',
        ]);


        $attributes['attention'] = $request->has('attention');


        if ($request->file('logo')) {
            $folderName = $request->IP; // Or dynamic folder name based on printer model, etc.

            // Ensure the directory exists, create it if not
            if (!Storage::disk('public')->exists("logos/{$folderName}")) {
                Storage::disk('public')->makeDirectory("logos/{$folderName}");
            }

            foreach ($request->file('logo') as $file) {
                $logoPaths[] = $file->store("logos/{$folderName}", 'public'); // Store each file
            }

            $attributes['logo'] = json_encode($logoPaths);
        }


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
    public function update(Request $request, Printer $printer)
    {
        // Validate the printer fields
        $attributes = $request->validate([
            'model' => ['required', 'string', 'max:255'],
            'number' => ['required', 'numeric', 'min:1', 'max:16777215'],
            'location' => ['required', 'string', 'max:255'],
            'IP' => ['required', 'unique:printers,IP,' . $printer->id, 'ip'],  // Ensure uniqueness excluding current printer
            'status' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],  // Comma-separated tags
            'attention' => ['nullable'],
            'logo' => ['nullable', 'mimes:jpg,jpeg,png'],  // Allow only certain file types
        ], [
            'model.required' => 'Укажите модель принтера.',
            'model.max' => 'Максимум 255 символов.',

            'number.required' => 'Укажите номер принтера.',
            'number.max' => 'Номер не должен превышать 16777215 символов.',

            'location.required' => 'Укажите локацию принтера.',
            'location.max' => 'Максимум 255 символов.',

            'IP.required' => 'Укажите IP адрес принтера.',
            'IP.unique' => 'Данный IP адрес уже занят.',
            'IP.ip' => 'IP адрес должен быть верным.',

            'status.required' => 'Укажите статус принтера.',
            'status.max' => 'Максимум 255 символов.',

            'comment.max' => 'Максимум 255 символов.',

            'logo.mimes' => 'Только PNG, JPG или JPEG!',
        ]);

        $attributes['attention'] = $request->has('attention');

        // Handle the logo upload if there's a new logo
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $attributes['logo'] = $logoPath;
        }

        // Update the printer instance
        $printer->update(Arr::except($attributes, 'tags'));

        // Handle the comma-separated tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));

            // Find or create the tags and get their IDs
            $tagIds = [];
            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }

            // Sync the printer with the tag IDs (update the pivot table)
            $printer->tags()->sync($tagIds);
        }

        return redirect('/');
    }



    public function destroy(Printer $printer)
    {
        $printer->delete();

        return redirect("/");
    }
}
