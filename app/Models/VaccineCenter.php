<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccineCenter extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "available_day" => "datetime:Y-m-d",
        ];
    }

    public function setNextAvailableDay(): Carbon
    {
        $baseDate = $this->availableDayIsAfterToday()
            ? $this->available_day
            : now();

        $nextWeekDay = get_next_weekday($baseDate);
        $this->available_day = $nextWeekDay;
        $this->available_day_booked = 0;
        $this->save();

        return $nextWeekDay;
    }

    public function spaceAvailableForSchedule(): bool
    {
        return $this->daily_limit > $this->available_day_booked;
    }

    public function availableDayIsAfterToday(): bool
    {
        return $this->available_day->startOfDay()->gt(now()->startOfDay());
    }
}
