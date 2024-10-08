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
            'printer' => $printer
        ]);
    }

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

        $attributes['attention'] = $request->has('attention') ? 1 : 0;

        if ($request->file('logo')) {
            $folderName = $request->IP;
            $logoPaths = [];

            if (!Storage::disk('public')->exists("logos/{$folderName}")) {
                Storage::disk('public')->makeDirectory("logos/{$folderName}");
            }

            foreach ($request->file('logo') as $file) {
                $logoPaths[] = $file->store("logos/{$folderName}", 'public');
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
        return view('printers.edit', [
            'printer' => $printer
        ]);
    }


    public function update(Request $request, Printer $printer)
    {

        $attributes = $request->validate([
            'model' => ['required', 'string', 'max:255'],
            'number' => ['required', 'numeric', 'min:1', 'max:16777215'],
            'location' => ['required', 'string', 'max:255'],
            'IP' => ['required', 'unique:printers,IP,' . $printer->id, 'ip'],
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

        $attributes['attention'] = $request->has('attention') ? 1 : 0;

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

        return redirect('/');
    }

    public function destroy(Printer $printer)
    {
        $printer->delete();

        return redirect("/");
    }
}
