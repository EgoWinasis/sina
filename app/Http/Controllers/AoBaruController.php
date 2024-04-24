<?php

namespace App\Http\Controllers;

use App\Models\ModelAoBaru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AoBaruController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->check()) {
                abort(403, 'Unauthorized');
            }

            $user = auth()->user();

            if ($user->role !== 'super') {
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
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode_ao' => 'required',
                'nama_ao' => 'required',
                'droping' => 'required',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }
            
            ModelAoBaru::create([
                'kode_ao' => $request->input('kode_ao'),
                'nama_ao' => $request->input('nama_ao'),
                'plafon' => $request->input('droping'),
                // Add more fields as needed
            ]);

            // Return a response indicating success
            return response()->json(['message' => 'Data saved successfully'], 200);
        } catch (\Exception $e) {
            // Handle exceptions (e.g., database errors)
            return response()->json(['message' => 'Error saving data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }
}
