<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Youtube
 *
 * @property int $id
 * @property string $url
 * @property string $subtitle
 * @property string|null $progress
 * @property string|null $rate
 * @property string|null $eta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Youtube newModelQuery()
 * @method static Builder|Youtube newQuery()
 * @method static Builder|Youtube query()
 * @method static Builder|Youtube whereCreatedAt($value)
 * @method static Builder|Youtube whereEta($value)
 * @method static Builder|Youtube whereId($value)
 * @method static Builder|Youtube whereProgress($value)
 * @method static Builder|Youtube whereRate($value)
 * @method static Builder|Youtube whereSubtitle($value)
 * @method static Builder|Youtube whereUpdatedAt($value)
 * @method static Builder|Youtube whereUrl($value)
 * @mixin Eloquent
 */
class Youtube extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function initialize(array $data): Model|Youtube
    {
        return self::create($data);
    }
}
