<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request) {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
        ]);

        $image_name = time() . '.' . $request->image->extension();
        $request->image->move(storage_path('app/public/images'), $image_name);

        $response = [
            'image_name' => $image_name
        ];

        return response($response, Response::HTTP_CREATED);
    }

    public function getImage($filename) {
        $imagePath = storage_path('app/public/images/' . $filename);

        if (file_exists($imagePath)) {
            $image = file_get_contents($imagePath);
            return response($image, 200) -> header('Content-Type', 'image');
        }
        return response()->json(['message' => 'Image not found.'], 404);
    }
}
