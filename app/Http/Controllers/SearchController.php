<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            "nid" => ["required", "numeric"],
        ]);

        $notRegistered = "Not registered";
        $notScheduled = "Not scheduled";
        $scheduled = "Scheduled";
        $vaccinated = "Vaccinated";

        $user = User::query()
            ->where("nid", $request->nid)
            ->select(["vaccine_schedule"])
            ->first();
        if ($user == null) {
            return back()->with("status", $notRegistered);
        }

        if ($user->vaccine_schedule == null) {
            return back()->with("status", $notScheduled);
        }

        if ($user->vaccine_schedule->gte(now()->startOfDay())) {
            return back()->with("status", $scheduled);
        }

        return back()->with("status", $vaccinated);
    }
}
