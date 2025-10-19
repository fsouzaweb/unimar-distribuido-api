<?php

namespace Database\Seeders;

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
        // Criar usuários de teste
        User::factory()->create([
            'name' => 'Fabiano Souza',
            'email' => 'fabiano@blog.com',
        ]);
        
        User::factory()->create([
            'name' => 'Admin Blog',
            'email' => 'admin@blog.com',
        ]);
        
        User::factory(8)->create();
        
        // Executar outros seeders
        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            PostSeeder::class,
        ]);
    }
}
