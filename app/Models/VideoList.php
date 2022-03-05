<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\VideoList
 *
 * @property string $guid
 * @property string $type
 * @property int $failed
 * @property string $uploaderIP
 * @property int $downloaded
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|VideoList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoList query()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoList whereDownloaded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoList whereFailed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoList whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoList whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoList whereUploaderIP($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Upload|null $uploads
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
