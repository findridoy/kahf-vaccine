<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $vaccineCenters = VaccineCenter::query()
            ->select(["id", "name"])
            ->limit(500)
            ->get();
        return view("auth.register", ["vaccine_centers" => $vaccineCenters]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): View
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "vaccine_center_id" => ["required", "numeric"],
            "nid" => ["required", "numeric", "unique:" . User::class],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                "unique:" . User::class,
            ],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "vaccine_center_id" => $request->vaccine_center_id,
            "nid" => $request->nid,
            // 'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Auth::login($user);

        // return redirect(route("dashboard", absolute: false));
        return view("auth.registration-complete");
    }
}
