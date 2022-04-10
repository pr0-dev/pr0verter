<?php

namespace App\Jobs;

use App\Models\Conversion;
use Carbon\Carbon;
use File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;

class CleanupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (Conversion::get() as $conversions) {
            if($conversions->created_at < Carbon::now()->subHours(2)) {
                foreach(Storage::disk($conversions->source_disk)->allFiles() as $file) {
                    if (str_contains($file, $conversions->guid)) {
                        Storage::disk($conversions->source_disk)->delete($file);
                    }
                }
                foreach(Storage::disk($conversions->result_disk)->allFiles() as $file) {
                    if (str_contains($file, $conversions->guid)) {
                        Storage::disk($conversions->result_disk)->delete($file);
                    }
                }
                $conversions->delete();
            }
        }

        foreach(Storage::disk('local')->allFiles() as $file) {
            if(str_contains($file, '.git'))
                continue;

            if(Carbon::createFromTimestamp(fileatime(Storage::disk('local')->path($file))) < Carbon::now()->subDays(2)) {
                Storage::delete($file);
            } elseif ((100 - disk_free_space('/') / disk_total_space('/') * 100) > 80 && Carbon::createFromTimestamp(fileatime(Storage::disk('local')->path($file))) < Carbon::now()->subHours(2)) {
                Storage::delete($file);
            }
        }

        $protectedDirectories = [
            'converted',
            'converted/Upload',
            'converted/Youtube',
            'converted/Download',
            'raw',
            'raw/Upload',
            'raw/Youtube',
            'raw/Download',
            'public'
        ];

        foreach(Storage::disk('local')->allDirectories() as $dir) {
            if(str_contains($dir, '.git'))
                continue;

            if(in_array($dir, $protectedDirectories)) {
                continue;
            }

            if(Carbon::createFromTimestamp(Storage::disk('local')->lastModified($dir)) < Carbon::now()->subDays(2)) {
                Storage::disk('local')->deleteDirectory($dir);
            }

        }
    }
}
