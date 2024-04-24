<?php

namespace App\Http\Controllers;

use App\Models\ModelPerangkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerangkatController extends Controller
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
        $perangkats = DB::table('perangkats')
            ->select('id', 'perangkat', 'kantor')
            ->orderBy('id')
            ->get();

        return view('perangkat.perangkat_view')->with(compact('perangkats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('perangkat.perangkat_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'perangkat' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'kantor' => ['required', 'string', 'max:255'],
        ]);

        // Convert the values to uppercase
        $validatedData['perangkat'] = strtoupper($validatedData['perangkat']);

        ModelPerangkat::create($validatedData);
        return redirect()
            ->route('perangkat.index')
            ->with('success', 'Berhasil Menambahkan Data Akun Perangkat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = DB::table('perangkats')
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
        //
    }
}
