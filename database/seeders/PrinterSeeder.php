<?php

namespace Database\Seeders;

use App\Models\Printer;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class PrinterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = Tag::factory(3)->create();

        Printer::factory(20)->hasAttached($tags)->create(new Sequence([
            'attention' => false,
        ], [
            'attention' => true,
        ]));
    }
}
