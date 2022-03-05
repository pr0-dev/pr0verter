<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload query()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereConvertError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereConvertProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereOriginalCodec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereOriginalDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereOriginalFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereProbeScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereResultAudio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereResultBitrate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereResultEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereResultHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereResultStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereResultWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUpdatedAt($value)
 * @mixin \Eloquent
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
