<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = [
            'unsigned',
            'pending',
            'rejected',
            'signed',
        ];

        return [
            'file_name' => fake()->bothify('???_?????_????-####'),
            'file_path' => '/documents/' . fake()->uuid() . '.pdf',
            'status' => fake()->randomElement($statuses),
        ];
    }
}
