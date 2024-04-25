<?php

namespace App\Http\Controllers;

use App\Models\ModelHariBesar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HariBesarController extends Controller
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
        $hariBesar = DB::table('hari_besar')
            ->select('*')
            ->orderBy('created_at')
            ->get();

        return view('hari_besar.hari_besar_view')->with(compact('hariBesar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hari_besar.hari_besar_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'hari' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'string', 'max:50'],
        ]);


        // Convert the values to uppercase
        $validatedData['hari'] = strtoupper($validatedData['hari']);
        $validatedData['status'] = strtoupper("Pending");
        $validatedData['desain'] = "-";


        ModelHariBesar::create($validatedData);
        return redirect()
            ->route('hari-besar.index')
            ->with('success', 'Berhasil Menambahkan Data Hari Besar');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = DB::table('hari_besar')
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
        $hariBesar = DB::table('hari_besar')
            ->select('*')
            ->where('id', '=', $id)
            ->get();

        return view('hari_besar.hari_besar_edit_view')->with(compact('hariBesar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $hari = ModelHariBesar::findOrFail($id);

        if ($request->has('status')) {
            $validatedData = $request->validate([
                'status' => ['required', 'string', 'in:Pending,Selesai,Batal']
            ]);
            $validatedData['status'] = strtoupper($validatedData['status']);

            $hari->update(['status' => $validatedData['status']]);

            return response()->json(['message' => 'Status updated successfully'], 200);
        } else {

            $validatedData = $request->validate([
                'hari' => ['required', 'string', 'max:255'],
                'tanggal' => ['required', 'string', 'max:50'],
            ]);

            // Convert the values to uppercase
            $validatedData['hari'] = strtoupper($validatedData['hari']);

            $validatedData['status'] = $hari->status;
            $validatedData['desain'] = $hari->desain;

            $hari->update($validatedData);

            return redirect()
                ->route('hari-besar.index')
                ->with('success', 'Berhasil Memperbarui Data Hari Besar');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         // Find the banner by its ID
         $hari_besar = ModelHariBesar::find($id);

         // Check if the hari_besar exists
         if (!$hari_besar) {
             return response()->json(['message' => 'Hari besar not found'], 404);
         }
 
         
         if ($hari_besar->desain) {
             Storage::delete('public/hari_besar/' . $hari_besar->desain);
         }
 
         // Remove the 'desain' field from the database
         $hari_besar->desain = null;
         $hari_besar->save();
 
         // Delete the hari_besar
         $hari_besar->delete();
 
         // Return a success response
         return response()->json(['message' => 'Hari besar deleted successfully']);
    }
}
