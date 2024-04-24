<?php

namespace App\Http\Controllers;

use App\Models\ModelSosmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SosmedController extends Controller
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
        $sosial_media = DB::table('sosial_media')
            ->select('id', 'platform', 'kantor')
            ->orderBy('id')
            ->get();

        return view('sosmed.sosmed_view')->with(compact('sosial_media'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sosmed.sosmed_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'platform' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'kantor' => ['required', 'string', 'max:255'],
        ]);

        // Convert the values to uppercase
        $validatedData['platform'] = strtoupper($validatedData['platform']);

        ModelSosmed::create($validatedData);
        return redirect()
            ->route('sosmed.index')
            ->with('success', 'Berhasil Menambahkan Data Akun');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = DB::table('sosial_media')
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
        $sosmed = DB::table('sosial_media')
            ->select('*')
            ->where('id', '=', $id)
            ->get();

        return view('sosmed.sosmed_edit')->with(compact('sosmed'));
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
        //
    }
}
