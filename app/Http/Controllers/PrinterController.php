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

        $attributes['attention'] = $request->has('attention') ? 1 : 0;

        if ($request->filled('fixDate')) {
            $attributes['fixDate'] = Carbon::createFromFormat('Y-m-d', $request->input('fixDate'));
        }
        $attributes['counterDate'] = Carbon::now()->format('Y-m-d');
        if ($request->ip_exists === 'yes') {
            $ip = $request->input('IP');

            $ipValidationRules = ['required', 'unique:printers,IP'];

            if (strpos($ip, '.') !== false) {
                $ipValidationRules[] = 'ip:4';
            } elseif (strpos($ip, ':') !== false) {
                $ipValidationRules[] = 'ip:6';
            }

            $validatedData = $request->validate([
                'IP' => $ipValidationRules
            ], [
                'IP.unique' => 'Такой IP уже существует!',
                'IP.required' => 'Введите IP!',
                'IP.ip' => 'Введите корректный IP-адрес!'
            ]);
            $attributes['IP'] = $validatedData['IP'];
        }

        if ($request->file('logo')) {
            $folderName = $request->IP;
            $logoPaths = [];

            if (!Storage::disk('public')->exists("logos/{$folderName}")) {
                Storage::disk('public')->makeDirectory("logos/{$folderName}");
            }

            foreach ($request->file('logo') as $file) {
                try {
                    $logoPaths[] = $file->store("logos/{$folderName}", 'public');
                } catch (\Exception $e) {
                    return back()->withErrors(['logo' => 'Ошибка при загрузке файла: ' . $e->getMessage()]);
                }
            }

            $attributes['logo'] = json_encode($logoPaths);
        }

        $printer = Auth::user()->printers()->create(Arr::except($attributes, 'tags'));

        if ($attributes['tags'] ?? false) {
            // Use an array to store unique tags
            $tags = array_unique(array_map('trim', explode(',', $attributes['tags'])));

            foreach ($tags as $tag) {
                // Check if the tag already exists before tagging
                if (!empty($tag) && !$printer->tags()->where('name', $tag)->exists()) {
                    $printer->tag($tag);
                }
            }
        }

        return redirect('/');
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
            'type' => ['required', 'string', 'max:255'],
            'counter' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
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
            $attributes['IP'] = $request->IP;
        } else {
            // Set IP to null if it was previously set and now it is not
            if ($printer->IP) {
                $attributes['IP'] = null;
            }
        }

        $attributes['attention'] = $request->has('attention') ? 1 : 0;

        if ($printer->counter != $request->counter) {
            $attributes['counterDate'] = Carbon::now()->format('Y-m-d');
        }

        // Validate number only if it has changed
        if ($printer->number != $request->number) {
            $request->validate([
                'number' => ['required', 'unique:printers,number', 'numeric', 'min:1', 'max:16777215'],
            ], [
                'number.required' => 'Укажите номер принтера.',
                'number.unique' => 'Инвентарный номер ' . $request->number . ' уже существует!',
            ]);
            $attributes['number'] = $request->number; // Update the number in the attributes array
        }

        // Handle logo uploads
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
        $printer->update(Arr::except($attributes, 'tags'));

        if ($attributes['tags'] ?? false) {
            // Get the current tags associated with the printer
            $currentTags = $printer->tags()->pluck('name')->toArray();

            // Split and trim the incoming tags from the request
            $newTags = array_unique(array_map('trim', explode(',', $attributes['tags'])));

            // Find tags to add (new tags)
            $tagsToAdd = array_diff($newTags, $currentTags);

            foreach ($tagsToAdd as $tag) {
                if (!empty($tag)) {
                    // Check if the tag already exists in the database
                    $tagModel = Tag::firstOrCreate(['name' => $tag]);
                    $printer->tags()->attach($tagModel->id); // Attach using tag ID
                }
            }

            // Find tags to remove (existing tags not in the new list)
            $tagsToRemove = array_diff($currentTags, $newTags);

            foreach ($tagsToRemove as $tag) {
                // Get the tag ID before detaching
                $tagToRemove = Tag::where('name', $tag)->first();
                if ($tagToRemove) {
                    $printer->tags()->detach($tagToRemove->id); // Detach the tag
                }
            }
        }


        return redirect('/');
    }




    public function destroy(Printer $printer)
    {
        $printer->delete();
        return redirect("/");
    }
}
