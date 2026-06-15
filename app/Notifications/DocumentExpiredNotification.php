<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentExpiredNotification extends Notification
{
    use Queueable;

    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->error() // Change le bouton en rouge
            ->subject('🚨 Document expiré - YaArchiver_Pro')
            ->greeting('Alerte Sécurité,')
            ->line("Un document archivé a atteint sa date limite de conservation légale ou opérationnelle.")
            ->line("Document concerné : " . $this->document->titre)
            ->action('Gérer l\'archive', url('/documents'))
            ->line('Veuillez procéder à son renouvellement ou à sa destruction selon les directives de YA Consulting.');
    }

    public function toArray($notifiable)
    {
        return [
            'document_id' => $this->document->id,
            'titre' => $this->document->titre,
            'icone' => '🚨',
            'message' => "Le document '{$this->document->titre}' a expiré.",
            'url' => route('documents.index'),
        ];
    }
}