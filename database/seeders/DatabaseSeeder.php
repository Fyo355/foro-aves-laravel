<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Species;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = collect([
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]),
            User::factory()->create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]),
            User::factory()->admin()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
            ]),
        ]);

        $species = collect(['Canario', 'Pinzon azul', 'Capirote', 'Hubara'])
            ->map(fn (string $speciesName) => Species::create(['name' => $speciesName]));

        Post::factory(20)
            ->recycle($users)
            ->recycle($species)
            ->create();

        Post::factory()
            ->recycle($species)
            ->create(['user_id' => null]);
    }
}
