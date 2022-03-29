<?php

namespace App\Jobs;

use App\Models\Conversion;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        foreach (Conversion::all() as $conversions) {
            if($conversions->getCreatedAtColumn() < Carbon::now()->subHour()) {
                \File::delete(\Storage::disk($conversions->source_disk)->path($conversions->guid));
                \File::delete(\Storage::disk($conversions->result_disk)->path($conversions->guid.'.mp4'));
                $conversions->delete();
            }
        }
    }
}
