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
        $device = DB::table('devices')
            ->select('*')
            ->where('id', '=', $id)
            ->get();

        return view('device.device_edit_view')->with(compact('device'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'device' => [
                'required',
                'string',
                'min:10',
                'max:10',
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

        $device = Device::find($id);
        $device->update($validatedData);
        return redirect()
            ->route('device.index')
            ->with('success', 'Berhasil Update Data Display Device');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
