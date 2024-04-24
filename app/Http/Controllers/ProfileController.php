<?php

namespace App\Http\Controllers;

use App\Models\ModelJabatan;
use App\Models\ModelKantor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
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
        $user = Auth::user();

        // Check if the user is authenticated
        if ($user) {
            // Retrieve additional user data from the database
            $userData = User::find($user->id);
            // Pass the user data to the view

            return view('profile/profile_view', ['profile' => $userData]);
        } else {
            // Redirect to the login page or perform another action
            return redirect()->route('login'); // Adjust the route name accordingly
        }
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
        $profile = User::find($id);
        $jabatan = ModelJabatan::orderBy('name')->get();
        $kantor = ModelKantor::orderBy('id')->get();
        return view('profile.profile_edit_view')->with(compact('profile', 'jabatan', 'kantor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'kantor' => ['required', 'string', 'max:255'],
            // foto
            'image' => 'image|file|mimes:png,jpg,jpeg',
        ]);


        if ($request->file('image')) {
            if ($user['image'] != 'user_default.png') {
                Storage::delete('public/profiles/' . $user['image']);
            }
            $file = $request->file('image');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $request->file('image')->storeAs('public/profiles/', $fileName);
            $validatedData['image'] = $fileName;
        } else {
            $validatedData['image'] = $user['image'];
        }


        User::where('id', $id)->update($validatedData);
        return redirect()->route('profile.index')
            ->with('success', 'Berhasil Update Profile');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
