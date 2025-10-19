<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Tecnologia',
            'Programação',
            'Laravel',
            'PHP',
            'JavaScript',
            'Desenvolvimento Web',
            'Mobile',
            'DevOps',
            'Banco de Dados',
            'Inteligência Artificial',
        ];
        
        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);
        }
    }
}
