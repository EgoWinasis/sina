<?php

namespace App\Http\Controllers;

use App\Models\ModelBanner;
use App\Models\ModelHariBesar;
use Illuminate\Http\Request;
use PDF;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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
    public function uploadImageHari(Request $request, $id)
    {
        try {
            $request->validate([
                'id' => 'required|integer', // Validate the ID field
                'imageDesain' => 'required|image|mimes:jpeg,png,jpg',
            ]);

            $idhari_besar = $request->input('id');

            $hari_besar = ModelHariBesar::findOrFail($idhari_besar);

            // Store the uploaded image
            $file = $request->file('imageDesain');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $request->file('imageDesain')->storeAs('public/hari_besar/', $fileName);

            // Update the hari_besar record with the new image path
            $hari_besar->desain = $fileName;
            $hari_besar->save();

            return response()->json(['message' => 'Image uploaded successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update hari besar: ' . $e->getMessage()], 500);
        }
    }


    public function importExcel()
    {
        return view('hari_besar.hari_besar_import');
    }

    public function storeExcelHari(Request $request)
    {
        try {
            // Example code to save data to the database
            $data = $request->input('data');
            foreach ($data as $row) {
                // Set the 'status' and 'desain' columns
                $row['status'] = 'PENDING';
                $row['desain'] = '-';

                // Process each row of data and store it
                // For example, you can use the ModelHariBesar to create a new record
                ModelHariBesar::create([
                    'hari' => $row['Hari'],
                    'tanggal' => $row['Tanggal'],
                    'status' => $row['status'],
                    'desain' => $row['desain'],
                ]);
            }

            // Return a response indicating success
            return response()->json(['message' => 'Data stored successfully'], 200);
        } catch (\Exception $e) {
            // Return a response indicating failure with the error message
            return response()->json(['message' => 'Failed to store data: ' . $e->getMessage()], 500);
        }
    }

    public function cetakBanner($id)
    {
        // Find the banner by ID
        $banner = ModelBanner::findOrFail($id);

        $pdf = PDF::loadView('banner.banner_cetak_view', ['banner' => $banner]);

        return $pdf->download("BANNER " . $banner->toko . '.pdf');
    }
}
