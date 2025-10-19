<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();
        $tags = Tag::all();
        
        $posts = [
            [
                'title' => 'Introdução ao Laravel: Criando sua primeira API',
                'content' => 'Laravel é um framework PHP poderoso que facilita o desenvolvimento de aplicações web modernas. Neste post, vamos explorar como criar uma API RESTful do zero usando Laravel, implementando autenticação JWT e seguindo as melhores práticas de desenvolvimento.',
                'published_at' => now(),
            ],
            [
                'title' => 'Implementando Autenticação JWT em Laravel',
                'content' => 'A autenticação é um aspecto crucial de qualquer aplicação web. Neste tutorial, vamos implementar autenticação JWT (JSON Web Token) em Laravel, criando um sistema seguro e escalável para proteger nossas rotas de API.',
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Padrões de Arquitetura: Repository e Service Layer',
                'content' => 'Organizar o código de forma limpa e manutenível é essencial para projetos de longo prazo. Vamos explorar os padrões Repository e Service Layer, e como implementá-los em Laravel para criar uma arquitetura robusta.',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Trabalhando com Jobs e Queues no Laravel',
                'content' => 'Processamento assíncrono é fundamental para aplicações performantes. Aprenda como usar Jobs e Queues no Laravel para executar tarefas em segundo plano, como envio de emails e processamento de dados.',
                'published_at' => null, // Post não publicado
            ],
            [
                'title' => 'Documentação de API com Swagger no Laravel',
                'content' => 'Uma boa documentação é essencial para qualquer API. Vamos ver como integrar o Swagger ao Laravel para gerar documentação interativa e sempre atualizada da nossa API.',
                'published_at' => now()->subDays(3),
            ],
        ];
        
        foreach ($posts as $postData) {
            $post = Post::create([
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'title' => $postData['title'],
                'content' => $postData['content'],
                'published_at' => $postData['published_at'],
            ]);
            
            // Associar tags aleatórias
            $randomTags = $tags->random(rand(2, 5));
            $post->tags()->attach($randomTags->pluck('id'));
            
            // Criar alguns comentários para posts publicados
            if ($post->published_at) {
                $commentCount = rand(1, 4);
                for ($i = 0; $i < $commentCount; $i++) {
                    Comment::create([
                        'user_id' => $users->random()->id,
                        'post_id' => $post->id,
                        'body' => 'Este é um comentário de exemplo para o post "' . $post->title . '". Muito interessante o conteúdo!',
                    ]);
                }
            }
        }
    }
}
