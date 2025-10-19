<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'API',
            'REST',
            'JWT',
            'Autenticação',
            'CRUD',
            'MVC',
            'Eloquent',
            'Migration',
            'Seeder',
            'Middleware',
            'Request',
            'Response',
            'Validation',
            'Repository Pattern',
            'Service Layer',
            'Resource',
            'Job',
            'Queue',
            'Notification',
            'Swagger',
        ];
        
        foreach ($tags as $tagName) {
            Tag::create([
                'name' => $tagName,
                'slug' => Str::slug($tagName),
            ]);
        }
    }
}
