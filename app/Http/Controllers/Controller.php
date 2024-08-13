<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index() {
        $users = $this->getUsers();
        return Inertia::render('Auth/Register', [
            'users' => $users
        ]);
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $users = $this->getUsers();

        broadcast(new UserRegistered($users));
        return redirect()->back()->with('success', 'User created successfully.');
    }

    private function getUsers() {
        $users = User::
            selectRaw('DATE_FORMAT(created_at, "%m-%d-%Y GMT") as x, COUNT(*) as y')
            ->groupBy('x')
            ->orderBy('x')
            ->get()
            ->toArray();
        return $users;
    }

}
