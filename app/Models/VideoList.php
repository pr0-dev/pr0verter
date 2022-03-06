<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\VideoList
 *
 * @property string $guid
 * @property string $type
 * @property int $failed
 * @property string $uploaderIP
 * @property int $downloaded
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|VideoList newModelQuery()
 * @method static Builder|VideoList newQuery()
 * @method static Builder|VideoList query()
 * @method static Builder|VideoList whereCreatedAt($value)
 * @method static Builder|VideoList whereDownloaded($value)
 * @method static Builder|VideoList whereFailed($value)
 * @method static Builder|VideoList whereGuid($value)
 * @method static Builder|VideoList whereType($value)
 * @method static Builder|VideoList whereUpdatedAt($value)
 * @method static Builder|VideoList whereUploaderIP($value)
 * @mixin Eloquent
 * @property-read Upload|null $uploads
 * @property string $load_type
 * @method static Builder|VideoList whereLoadType($value)
 */
class VideoList extends Model
{
    use HasFactory;

    protected $primaryKey = 'guid';
    protected $keyType = 'string';
    public $incrementing = 'false';

    protected $guarded = [];

    /**
     * @return MorphTo
     */
    public function type(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'video_type', 'guid');
    }
}
