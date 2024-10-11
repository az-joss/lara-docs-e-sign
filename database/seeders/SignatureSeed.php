<?php

namespace Database\Seeders;

use App\Models\Signature;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SignatureSeed extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::pluck('id')->each(function(int $userId) {
            Signature::factory(2)
                ->create([
                    'user_id' => $userId
            ]);
        });
    }
}
