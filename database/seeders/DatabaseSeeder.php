<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Comente ou remova as linhas que usam Factory, como esta:
        // \App\Models\User::factory(10)->create();

        // Mantenha apenas os seeders de estrutura que você precisa no Render
        $this->call([
            UfSeeder::class,
            RodoviaSeeder::class,
        ]);
    }
}