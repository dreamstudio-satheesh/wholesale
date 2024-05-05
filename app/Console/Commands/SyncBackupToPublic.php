<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SyncBackupToPublic extends Command
{
    protected $signature = 'sync:backup-public';
    protected $description = 'Remove storage/app/public folder and copy storage/app/backup to public folder';

    public function handle()
    {
        $publicPath = storage_path('app/public');
        $backupPath = storage_path('app/backup');
        $publicDestination = public_path();

        // Check and delete the public storage directory
        if (File::exists($publicPath)) {
            File::deleteDirectory($publicPath);
            $this->info('The storage/app/public folder has been removed.');
        }

        // Copy contents from backup to the public directory
        if (File::exists($backupPath)) {
            File::copyDirectory($backupPath, $publicPath);
            $this->info('Contents from storage/app/backup have been copied to the public folder.');
        } else {
            $this->error('The storage/app/backup folder does not exist.');
        }
    }
}
