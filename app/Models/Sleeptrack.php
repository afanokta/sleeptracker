<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sleeptrack extends Model
{
    protected $fillable = [
        'sleep_report_id',
        'input_type',
        'input_time',
        'location',
        'long',
        'lat',
        'photo',
    ];

    protected $casts = [
        'input_time' => 'datetime',
        'long' => 'double',
        'lat' => 'double',
    ];

    public function sleepReport(): BelongsTo
    {
        return $this->belongsTo(SleepReport::class);
    }
}
