<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Download
 *
 * @property int $id
 * @property string $url
 * @property string|null $progress
 * @property string|null $rate
 * @property string|null $eta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Download newModelQuery()
 * @method static Builder|Download newQuery()
 * @method static Builder|Download query()
 * @method static Builder|Download whereCreatedAt($value)
 * @method static Builder|Download whereEta($value)
 * @method static Builder|Download whereId($value)
 * @method static Builder|Download whereProgress($value)
 * @method static Builder|Download whereRate($value)
 * @method static Builder|Download whereUpdatedAt($value)
 * @method static Builder|Download whereUrl($value)
 * @mixin Eloquent
 */
class Download extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function initialize(string $url): Model|Download
    {
        return self::create([
            'url' => $url,
        ]);
    }
}
