<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create()->each(function ($user) {
            for ($i = 0; $i <= rand(25, 50); $i++) {
                $received = rand(0, 1);

                \App\Models\EmailMessage::factory()->create([
                    'user_id' => $user->id,
                    'sent_by' => $received ? fake()->unique()->safeEmail() : $user->name . '@nebulasend.com',
                    'sent_to' => $received ? $user->name . '@nebulasend.com' : fake()->unique()->safeEmail()
                ]);
            }
        });
    }
}
