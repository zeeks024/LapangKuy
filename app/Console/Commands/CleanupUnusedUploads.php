<?php

namespace App\Console\Commands;

use App\Models\Field;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupUnusedUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uploads:cleanup {--days=30 : Days to keep unused files} {--dry-run : Run in simulation mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up unused image uploads from the storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $days = (int) $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Cleaning up unused uploads older than {$days} days...");
        if ($dryRun) {
            $this->warn("Running in dry-run mode - no files will be deleted");
        }
        
        // List all field images
        $path = public_path('assets/images/fields');
        $this->info("Scanning directory: {$path}");
        
        if (!File::exists($path)) {
            $this->error("Directory not found: {$path}");
            return 1;
        }
        
        // Get all field image URLs from database
        $fieldImages = Field::pluck('image_url')->filter()->toArray();
        $activeImageNames = [];
        
        foreach ($fieldImages as $url) {
            // Extract the filename from the URL
            $parts = explode('/', $url);
            $filename = end($parts);
            $activeImageNames[] = $filename;
        }
        
        $this->info("Found " . count($activeImageNames) . " active image references in database");
        
        // Find files in directory that aren't referenced in the database
        $allFiles = File::files($path);
        $unusedFiles = [];
        $savedSpace = 0;
        
        foreach ($allFiles as $file) {
            $filename = $file->getFilename();
            $fileModifiedTime = Carbon::createFromTimestamp($file->getMTime());
            $fileSize = $file->getSize();
            
            // Check if file is not referenced and older than cutoff date
            if (!in_array($filename, $activeImageNames) && $fileModifiedTime->lt($cutoffDate)) {
                $unusedFiles[] = [
                    'path' => $file->getPathname(),
                    'size' => $fileSize,
                    'modified' => $fileModifiedTime->format('Y-m-d H:i:s')
                ];
                
                $savedSpace += $fileSize;
            }
        }
        
        // Report results
        $this->info("Found " . count($unusedFiles) . " unused files that can be removed");
        $this->info("Total space to be recovered: " . $this->formatBytes($savedSpace));
        
        if (count($unusedFiles) > 0) {
            // If not dry run, delete the files
            if (!$dryRun) {
                $this->output->progressStart(count($unusedFiles));
                
                foreach ($unusedFiles as $file) {
                    File::delete($file['path']);
                    $this->output->progressAdvance();
                }
                
                $this->output->progressFinish();
                $this->info("Successfully deleted " . count($unusedFiles) . " unused files");
            } else {
                $this->table(
                    ['File Path', 'Size', 'Modified Date'],
                    array_map(function ($file) {
                        return [
                            $file['path'],
                            $this->formatBytes($file['size']),
                            $file['modified']
                        ];
                    }, array_slice($unusedFiles, 0, 10))
                );
                
                if (count($unusedFiles) > 10) {
                    $this->warn("... and " . (count($unusedFiles) - 10) . " more files");
                }
            }
        }
        
        return 0;
    }
    
    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
