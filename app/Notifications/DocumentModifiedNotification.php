<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentModifiedNotification extends Notification
{
    use Queueable;

    protected $document;
    protected $operator;

    public function __construct(Document $document, $operator)
    {
        $this->document = $document;
        $this->operator = $operator;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⚠️ Modification d\'une archive - YaArchiver_Pro')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line("Les métadonnées d'un document ont été modifiées.")
            ->line("Document : " . $this->document->titre)
            ->line("Modifié par : " . $this->operator->name)
            ->action('Vérifier l\'historique', url('/audit-logs'))
            ->line('Cette action a été consignée dans le journal d\'audit.');
    }

    public function toArray($notifiable)
    {
        return [
            'document_id' => $this->document->id,
            'titre' => $this->document->titre,
            'icone' => '⚠️',
            'message' => "Le document '{$this->document->titre}' a été mis à jour par {$this->operator->name}.",
            'url' => route('dashboard'),
        ];
    }
}