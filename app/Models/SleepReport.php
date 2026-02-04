<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class SleepReport extends Model
{
    protected $fillable = [
        'driver_id',
        'status',
        'completed',
        'date',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'date' => 'date',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function sleeptracks(): HasMany
    {
        return $this->hasMany(Sleeptrack::class);
    }

    public function getSleepDurationHoursAttribute(): float
    {
        /** @var \Illuminate\Support\Collection<int, \App\Models\Sleeptrack> $tracks */
        $tracks = $this->sleeptracks->sortBy('input_time')->values();

        $sleepDurationHours = 0.0;

        for ($i = 0; $i < $tracks->count(); $i++) {
            $track = $tracks[$i];

            if ($track->input_type === 'sleep') {
                $next = $tracks->get($i + 1);

                if ($next && $next->input_type === 'wake') {
                    $sleepDurationHours += $track->input_time->diffInMinutes($next->input_time) / 60;
                }
            }
        }

        return $sleepDurationHours;
    }

    public function getSleepCategoryAttribute(): string
    {
        $hours = $this->sleep_duration_hours;

        if ($hours < 7) {
            return 'Kurang';
        }

        if ($hours <= 9) {
            return 'Cukup';
        }

        return 'Lebih dari cukup';
    }
}
