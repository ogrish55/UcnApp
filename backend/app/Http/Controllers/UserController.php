<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\models\User;

class UserController extends Controller
{
    public function UpdateUser(Request $request, User $user) {
        
        $user = Auth::user();

        $data = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phoneNumber' => 'required|string|max:255'
            ]);

        $user->update($data);

        return response($user, 200);
    }
}
