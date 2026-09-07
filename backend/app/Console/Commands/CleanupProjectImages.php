<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CleanupProjectImages extends Command
{
    protected $signature = 'cleanup:project-images';

    protected $description = 'Delete old or unused project images from uploads/projects folder';

    public function handle()
    {
        $activeImages = DB::table('project_images')
            ->whereNull('deleted_at')
            ->pluck('image')
            ->toArray();

        $activeImages = array_map('basename', $activeImages);
        $allFiles = Storage::disk('public')->files('uploads/projects');

        $deleted = 0;

        foreach ($allFiles as $filePath) {
            if (!in_array(basename($filePath), $activeImages)) {
                Storage::disk('public')->delete($filePath);
                $this->info("Deleted: " . basename($filePath));
                $deleted++;
            }
        }

        $this->info("✅ Cleanup complete. Deleted $deleted unused images.");
    }
}
?>