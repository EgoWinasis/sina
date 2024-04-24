<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id(); // Get the authenticated user's ID
        $user = User::find($userId); // Get the user data using the User model
        // dd($user);
        // You can now use $user to access the user's data in your view
        return view('auth.passwords.ubah', ['user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $user = User::find($id);

        $request->validate([
            'old_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('kata sandi lama salah');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ],[
            'required' => 'Kolom :attribute harus diisi.',
            'min' => 'Kolom :attribute tidak boleh kurang dari :min karakter.',
            'confirmed' => 'Kolom :attribute Kata Sandi tidak sama.'
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('password.index')->with('success', 'Kata Sandi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}