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
     * Fetch all printers and group them based on the 'attention' field.
     */
    public function index()
    {
        $printers = Printer::all();

        // Retrieve distinct models and locations for the dropdowns
        $models = Printer::distinct()->pluck('model');
        $locations = Printer::distinct()->pluck('location');
        // Fetch all printers and load their related tags
        $printers = Printer::with(['tags'])->latest()->get();

        // Filter printers with 'attention' set to true and false
        $aprinters = $printers->where('attention', true);
        $regularPrinters = $printers->where('attention', false);

        return view('printers.index', [
            'aprinters' => $aprinters,
            'printers' => $regularPrinters,
            'tags' => Tag::all(),
            'models' => $models,
            'locations' => $locations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('printers.create');
    }

    /**
     * Display the specified resource.
     */
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
        // Validate input data, including logo files
        $attributes = $request->validate([
            'model' => ['required', 'string', 'max:255'],
            'number' => ['required', 'numeric', 'min:1', 'max:16777215'],
            'location' => ['required', 'string', 'max:255'],
            'IP' => ['required', 'unique:printers,IP', 'ip'],
            'status' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],
            'attention' => ['nullable'],
            'logo.*' => ['nullable', 'mimes:jpg,jpeg,png'],
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

        // Convert the checkbox value for attention into a boolean (1 or 0)
        $attributes['attention'] = $request->has('attention') ? 1 : 0;

        // Handle logo file uploads if provided
        if ($request->file('logo')) {
            $folderName = $request->IP;  // Use printer's IP as the folder name
            $logoPaths = [];

            // Create the directory if it doesn't exist
            if (!Storage::disk('public')->exists("logos/{$folderName}")) {
                Storage::disk('public')->makeDirectory("logos/{$folderName}");
            }

            // Store each uploaded logo file
            foreach ($request->file('logo') as $file) {
                $logoPaths[] = $file->store("logos/{$folderName}", 'public');
            }

            // Save the logo paths as a JSON string
            $attributes['logo'] = json_encode($logoPaths);
        }

        // Create the printer record in the database for the authenticated user
        $printer = Auth::user()->printers()->create(Arr::except($attributes, 'tags'));

        // Handle tags, if provided
        if ($attributes['tags'] ?? false) {
            foreach (explode(',', $attributes['tags']) as $tag) {
                $printer->tag($tag);  // Attach the tags to the printer
            }
        }

        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Printer $printer)
    {
        return view('printers.edit', [
            'printer' => $printer
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Printer $printer)
    {
        // Validate input data, including logo files
        $attributes = $request->validate([
            'model' => ['required', 'string', 'max:255'],
            'number' => ['required', 'numeric', 'min:1', 'max:16777215'],
            'location' => ['required', 'string', 'max:255'],
            'IP' => ['required', 'unique:printers,IP,' . $printer->id, 'ip'],  // Exclude current printer from uniqueness check
            'status' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],  // Comma-separated tags
            'attention' => ['nullable'],
            'logo.*' => ['nullable', 'mimes:jpg,jpeg,png'],  // Allow only certain file types
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

        // Ensure the 'attention' field is handled properly (either 1 or 0)
        $attributes['attention'] = $request->has('attention') ? 1 : 0;

        // Handle existing logos and deleted logos
        $existingLogos = json_decode($printer->logo, true) ?? [];
        $logoPaths = $existingLogos;

        // Handle removed logos if specified
        if ($request->filled('removed_logos')) {
            $removedLogos = json_decode($request->removed_logos);
            $logoPaths = array_diff($existingLogos, $removedLogos);  // Remove deleted logos
        }

        // Handle new logo uploads
        if ($request->file('logo')) {
            $folderName = $request->IP;

            // Store new logo files and add their paths to the array
            foreach ($request->file('logo') as $file) {
                $logoPaths[] = $file->store("logos/{$folderName}", 'public');
            }
        }

        // Update the printer's logo field with the new or remaining logo paths
        $attributes['logo'] = json_encode($logoPaths);

        // Update the printer's other attributes in the database
        $printer->update(Arr::except($attributes, 'tags'));

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Printer $printer)
    {
        // Delete the printer
        $printer->delete();

        return redirect("/");
    }
}
