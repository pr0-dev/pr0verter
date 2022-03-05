<?php

namespace App\Utilities\Converter;

use App\Models\Upload;
use FFMpeg\Format\Video\X264;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use JetBrains\PhpStorm\ArrayShape;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\FFProbe;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class Converter {
    private int $resultSize;
    private int $audio;
    private int $start;
    private int $end;
    private bool $keepResolution;
    private float $duration;
    private \FFMpeg\FFProbe $probe;

    private int $bitrate;
    private int $width;
    private int $height;
    private string $profile;

    const availableVideoRatios = [
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
        ],
        6 => [
            'height' => 2160,
            'bitrateMin' => 8000,
            'profile' => 'high'
        ]
    ];

    public function __construct(private readonly Request $request, private readonly string $fileLocation, private readonly string $guid)
    {
        $this->probe = FFProbe::create(config('laravel-ffmpeg'));
        $this->resultSize = $this->request->get('resultSize') * 1024;
        $this->keepResolution = $this->request->get('keepResolution');
        $this->sanitizeData();
        $this->prepareFFMpeg();
    }

    private function sanitizeData() {
        $duration = $this->calculateDuration();
        $originalDuration = $this->getOriginalDuration();
        $requestStart = $this->request->get('start');


        /**
         * Error Case: Start is beyond the ending Duration
         * Solution: Move end duration (which is either equal or higher to start) to end of video,
         * then move start according to duration OR to 0
         */
        if($requestStart >= $originalDuration) {
            $this->end = $originalDuration;
            if($duration > $originalDuration) {
                $this->start = 0;
            } else {
                $this->start = $this->end - $duration;
            }
        }

        /**
         * Error Case: Duration + start offset is larger than the original video duration
         * Solution: Move end duration to maximum time
         */
        if($requestStart + $duration > $originalDuration) {
            $this->end = $originalDuration;
        }

        $this->duration = $this->end - $this->start;

        /**
         * Error Case: Video does not have an Audio Stream
         * Solution: Disable audio
         */
        if(!$this->hasAudio())
            $this->audio = 0;

        /**
         * Error Case: Audio is somehow larger than the actual max size
         * Solution: Disable audio
         */
        if($this->audio * $this->duration > $this->resultSize)
            $this->audio = 0;
    }

    /**
     * @return mixed
     */
    private function getOriginalDuration(): mixed
    {
        return $this->probe->format($this->fileLocation)->get('duration');
    }

    private function prepareFFMpeg()
    {
        $this->findIdealResolutionAndBitrate();
        Upload::whereGuid($this->guid)->update([
                'probe_score' => $this->probe->format($this->fileLocation)->get('probe_score'),
                'original_duration' => $this->probe->format($this->fileLocation)->get('duration'),
                'original_format' => $this->probe->format($this->fileLocation)->get('format_name'),
                'original_codec' => $this->probe->streams()->videos()->first()->get('codec_name'),
                'result_bitrate' => $this->bitrate,
                'result_height' => $this->height,
                'result_width' => $this->width,
                'result_start' => $this->start,
                'result_end' => $this->end,
                'result_audio' => $this->audio
            ]
        );
    }

    /**
     * @return Repository|Application|mixed
     */
    private function calculateDuration(): mixed
    {
        $maximumDuration = config('pr0verter.maxResultLength');
        $requestedDuration = $this->request->get('end') - $this->request->get('start');
        if(!$requestedDuration)
            return $maximumDuration;

        return min($maximumDuration, $requestedDuration);
    }

    /**
     * @return bool
     */
    private function hasAudio(): bool
    {
        return 0 < $this->probe->streams($this->fileLocation)->audios()->count();
    }

    /**
     * @return void
     */
    private function findIdealResolutionAndBitrate()
    {
        $ratio = $this->probe->streams($this->fileLocation)->videos()->first()->getDimensions()->getRatio();
        $width = $this->probe->streams($this->fileLocation)->videos()->first()->getDimensions()->getWidth();
        $height = $this->probe->streams($this->fileLocation)->videos()->first()->getDimensions()->getHeight();
        if(($width * $height / 1048576 > config('pr0verter.maxResultPixels') && $this->keepResolution) || !$this->keepResolution)
        {
            /** First we try to find the closest resolution to the original */
            $closest = null;
            $idx = null;
            foreach (self::availableVideoRatios as $ratio => $data) {
                if($closest === null || abs($height - $closest) > abs($data['height'] - $data)) {
                    $closest = $data['height'];
                    $idx = $ratio;
                }
            }

            /** Now we check if the minBitrate fits into the Video, so that we don't go over the maximum size Limit */
            for(; $idx >= 0; $idx--) {
                $minBitrate = self::availableVideoRatios[$idx]['bitrateMin'];
                if(($minBitrate + $this->audio) * $this->duration > $this->resultSize) {
                    continue;
                } else {
                    break;
                }
            }
            $this->height = self::availableVideoRatios[$idx]['height'];
            $this->width = $this->height * $ratio;
            $this->profile = self::availableVideoRatios[$idx]['profile'];
        } else {
            $this->height = $height;
            $this->width = $width;
            $this->profile = 'baseline';
        }
        $this->bitrate = ($this->resultSize - ($this->audio * $this->duration)) / $this->duration;
    }

    /**
     * @return array
     */
    #[ArrayShape(['format' => "\FFMpeg\Format\Video\X264", 'height' => "int", 'width' => "int", 'start' => "int", 'end' => "int", 'guid' => "string", 'location' => "string"])] public function getFFMpegConfig(): array
    {
        $filters = [
            '-profile:v', $this->profile,
            '-level', '4.0',
            '-preset', 'medium',
            '-fs', $this->resultSize.'k',
            '-movflags', '+faststart'
        ];

        $format = new X264();
        if(!$this->audio) {
            $filters[] = '-an';
        } else {
            $format->setAudioCodec('aac');
            $format->setAudioKiloBitrate($this->audio);
        }

        $format->setAdditionalParameters($filters);
        $format->setPasses(2);
        $format->setKiloBitrate($this->bitrate);

        return array(
            'format' => $format,
            'height' => $this->height,
            'width' => $this->width,
            'start' => $this->start,
            'end' => $this->end,
            'guid' => $this->guid,
            'location' => $this->fileLocation
        );
    }

}
