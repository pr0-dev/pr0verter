<?php

namespace App\Models;

use App\Http\Requests\ConvertFileRequest;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;


/**
 * App\Models\Upload
 *
 * @property int $id
 * @property string $mime_type
 * @property string $extension
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Conversion|null $conversion
 * @method static Builder|Upload newModelQuery()
 * @method static Builder|Upload newQuery()
 * @method static Builder|Upload query()
 * @method static Builder|Upload whereCreatedAt($value)
 * @method static Builder|Upload whereExtension($value)
 * @method static Builder|Upload whereId($value)
 * @method static Builder|Upload whereMimeType($value)
 * @method static Builder|Upload whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Upload extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @param ConvertFileRequest $request
     * @return Model|Upload
     */
    public static function initialize(ConvertFileRequest $request): Model|Upload
    {
        return self::create([
            'mime_type' => $request->file('video')->getClientMimeType(),
            'extension' => $request->file('video')->getClientOriginalExtension()
        ]);
    }

    /**
     * @return MorphOne
     */
    public function conversion(): MorphOne
    {
        return $this->morphOne(Conversion::class, 'typeInfo');
    }
}
