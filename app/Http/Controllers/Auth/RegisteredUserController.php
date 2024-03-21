<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|mobile_number|lowercase|max:255|unique:'.User::class,
            //'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'mobile.required' => 'شماره تلفن وارد نشده است.',
            'mobile.mobile_number' => 'شماره تلفن وارد شده معتبر نیست.',
            'mobile.unique' => 'این شماره تلفن قبلا ثبت شده است.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
        ]);

        $role = Role::where('name', Roles::MEMBER)->first();

        // Assign the role ID to the user
        $user->role_id = $role->id;
        $user->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard')->with('mobile', $user->mobile);
    }
}
