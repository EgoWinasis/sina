<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\type;

class PenggunaController extends Controller
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
        // Get the currently authenticated user
        $currentUser = Auth::user();

        // Fetch all users except the currently authenticated user
        $users = User::where('id', '!=', $currentUser->id)->get();
        $dataAo = DB::connection('mysql_nusamba')
            ->table('data_ao')
            ->select('kode_ao','nama')
            ->get();
        // Pass the data to the view
        return view('pengguna.pengguna_view')->with(compact('users','dataAo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'required' => 'Kolom :attribute harus diisi.',
            'max' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
            'min' => 'Kolom :attribute tidak boleh kurang dari :min karakter.',
            'confirmed' => 'Kolom :attribute Kata Sandi tidak sama.',
            'unique' => 'Kolom :attribute email sudah digunakan',
        ]);

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        return redirect()->route('pengguna.index')->with('success', 'Akun Berhasil Dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the user by ID
        $user = User::find($id);

        // Check if the user is found
        if ($user) {
            // Return user data as JSON response
            return response()->json($user);
        } else {
            // User not found, return an error response
            return response()->json(['error' => 'User not found'], 404);
        }
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

        // Find the user by ID using a raw query
        $user = DB::table('users')->find($id);
        $type = $request->input('type');
        $message = 'User disabled successfully';
        // Check if the user is found
        if ($user) {
            try {
                if ($type == 'role') {
                    $selectedRole = $request->input('selectedRole');
                    DB::table('users')->where('id', $id)->update(['role' => $selectedRole]);
                    $message = 'Change Role successfully';
                }elseif ($type == 'ao') {
                    $selectedAo = $request->input('selectedAo');
                    DB::table('users')->where('id', $id)->update(['kode_ao' => $selectedAo]);
                    $message = 'Change Kode AO successfully';
                }else if ($type == 0) {
                    DB::table('users')->where('id', $id)->update(['isActive' => 0]);
                } else if ($type == 1) {
                    DB::table('users')->where('id', $id)->update(['isActive' => 1]);
                    $message = 'User Enabled successfully';
                }
                // Update the isActive column to 0 using a raw query

                // Return a response
                return response()->json(['message' => $message]);
            } catch (\Exception $e) {
                // Log the exception for debugging
                Log::error('Update Error:', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Error updating user'], 500);
            }
        } else {
            // User not found, return an error response
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
