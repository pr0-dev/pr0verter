<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Download
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Download newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Download newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Download query()
 * @method static \Illuminate\Database\Eloquent\Builder|Download whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Download whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Download whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Download extends Model
{
    use HasFactory;
}
