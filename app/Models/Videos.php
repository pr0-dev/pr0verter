<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Videos
 *
 * @property int $id
 * @property string $uploader_ip
 * @property string $origEnding
 * @property int $type
 * @property int $loadProgress
 * @property int $convertProgress
 * @property int $failed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Videos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Videos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Videos query()
 * @method static \Illuminate\Database\Eloquent\Builder|Videos whereConvertProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Videos whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Videos whereFailed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Videos whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Videos whereLoadProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Videos whereOrigEnding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Videos whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Videos whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Videos whereUploaderIp($value)
 * @mixin \Eloquent
 */
class Videos extends Model
{
    use HasFactory;
}
