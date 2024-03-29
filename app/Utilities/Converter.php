<?php

namespace App\Utilities;

use App\Models\Conversion;
use FFMpeg\FFProbe\DataMapping\Format;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\FFProbe;

class Converter
{
    const AVAILABLE_VIDEO_RATIOS = [
        0 => [
            'height' => 270,
            'bitrateMin' => 400,
            'profile' => 'baseline'
        ],
        1 => [
            'height' => 360,
            'bitrateMin' => 800,
            'profile' => 'main'
        ],
        2 => [
            'height' => 480,
            'bitrateMin' => 1200,
            'profile' => 'main'
        ],
        3 => [
            'height' => 540,
            'bitrateMin' => 1500,
            'profile' => 'main'
        ],
        4 => [
            'height' => 720,
            'bitrateMin' => 2000,
            'profile' => 'main'
        ],
        5 => [
            'height' => 1080,
            'bitrateMin' => 4000,
            'profile' => 'high'
        ]
    ];
    private int $audio;
    private int $start;
    private float $duration;
    private Format $format;
    private StreamCollection $streams;
    private int $bitrate;
    private int $width;
    private int $height;
    private string $profile;

    public function __construct(private readonly string $fileLocation, private readonly Conversion $conversion)
    {
        $probe = FFProbe::create(config('laravel-ffmpeg'));
        $this->format = $probe->format($this->fileLocation);
        $this->streams = $probe->streams($this->fileLocation);
        $this->sanitizeData();
        $this->prepareFFMpeg();
    }

    private function sanitizeData()
    {
        $duration = $this->calculateDuration();
        $originalDuration = $this->getOriginalDuration();
        $this->start = $this->conversion->start;
        $end = $this->start + $duration;
        $this->audio = $this->conversion->sound;


        /**
         * Error Case: Start is beyond the ending Duration
         * Solution: Move end duration (which is either equal or higher to start) to end of video,
         * then move start according to duration OR to 0
         */
        if ($this->start >= $originalDuration) {
            $end = $originalDuration;
            if ($duration > $originalDuration) {
                $this->start = 0;
            } else {
                $this->start = $end - $duration;
            }
        }

        /**
         * Error Case: Duration + start offset is larger than the original video duration
         * Solution: Move end duration to maximum time
         */
        if ($this->start + $duration > $originalDuration) {
            $end = $originalDuration;
        }

        $this->duration = $end - $this->start;

        /**
         * Error Case: Video does not have an Audio Stream
         * Solution: Disable audio
         */
        if (!$this->hasAudio())
            $this->audio = 0;

        /**format
         * Error Case: Audio is somehow larger than the actual max size
         * Solution: Disable audio
         */
        if ($this->audio * $this->duration > $this->conversion->size)
            $this->audio = 0;
    }

    /**
     * @return Repository|Application|mixed
     */
    private function calculateDuration(): mixed
    {
        $maximumDuration = config('pr0verter.maxResultLength');
        if($this->conversion->end == 0)
            $this->conversion->end = $this->conversion->start;

        $requestedDuration = $this->conversion->end - $this->conversion->start;
        if (!$requestedDuration)
            return $maximumDuration;
        return min($maximumDuration, $requestedDuration);
    }

    /**
     * @return mixed
     */
    private function getOriginalDuration(): mixed
    {
        return $this->format->get('duration');
    }

    /**
     * @return bool
     */
    private function hasAudio(): bool
    {

        return 0 < $this->streams->audios()->count();
    }

    private function prepareFFMpeg()
    {
        $this->findIdealResolutionAndBitrate();
        $this->conversion->update([
                'probe_score' => $this->format->get('probe_score'),
                'source_duration' => $this->format->get('duration'),
                'source_format' => $this->format->get('format_name'),
                'source_codec' => $this->streams->videos()->first()->get('codec_name'),
                'result_bitrate' => $this->bitrate,
                'result_height' => $this->height,
                'result_width' => $this->width,
                'result_start' => $this->start,
                'result_duration' => $this->duration,
                'result_audio' => $this->audio,
                'result_profile' => $this->profile,
                'result_size' => $this->conversion->size
            ]
        );
    }

    /**
     * @return void
     */
    private function findIdealResolutionAndBitrate()
    {
        $ratio = $this->streams->videos()->first()->getDimensions()->getRatio();
        $width = $this->streams->videos()->first()->getDimensions()->getWidth();
        $height = $this->streams->videos()->first()->getDimensions()->getHeight();

        if ((($width * $height / 1048576 > config('pr0verter.maxResultPixels')) && $this->conversion->ratio) || !$this->conversion->ratio) {
            /** First we try to find the closest resolution to the original */
            $closest = null;
            $idx = null;
            foreach (self::AVAILABLE_VIDEO_RATIOS as $index => $data) {
                \Log::critical('Index: ['.$index.'] Closest: ['.$closest.'] Height: ['.$height.'] DataHeight: ['.$data['height'].']');
                if ($closest === null || abs($height - $closest) > abs($data['height'] - $height)) {
                    $closest = $data['height'];
                    $idx = $index;
                }
            }

            /** Now we check if the minBitrate fits into the Video, so that we don't go over the maximum size Limit */
            for (; $idx > 0; $idx--) {
                $minBitrate = self::AVAILABLE_VIDEO_RATIOS[$idx]['bitrateMin'];
                \Log::critical('Index: ['.$idx.'] CalRate: ['.($minBitrate + $this->audio) * $this->duration.'] Size: ['.$this->conversion->size .']');
                if ((($minBitrate + $this->audio) * $this->duration) > ($this->conversion->size)) {
                    continue;
                } else {
                    break;
                }
            }
            $this->height = self::AVAILABLE_VIDEO_RATIOS[$idx]['height'];
            $this->width = $ratio->calculateWidth($this->height);
            $this->profile = self::AVAILABLE_VIDEO_RATIOS[$idx]['profile'];
        } else {
            $this->height = $height;
            $this->width = $width;
            $this->profile = 'baseline';
        }
        $this->bitrate = ($this->conversion->size - ($this->audio * $this->duration)) / $this->duration;
    }

    /**
     * @return Conversion
     */
    public function getFFMpegConfig(): Conversion
    {
        return $this->conversion;
    }

}
