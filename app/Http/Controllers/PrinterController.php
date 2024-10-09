<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PrinterController extends Controller
{
    public function index()
    {
        $limit = 15;

        $printers = Printer::with(['tags'])->latest()->take($limit)->get();
        $aprinters = Printer::with(['tags'])->where('attention', true)->get();
        $tags = Tag::all();

        return view('printers.index', [
            'printers' => $printers,
            'aprinters' => $aprinters,
            'tags' => $tags,
        ]);
    }

    public function all()
    {
        $printers = Printer::with(['tags'])->latest()->paginate(5);
        return view('printers.all', [
            'printers' => $printers,
        ]);
    }

    public function create()
    {
        return view('printers.create');
    }

    public function show(Printer $printer)
    {
        return view('printers.show', [
            'printer' => $printer,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $attributes = $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'counter' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'number' => ['required', 'unique:printers,number', 'numeric', 'min:1', 'max:16777215'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],
            'attention' => ['nullable'],
            'logo.*' => ['nullable', 'mimes:jpg,jpeg,png'],
        ], [
            'type.required' => 'Укажите модель принтера.',
            'counter.required' => 'Укажите номер счетчика.',
            'model.required' => 'Укажите модель принтера.',
            'number.required' => 'Укажите номер принтера.',
            'number.unique' => 'Инвентарный номер уже существует!',
            'location.required' => 'Укажите локацию принтера.',
            'status.required' => 'Укажите статус принтера.',
            'fixDate.required' => 'Укажите дату последнего ремонта.',
            'logo.*.mimes' => 'Только PNG, JPG или JPEG!',
        ]);
        // Set attention based on checkbox
        $attributes['attention'] = $request->has('attention') ? 1 : 0;

        // Convert string dates to Carbon instances
        if ($request->filled('fixDate')) {
            $attributes['fixDate'] = Carbon::createFromFormat('Y-m-d', $request->input('fixDate'));
        }
        $attributes['counterDate'] = Carbon::now()->format('Y-m-d');

        if ($request->ip_exists === 'yes') {
            $attributes['IP'] = $request->validate([
                'IP' => ['required']
            ], [
                'IP.required' => 'Введите IP!'
            ]);
        }

        // $attributes[''] = ;

        // Handle file uploads
        if ($request->file('logo')) {
            $folderName = $request->IP; // Use the IP address as folder name
            $logoPaths = [];

            // Ensure the directory exists
            if (!Storage::disk('public')->exists("logos/{$folderName}")) {
                Storage::disk('public')->makeDirectory("logos/{$folderName}");
            }

            // Store each logo file
            foreach ($request->file('logo') as $file) {
                try {
                    $logoPaths[] = $file->store("logos/{$folderName}", 'public');
                } catch (\Exception $e) {
                    return back()->withErrors(['logo' => 'Ошибка при загрузке файла: ' . $e->getMessage()]);
                }
            }

            // Store the paths in JSON format
            $attributes['logo'] = json_encode($logoPaths);
        }

        // Create the printer record
        $printer = Auth::user()->printers()->create(Arr::except($attributes, 'tags'));

        // Handle tags if provided
        if ($attributes['tags'] ?? false) {
            foreach (explode(',', $attributes['tags']) as $tag) {
                $printer->tag($tag);
            }
        }

        // Redirect with success message
        return redirect('/')->with('success', 'Принтер успешно добавлен!');
    }

    public function edit(Printer $printer)
    {
        return view('printers.edit', [
            'printer' => $printer,
        ]);
    }

    public function update(Request $request, Printer $printer)
    {
        $attributes = $request->validate([
            'model' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],
            'attention' => ['nullable'],
            'logo.*' => ['nullable', 'mimes:jpg,jpeg,png'],
        ], [
            'model.required' => 'Укажите модель принтера.',
            'location.required' => 'Укажите локацию принтера.',
            'status.required' => 'Укажите статус принтера.',
            'logo.*.mimes' => 'Только PNG, JPG или JPEG!',
        ]);

        // Validate IP if it exists
        if ($request->ip_exists === 'yes') {
            $request->validate([
                'IP' => ['required', 'unique:printers,IP,' . $printer->id, 'ip'],
            ], [
                'IP.required' => 'Укажите IP адрес принтера.',
                'IP.unique' => 'Данный IP адрес уже занят.',
                'IP.ip' => 'IP адрес должен быть верным.',
            ]);
            $attributes['IP'] = $request->IP; // Store IP if it exists
        } else {
            // If IP does not exist and there was an IP before, clear the IP field
            if ($printer->IP) {
                $attributes['IP'] = null; // Remove IP address
            }
        }

        // Handling the attention checkbox
        $attributes['attention'] = $request->has('attention') ? 1 : 0;

        // Validate number if it has changed
        if ($printer->number != $request->number) {
            $attributes = $request->validate(
                [
                    'number' => ['required', 'unique:printers,number', 'numeric', 'min:1', 'max:16777215'],
                ],
                [
                    'number.required' => 'Укажите номер принтера.',
                    'number.unique' => 'Инвентарный номер ' . $request->number . ' уже существует!',
                ]
            );
        }

        // Handle logos
        $existingLogos = json_decode($printer->logo, true) ?? [];
        $logoPaths = $existingLogos;

        if ($request->filled('removed_logos')) {
            $removedLogos = json_decode($request->removed_logos);
            $logoPaths = array_diff($existingLogos, $removedLogos);
        }

        if ($request->file('logo')) {
            $folderName = $request->IP;

            foreach ($request->file('logo') as $file) {
                $logoPaths[] = $file->store("logos/{$folderName}", 'public');
            }
        }

        $attributes['logo'] = json_encode($logoPaths);

        // Update the printer record
        $printer->update(Arr::except($attributes, 'tags'));

        return redirect('/');
    }


    public function destroy(Printer $printer)
    {
        $printer->delete();
        return redirect("/");
    }
}
