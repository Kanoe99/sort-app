<?php

namespace Database\Factories;

use App\Models\Employer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
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


        return [
            'model' => $model,
            'number' => rand(1000, 9999),
            'location' => rand(100, 599),
            'IP' => fake()->unique()->ipv4(),
            'status' => array_rand($statuses),
            'comment' => fake()->text(20),
            'attention' => false,

        ];
    }
}
