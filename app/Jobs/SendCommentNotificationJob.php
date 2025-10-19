<?php

namespace App\Jobs;

use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCommentNotificationJob implements ShouldQueue
{
    use Queueable;

    protected $comment;

    /**
     * Create a new job instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Carrega os relacionamentos necessários
            $this->comment->load(['author', 'post.author']);
            
            $postAuthor = $this->comment->post->author;
            $commentAuthor = $this->comment->author;
            
            // Não envia notificação se o autor do comentário é o mesmo do post
            if ($postAuthor->id === $commentAuthor->id) {
                return;
            }
            
            // Simulação de envio de email (pode ser substituído por Mail::send)
            $emailData = [
                'post_title' => $this->comment->post->title,
                'post_author' => $postAuthor->name,
                'comment_author' => $commentAuthor->name,
                'comment_body' => $this->comment->body,
                'post_url' => config('app.url') . '/posts/' . $this->comment->post->id,
            ];
            
            // Log da notificação (substitui o envio real de email para demonstração)
            Log::info('Notificação de novo comentário enviada', [
                'to' => $postAuthor->email,
                'post_id' => $this->comment->post->id,
                'comment_id' => $this->comment->id,
                'data' => $emailData
            ]);
            
            // Aqui seria o código real de envio de email:
            // Mail::to($postAuthor->email)->send(new CommentNotificationMail($emailData));
            
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação de comentário', [
                'comment_id' => $this->comment->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e; // Re-lança a exceção para que o job seja reprocessado
        }
    }
}
