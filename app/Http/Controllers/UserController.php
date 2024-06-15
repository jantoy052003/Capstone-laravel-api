<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateProfilePicture(Request $request)
    {
        $fields = $request->validate([
            'profile_picture' => 'nullable|string',
        ]);

        $user = User::find(auth()->user()->id);
        $user->update($request->all());

        $response = [
            'user' => $user,
        ];

        return response($response, 200);
    }
}
