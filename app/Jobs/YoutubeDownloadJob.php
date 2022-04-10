<?php

namespace App\Jobs;

use App\Models\Conversion;
use App\Models\Youtube;
use App\Utilities\Converter;
use Exception;
use File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;
use YoutubeDl\Options;
use YoutubeDownload;

class YoutubeDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int */
    public int $tries = 1;

    /** @var int */
    public int $timeout = 3600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Youtube $youtube, private Conversion $conversion)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $youtubeModel = $this->youtube;
            $options = Options::create()
                ->continue(true)
                ->restrictFileNames(true)
                ->format('best')
                ->downloadPath(Storage::disk($this->conversion->source_disk)->path($this->conversion->guid.'_tmp'))
                ->url($this->youtube->url)
                ->noPlaylist()
                ->maxDownloads(1);

            $automaticCaption = true;
            if ($this->youtube->subtitle != null) {
                $collection = YoutubeDownload::download(
                    Options::create()->skipDownload(true)
                        ->url($this->youtube->url)
                        ->downloadPath(storage_path())
                        ->maxDownloads(1)
                );

                foreach($collection->getVideos() as $video) {
                    foreach ($video->getSubtitles() as $lang => $options) {
                        if($lang == $this->youtube->subtitle)
                            $automaticCaption = false;
                    }
                }
                if($automaticCaption) {
                    $options = $options->subLang([$this->youtube->subtitle])->writeAutoSub(true);
                } else {
                    $options = $options->subLang([$this->youtube->subtitle])->embedSubs(true);
                }
            }

            $collection = YoutubeDownload::onProgress(static function (?string $progressTarget, $percentage, string $size, $speed, $eta, ?string $totalTime) use ($youtubeModel) {
                $youtubeModel->update([
                    'progress' => $percentage,
                    'rate' => $speed,
                    'eta' => $eta
                ]);
            })->download($options);

            foreach ($collection->getVideos() as $video) {
                File::move($video->getFile()->getPathname(), Storage::disk($this->conversion->source_disk)->path($this->conversion->guid));
            }

            $converter = new Converter(Storage::disk($this->conversion->source_disk)->path($this->conversion->filename), $this->conversion);
        } catch (Exception $exception) {
            $this->conversion->failed = true;
            $this->conversion->probe_error = $exception->getMessage();
            $this->conversion->save();
            return;
        }

        if ($this->conversion->interpolation) {
            dispatch((new ConvertVideoJob($converter->getFFMpegConfig()))->onQueue('convertWithInterpolation'));
            return;
        }

        dispatch((new ConvertVideoJob($converter->getFFMpegConfig()))->onQueue('convert'));
    }
}
