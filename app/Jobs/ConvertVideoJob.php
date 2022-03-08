<?php

namespace App\Jobs;

use App\Models\Conversion;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int  */
    public int $tries = 3;

    /** @var int  */
    public int $timeout = 3600;

    /**
     * @param Conversion $conversion
     */
    public function __construct(private readonly Conversion $conversion)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $format = new X264();
        $filters = [
            '-profile:v', $this->conversion->result_profile,
            '-level', '4.0',
            '-preset', 'medium',
            '-fs', $this->conversion->result_size . 'k',
            //'-vf', 'minterpolate=fps=60',
            '-movflags', '+faststart'
        ];
        if ($this->conversion->result_audio)
            $format->setAudioKiloBitrate($this->conversion->result_audio);
        else
            $filters[] = '-an';

        $format->setAdditionalParameters($filters)
            ->setPasses(2)
            ->setKiloBitrate($this->conversion->result_bitrate);

        try {
            FFMpeg::fromDisk($this->conversion->source_disk)
                ->open($this->conversion->filename)
                ->resize($this->conversion->result_width, $this->conversion->result_height)
                ->addFilter(function (VideoFilters $filters) {
                    $filters->clip(TimeCode::fromSeconds($this->conversion->result_start), TimeCode::fromSeconds($this->conversion->result_duration));
                })
                ->export()
                ->onProgress(function ($percentage, $remaining, $rate) {
                    $this->conversion->update([
                        'converter_progress' => $percentage,
                        'converter_remaining' => $remaining,
                        'converter_rate' => $rate
                    ]);
                })
                ->inFormat($format)
                ->toDisk($this->conversion->result_disk)
                ->save($this->conversion->guid . '.mp4');
        } catch (EncodingException $exception) {
            $this->conversion->converter_error = $exception->getErrorOutput();
            $this->conversion->failed = true;
            $this->conversion->save();
        }
    }
}