<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Document;
use App\Models\User;
use App\Notifications\DocumentExpiredNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\LogAction;

class BackupDatabase extends Command
{
    // Signature de la commande à appeler dans le terminal
    protected $signature = 'app:backup';
    protected $description = 'Sauvegarde automatique de la base de données SQLite et des documents chiffrés';

    public function handle()
    {
        $date = now()->format('Y-m-d_H-i-s');
        $backupFolderName = "sauvegardes/backup_{$date}";

        // 1. Sauvegarde de la base de données SQLite
        $sqlitePath = database_path('database.sqlite');
        if (File::exists($sqlitePath)) {
            Storage::put("{$backupFolderName}/database.sqlite", File::get($sqlitePath));
        }

        // 2. Copie de tous les fichiers chiffrés du dossier archives
        $files = Storage::files('archives');
        foreach ($files as $file) {
            Storage::copy($file, "{$backupFolderName}/" . basename($file));
        }

        $this->info("Sauvegarde automatique complétée avec succès le {$date}.");

        // Traçabilité : Enregistrement du log de sauvegarde dans le système
        LogAction::create([
            'user_id' => null, // Exécuté par le système
            'action' => 'Sauvegarde Système',
            'details' => "Une sauvegarde automatique globale a été générée dans le répertoire : {$backupFolderName}"
        ]);

        // À insérer à la fin de la méthode handle() de votre commande :
        $documentsExpires = Document::whereNotNull('date_expiration')
            ->where('date_expiration', '<=', now()->toDateString())
            ->get();

        foreach ($documentsExpires as $doc) {
            $destinataires = User::whereIn('role', ['admin', 'direction'])->get();
            Notification::send($destinataires, new DocumentExpiredNotification($doc));
        }
    }
}