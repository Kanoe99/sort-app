<?php

namespace Database\Factories;

use App\Models\Printer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Printer>
 */
class PrinterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $prefixes = ['Принтер', 'HP'];
        $names = ['Samsung', 'LaserJet'];

        $randomPrefixKey = array_rand($prefixes);
        $randomNameKey = array_rand($names);

        $model = $prefixes[$randomPrefixKey] . ' ' . $names[$randomNameKey] . ' ' . strval(rand(100, 999));

        $statuses = ['подготовка к списанию', 'в эксплуатации', 'требуется ремонт', 'резерв'];

        // Ensure locale is set correctly
        setlocale(LC_TIME, 'ru_RU.UTF-8');

        return [
            'type' => fake()->text(5),
            // Convert to Carbon instances before formatting
            'counterDate' => \Carbon\Carbon::parse(fake()->dateTime())->format('d-m-Y H:i:s'), // Day first, then month and year
            'fixDate' => \Carbon\Carbon::parse(fake()->date())->format('d-m-Y'), // Day first
            'model' => strval($model),
            'number' => rand(1000, 9999),
            'location' => rand(100, 599),
            'IP' => fake()->unique()->ipv4(),
            'status' => $statuses[array_rand($statuses)],
            'comment' => fake()->text(20),
            'attention' => false,
            'counter' => fake()->numberBetween(100, 9999),
        ];
    }
}
