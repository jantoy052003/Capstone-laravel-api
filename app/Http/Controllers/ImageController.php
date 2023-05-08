<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\User;


class ImageController extends Controller
{

    public function upload(Request $request, $userId) {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
        ]);

        $user = User::findOrFail($userId);
        $image_name = $user->id . '_' . time() . '.' . $request->image->extension();
        $request->image->move(storage_path('app/public/images'), $image_name);

        $user->image_filename = $image_name;
        $user->save();

        $response = [
            'image_name' => $image_name
        ];

        return response($response, Response::HTTP_CREATED);
    }

    public function getImage(Request $request, $userId) {
        $user = User::find($userId);
        if (!$user) {
            abort(Response::HTTP_NOT_FOUND, 'User not found');
        }

        if ($user->image_filename) {
            $imagePath = storage_path('app/public/images/' . $user->image_filename);
            if (file_exists($imagePath)) {
                $image = file_get_contents($imagePath);
                return response($image, 200)->header('Content-Type', 'image/jpeg');
            }
        }

        $defaultImagePath = storage_path('app/public/images/default.png');
        $defaultImage = file_get_contents($defaultImagePath);
        
        return response($defaultImage, 200)->header('Content-Type', 'image/jpeg');
    }

}
