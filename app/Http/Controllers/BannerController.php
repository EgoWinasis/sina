<?php

namespace App\Http\Controllers;

use App\Models\ModelBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->check()) {
                abort(403, 'Unauthorized');
            }

            $user = auth()->user();

            if ($user->role !== 'super' && $user->jabatan !== "Marketing Komunitas") {
                abort(403, 'Unauthorized');
            }


            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = DB::table('banner')
            ->select('*')
            ->orderBy('created_at')
            ->get();

        return view('banner.banner_view')->with(compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('banner.banner_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user = auth()->user()->name;

        $validatedData = $request->validate([
            'kantor' => ['required', 'string', 'max:255'],
            'toko' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:255'],
            'panjang' => ['required', 'string', 'max:11'],
            'lebar' => ['required', 'string', 'max:11'],
            'hp' => ['required', 'string', 'max:16'],
            'deadline' => ['required', 'string', 'max:10'],

            'image' => 'image|file|mimes:png,jpg,jpeg',

        ]);


        // Convert the values to uppercase
        $validatedData['kantor'] = strtoupper($validatedData['kantor']);
        $validatedData['toko'] = strtoupper($validatedData['toko']);
        $validatedData['alamat'] = strtoupper($validatedData['alamat']);
        $validatedData['deskripsi'] = strtoupper($validatedData['deskripsi']);
        // 
        $validatedData['input_by'] = strtoupper($user);
        $validatedData['status'] = strtoupper("pending");
        $validatedData['desain'] = strtoupper("-");

        if ($request->file('image') && $validatedData['image'] != "image-default.png") {
            $file = $request->file('image');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $request->file('image')->storeAs('public/banner/', $fileName);
            $validatedData['image'] = $fileName;
        } else {
            $validatedData['image'] = "image-default.png";
        }


        ModelBanner::create($validatedData);
        return redirect()
            ->route('banner.index')
            ->with('success', 'Berhasil Menambahkan Data Permintaan Banner');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = DB::table('banner')
            ->select('*')
            ->where('id', '=', $id)
            ->get();

        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banner = DB::table('banner')
            ->select('*')
            ->where('id', '=', $id)
            ->get();

        return view('banner.banner_edit_view')->with(compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = ModelBanner::findOrFail($id);

        if ($request->has('status')) {
            $validatedData = $request->validate([
                'status' => ['required', 'string', 'in:Pending,Selesai,Batal']
            ]);
            $validatedData['status'] = strtoupper($validatedData['status']);

            $banner->update(['status' => $validatedData['status']]);

            return response()->json(['message' => 'Status updated successfully'], 200);
        } else {

            $user = auth()->user()->name;

            $validatedData = $request->validate([
                'kantor' => ['required', 'string', 'max:255'],
                'toko' => ['required', 'string', 'max:255'],
                'alamat' => ['required', 'string', 'max:255'],
                'deskripsi' => ['required', 'string', 'max:255'],
                'panjang' => ['required', 'string', 'max:11'],
                'lebar' => ['required', 'string', 'max:11'],
                'hp' => ['required', 'string', 'max:16'],
                'deadline' => ['required', 'string', 'max:10'],

                'image' => 'image|file|mimes:png,jpg,jpeg',

            ]);

            // Convert the values to uppercase
            $validatedData['kantor'] = strtoupper($validatedData['kantor']);
            $validatedData['toko'] = strtoupper($validatedData['toko']);
            $validatedData['alamat'] = strtoupper($validatedData['alamat']);
            $validatedData['deskripsi'] = strtoupper($validatedData['deskripsi']);
            $validatedData['input_by'] = strtoupper($user);


            // $banner = ModelBanner::findOrFail($id); // Assuming $id is the ID of the banner you want to update

            $validatedData['status'] = $banner->status;
            $validatedData['desain'] = $banner->desain;
            // image
            if ($request->file('image')) {
                $file = $request->file('image');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $request->file('image')->storeAs('public/banner/', $fileName);
                $validatedData['image'] = $fileName;
            } else {
                // If no new image is uploaded, keep the existing image
                $validatedData['image'] = $banner->image;
            }

            $banner->update($validatedData);

            return redirect()
                ->route('banner.index')
                ->with('success', 'Berhasil Memperbarui Data Permintaan Banner');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the banner by its ID
        $banner = ModelBanner::find($id);

        // Check if the banner exists
        if (!$banner) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        // Delete the image file
        if ($banner->image) {
            Storage::delete('public/banner/' . $banner->image);
        }
        if ($banner->desain) {
            Storage::delete('public/banner/' . $banner->desain);
        }

        // Remove the 'desain' field from the database
        $banner->desain = null;
        $banner->save();

        // Delete the banner
        $banner->delete();

        // Return a success response
        return response()->json(['message' => 'Banner deleted successfully']);
    }
}
