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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Download newModelQuery()
 * @method static Builder|Download newQuery()
 * @method static Builder|Download query()
 * @method static Builder|Download whereCreatedAt($value)
 * @method static Builder|Download whereId($value)
 * @method static Builder|Download whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Download extends Model
{
    use HasFactory;
}
