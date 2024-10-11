<?php

use App\Jobs\SendScheduleReminderNotification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Log::info("start sending schedule reminder notification");

    User::query()
        ->where("vaccine_schedule", now()->addDay()->format("Y-m-d"))
        ->where("vaccine_notify", 0)
        ->select([
            "id",
            "name",
            "email",
            "vaccine_center_id",
            "vaccine_schedule",
        ])
        ->with("vaccineCenter:name")
        ->chunk(200, function (Collection $users) {
            foreach ($users as $user) {
                SendScheduleReminderNotification::dispatch($user);
            }
        });

    Log::info("all items for schedule reminder notification sent to queue");
})->dailyAt(config("app.vaccine_reminder_sent_hour"));
