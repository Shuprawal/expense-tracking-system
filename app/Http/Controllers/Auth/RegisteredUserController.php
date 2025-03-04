<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use MongoDB\BSON\Regex;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {

        if(User::where('username',$request->username)->exists()){
            $originalName = $request->username;
            $count = 1;
            $existingName = User::where('username','like',$originalName.'%')->pluck('username')->toArray();

            while ((in_array($originalName.$count,$existingName))){
                $count++;
            }
            $newName = $originalName.$count;

            return back()->withErrors(['username'=>'username already taken try '.$newName]);
        }


        $user = User::create([
            'first_name'=> $request->first_name,
            'last_name'=> $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => 'user',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('new-categories');
    }
}
