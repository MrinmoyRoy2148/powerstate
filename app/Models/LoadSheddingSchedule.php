<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['zone_id', 'title', 'scheduled_date', 'start_time', 'end_time', 'notes', 'status'])]
class LoadSheddingSchedule extends Model
{
    /**
     * @param  Builder<LoadSheddingSchedule>  $query
     * @return Builder<LoadSheddingSchedule>
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query->whereDate('scheduled_date', '>', today())
                ->orWhere(function (Builder $query): void {
                    $query->whereDate('scheduled_date', today())
                        ->where('end_time', '>', now()->format('H:i:s'));
                });
        });
    }

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Zone, $this>
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return HasMany<Outage, $this>
     */
    public function outages(): HasMany
    {
        return $this->hasMany(Outage::class, 'schedule_id');
    }
}
