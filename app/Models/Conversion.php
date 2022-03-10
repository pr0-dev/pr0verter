<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Conversion
 *
 * @property string $guid
 * @property string $typeInfo_type
 * @property int $typeInfo_id
 * @property string $source_disk
 * @property string $result_disk
 * @property string $filename
 * @property int $size
 * @property int $sound
 * @property int $ratio
 * @property int $interpolation
 * @property int $start
 * @property int $end
 * @property string|null $source_format
 * @property string|null $source_codec
 * @property int $source_duration
 * @property int $probe_score
 * @property string|null $probe_error
 * @property string|null $converter_remaining
 * @property string|null $converter_rate
 * @property string|null $converter_error
 * @property int $converter_progress
 * @property int $result_bitrate
 * @property int $result_height
 * @property int $result_width
 * @property int $result_start
 * @property int $result_duration
 * @property int $result_audio
 * @property int $result_size
 * @property string|null $result_profile
 * @property int $failed
 * @property int $downloaded
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|\Eloquent $typeInfo
 * @method static Builder|Conversion newModelQuery()
 * @method static Builder|Conversion newQuery()
 * @method static Builder|Conversion query()
 * @method static Builder|Conversion whereConverterError($value)
 * @method static Builder|Conversion whereConverterProgress($value)
 * @method static Builder|Conversion whereConverterRate($value)
 * @method static Builder|Conversion whereConverterRemaining($value)
 * @method static Builder|Conversion whereCreatedAt($value)
 * @method static Builder|Conversion whereDownloaded($value)
 * @method static Builder|Conversion whereEnd($value)
 * @method static Builder|Conversion whereFailed($value)
 * @method static Builder|Conversion whereFilename($value)
 * @method static Builder|Conversion whereGuid($value)
 * @method static Builder|Conversion whereInterpolation($value)
 * @method static Builder|Conversion whereProbeError($value)
 * @method static Builder|Conversion whereProbeScore($value)
 * @method static Builder|Conversion whereRatio($value)
 * @method static Builder|Conversion whereResultAudio($value)
 * @method static Builder|Conversion whereResultBitrate($value)
 * @method static Builder|Conversion whereResultDisk($value)
 * @method static Builder|Conversion whereResultDuration($value)
 * @method static Builder|Conversion whereResultHeight($value)
 * @method static Builder|Conversion whereResultProfile($value)
 * @method static Builder|Conversion whereResultSize($value)
 * @method static Builder|Conversion whereResultStart($value)
 * @method static Builder|Conversion whereResultWidth($value)
 * @method static Builder|Conversion whereSize($value)
 * @method static Builder|Conversion whereSound($value)
 * @method static Builder|Conversion whereSourceCodec($value)
 * @method static Builder|Conversion whereSourceDisk($value)
 * @method static Builder|Conversion whereSourceDuration($value)
 * @method static Builder|Conversion whereSourceFormat($value)
 * @method static Builder|Conversion whereStart($value)
 * @method static Builder|Conversion whereTypeInfoId($value)
 * @method static Builder|Conversion whereTypeInfoType($value)
 * @method static Builder|Conversion whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Conversion extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $keyType = 'string';
    protected $primaryKey = 'guid';
    protected $casts = [
        'guid' => 'string'
    ];

    /**
     * @param int $typeId
     * @param string $typeType
     * @param string $sourceDisk
     * @param string $ip
     * @param string $resultDisk
     * @return Conversion|Model
     */
    public static function initialize(int $typeId, string $typeType, string $sourceDisk, string $resultDisk, array $requestData): Model|Conversion
    {
        $guid = uniqid();
        while (true) {
            if (self::find($guid)->exists())
                $guid = uniqid();
            else
                break;
        }

        if(config('pr0verter.disabled.inputs.interpolation'))
            $requestData['interpolation'] = false;

        return self::create([array_merge([
            'guid' => $guid,
            'typeInfo_id' => $typeId,
            'typeInfo_type' => $typeType,
            'source_disk' => $sourceDisk,
            'result_disk' => $resultDisk,
            'filename' => $guid],
            $requestData)
        ]);
    }

    /**
     * @return MorphTo
     */
    public function typeInfo(): MorphTo
    {
        return $this->morphTo();
    }

}
