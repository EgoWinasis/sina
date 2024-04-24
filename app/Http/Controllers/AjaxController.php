<?php

namespace App\Http\Controllers;

use App\Models\ModelBanner;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function uploadImage(Request $request, $id)
    {
        try {
            $request->validate([
                'id' => 'required|integer', // Validate the ID field
                'imageDesain' => 'required|image|mimes:jpeg,png,jpg',
            ]);

            $idBanner = $request->input('id');

            $banner = ModelBanner::findOrFail($idBanner);

            // Store the uploaded image
            $file = $request->file('imageDesain');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $request->file('imageDesain')->storeAs('public/banner/', $fileName);

            // Update the banner record with the new image path
            $banner->desain = $fileName;
            $banner->save();

            return response()->json(['message' => 'Image uploaded successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update banner: ' . $e->getMessage()], 500);
        }
    }
}
