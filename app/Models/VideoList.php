<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
 */
class VideoList extends Model
{
    /**
     * @return HasOne
     */
    public function uploads(): HasOne
    {
        return $this->hasOne(Upload::class, 'guid');
    }

    use HasFactory;
}
