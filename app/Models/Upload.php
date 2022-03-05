<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Upload
 *
 * @property string $guid
 * @property string|null $mime_type
 * @property int $convert_progress
 * @property string|null $convert_error
 * @property int $probe_score
 * @property int $original_duration
 * @property int|null $original_format
 * @property int|null $original_codec
 * @property int $result_bitrate
 * @property int $result_height
 * @property int $result_width
 * @property int $result_start
 * @property int $result_end
 * @property int $result_audio
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Upload newModelQuery()
 * @method static Builder|Upload newQuery()
 * @method static Builder|Upload query()
 * @method static Builder|Upload whereConvertError($value)
 * @method static Builder|Upload whereConvertProgress($value)
 * @method static Builder|Upload whereCreatedAt($value)
 * @method static Builder|Upload whereGuid($value)
 * @method static Builder|Upload whereMimeType($value)
 * @method static Builder|Upload whereOriginalCodec($value)
 * @method static Builder|Upload whereOriginalDuration($value)
 * @method static Builder|Upload whereOriginalFormat($value)
 * @method static Builder|Upload whereProbeScore($value)
 * @method static Builder|Upload whereResultAudio($value)
 * @method static Builder|Upload whereResultBitrate($value)
 * @method static Builder|Upload whereResultEnd($value)
 * @method static Builder|Upload whereResultHeight($value)
 * @method static Builder|Upload whereResultStart($value)
 * @method static Builder|Upload whereResultWidth($value)
 * @method static Builder|Upload whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string|null $convert_remaining
 * @property string|null $convert_rate
 * @property string|null $probe_error
 * @property-read VideoList $list
 * @method static Builder|Upload whereConvertRate($value)
 * @method static Builder|Upload whereConvertRemaining($value)
 * @method static Builder|Upload whereProbeError($value)
 */
class Upload extends Model
{
    /**
     * @return BelongsTo
     */
    public function list(): BelongsTo
    {
        return $this->belongsTo(VideoList::class, 'guid', 'guid');
    }
    use HasFactory;
}
