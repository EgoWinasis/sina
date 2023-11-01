<?php

namespace App\Http\Controllers;
use App\Models\Device;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DeviceController extends Controller
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
        $devices = DB::table('devices')
            ->select('id', 'device', 'user', 'pemilik', 'kantor')
            ->orderBy('device')
            ->get();

        return view('device.device_view')->with(compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('device.device_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'device' => [
                'required',
                'string',
                'min:10',
                'max:10',
                'unique:devices,device',
            ],
            'user' => ['required', 'string', 'max:255', 'nullable'],
            'pemilik' => ['required', 'string', 'max:255', 'nullable'],
            'kantor' => ['required', 'string', 'max:255', 'nullable'],
        ]);

        // Convert the values to uppercase
        $validatedData['device'] = strtoupper($validatedData['device']);
        $validatedData['user'] = strtoupper($validatedData['user']);
        $validatedData['pemilik'] = strtoupper($validatedData['pemilik']);
        $validatedData['kantor'] = strtoupper($validatedData['kantor']);
        
        Device::create($validatedData);
        return redirect()
            ->route('device.index')
            ->with('success', 'Berhasil Menambahkan Data Display Device');
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
        //
    }
}
