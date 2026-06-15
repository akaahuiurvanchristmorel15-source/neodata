<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupSystem extends Command
{
    // Signature de la commande à exécuter
    protected $signature = 'system:backup';
    protected $description = 'Sauvegarde automatique quotidienne de la base SQLite et des archives chiffrées de YA Consulting';

    public function handle()
    {
        $this->info('🚀 Démarrage de la sauvegarde automatique...');

        $date = now()->format('Y-m-d_H-i-s');
        $backupDirectory = storage_path('app/backups/' . $date);

        if (!File::exists($backupDirectory)) {
            File::makeDirectory($backupDirectory, 0755, true);
        }

        // 1. Sauvegarde de la base de données SQLite
        $databasePath = database_path('database.sqlite');
        if (File::exists($databasePath)) {
            File::copy($databasePath, $backupDirectory . '/database.sqlite');
            $this->info('💾 Base de données SQLite sauvegardée.');
        }

        // 2. Compression et sauvegarde des fichiers chiffrés (Archives)
        $archivesPath = storage_path('app/archives');
        if (File::exists($archivesPath) && count(File::files($archivesPath)) > 0) {
            $zip = new ZipArchive;
            $zipFileName = $backupDirectory . '/archives_crypto.zip';

            if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
                $files = File::files($archivesPath);
                foreach ($files as $file) {
                    $zip->addFile($file->getRealPath(), $file->getFilename());
                }
                $zip->close();
                $this->info('🔒 Fichiers chiffrés compressés et sauvegardés.');
            }
        } else {
            $this->warn('⚠️ Aucun fichier chiffré à sauvegarder.');
        }

        // Enregistrement de la tâche système dans l'historique d'audit (Section 4.5)
        \App\Models\LogAction::create([
            'user_id' => null, // Script système automatique
            'action' => 'Sauvegarde système',
            'details' => "Sauvegarde automatisée réussie. Stockée dans : backups/{$date}"
        ]);

        $this->info("✨ Sauvegarde quotidienne terminée avec succès [{$date}].");
    }
}