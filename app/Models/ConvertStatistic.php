<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ConvertStatistic
 *
 * @property int $id
 * @property string $type
 * @property int $succeeded
 * @property string $convertTime
 * @property string $time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ConvertStatistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConvertStatistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConvertStatistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConvertStatistic whereConvertTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConvertStatistic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConvertStatistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConvertStatistic whereSucceeded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConvertStatistic whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConvertStatistic whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConvertStatistic whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConvertStatistic extends Model
{
    use HasFactory;
}
