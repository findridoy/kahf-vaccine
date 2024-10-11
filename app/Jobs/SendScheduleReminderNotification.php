<?php

namespace App\Jobs;

use App\Mail\VaccineSchedule;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendScheduleReminderNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->user)->send(new VaccineSchedule($this->user));
            Log::info("vaccine schedule mail sent successfully", [
                $this->user->id,
            ]);

            // If you want to send more notification to another channel. ex - SMS
            // then we can sent from here
        } catch (Throwable $e) {
            Log::error("sending vaccine schedule mail: " . $e->getMessage(), [
                $this->user->id,
            ]);
        }
    }
}
