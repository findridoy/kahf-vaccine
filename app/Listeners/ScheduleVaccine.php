<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\VaccineCenter;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class ScheduleVaccine implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        try {
            $user = $event->user;
            $vaccineCenter = $user->vaccineCenter;

            if ($this->canNotUseCurrentAvailableDay($vaccineCenter)) {
                $nextWeekDay = $vaccineCenter->setNextAvailableDay();

                $this->setVaccineSchedule($user, $vaccineCenter, $nextWeekDay);

                return;
            }

            $this->setVaccineSchedule(
                $user,
                $vaccineCenter,
                $vaccineCenter->available_day
            );
        } catch (Throwable $e) {
            Log::error(
                "scheduling vaccine date for user: " . $e->getMessage(),
                [$user->id]
            );
        }
    }

    private function setVaccineSchedule(
        User $user,
        VaccineCenter $vaccineCenter,
        Carbon $scheduleDate
    ) {
        $vaccineCenter->available_day_booked++;
        $vaccineCenter->save();

        $user->vaccine_schedule = $scheduleDate;
        $user->save();
    }

    private function canNotUseCurrentAvailableDay(
        VaccineCenter $vaccineCenter
    ): bool {
        return !$vaccineCenter->availableDayIsAfterToday() ||
            !$vaccineCenter->spaceAvailableForSchedule();
    }
}
