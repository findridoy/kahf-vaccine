<?php

use Carbon\Carbon;

function get_next_weekday(Carbon $date = null): Carbon
{
    $dateTime = $date ?? Carbon::today();

    $dateTime->addDay();

    // default weekday starts at monday but as our weekday start at sunday we need to check 5 and 6
    while ($dateTime->format("N") == 5 || $dateTime->format("N") == 6) {
        $dateTime->addDay();
    }

    return $dateTime;
}
