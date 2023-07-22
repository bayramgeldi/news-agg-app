<?php

namespace App\Models;

use App\Services\NewsSource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSourceSetting extends Model
{
    protected $fillable = [
        'user_id',
        'source',
    ];

    protected $appends = [
        'source',
    ];


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSourceAttribute($value): ?array
    {
        $sources=NewsSource::all();
        foreach ($sources as $source) {
            if ($source['name'] == $this->attributes['source']) {
                return ['name'=>$source['name'],'title'=>$source['title'], 'description'=>$source['description']];
            }
        }

        return null;
    }
}
