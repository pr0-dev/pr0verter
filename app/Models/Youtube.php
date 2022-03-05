<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Youtube
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Youtube newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Youtube newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Youtube query()
 * @method static \Illuminate\Database\Eloquent\Builder|Youtube whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Youtube whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Youtube whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Youtube extends Model
{
    use HasFactory;
}
