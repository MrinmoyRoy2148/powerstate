<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'zone_id',
    'schedule_id',
    'reason_id',
    'title',
    'outage_type',
    'description',
    'started_at',
    'expected_restoration_at',
    'restored_at',
    'status',
])]
class Outage extends Model
{
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'expected_restoration_at' => 'datetime',
            'restored_at' => 'datetime',
        ];
    }

    protected function durationMinutes(): Attribute
    {
        return Attribute::get(function (): ?int {
            if (
                $this->started_at === null
                || $this->restored_at === null
                || $this->restored_at->lessThan($this->started_at)
            ) {
                return null;
            }

            return intdiv($this->restored_at->getTimestamp() - $this->started_at->getTimestamp(), 60);
        });
    }

    /**
     * @return BelongsTo<Zone, $this>
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * @return BelongsTo<LoadSheddingSchedule, $this>
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(LoadSheddingSchedule::class, 'schedule_id');
    }

    /**
     * @return BelongsTo<OutageReason, $this>
     */
    public function reason(): BelongsTo
    {
        return $this->belongsTo(OutageReason::class, 'reason_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
