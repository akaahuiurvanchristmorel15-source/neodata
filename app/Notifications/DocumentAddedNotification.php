<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentAddedNotification extends Notification
{
    use Queueable;

    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    // Choix des canaux : Base de données + Email
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    // Contenu pour le canal Email
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📥 Nouveau document archivé - YaArchiver_Pro')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line("Un nouveau document a été chiffré et ajouté aux archives de YA Consulting.")
            ->line("Titre : " . $this->document->titre)
            ->line("Auteur : " . $this->document->auteur)
            ->action('Consulter l\'archive', url('/documents'))
            ->line('Merci d\'utiliser notre système d\'archivage sécurisé.');
    }

    // Contenu pour le canal Base de données (Affichage interface)
    public function toArray($notifiable)
    {
        return [
            'document_id' => $this->document->id,
            'titre' => $this->document->titre,
            'icone' => '📥',
            'message' => "Le document '{$this->document->titre}' a été ajouté par {$this->document->auteur}.",
            'url' => route('documents.index'),
        ];
    }
}