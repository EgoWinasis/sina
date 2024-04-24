<?php

namespace App\Http\Controllers;

use App\Models\ModelIpAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IpAddressController extends Controller
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
        $devices = DB::table('ip_address')
            ->select('id', 'device', 'ip_address', 'kantor')
            ->orderBy('device')
            ->get();

        return view('ipaddress.ipaddress_view')->with(compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ipaddress.ipaddress_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'device' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'kantor' => ['required', 'string', 'max:255'],
        ]);

        // Convert the values to uppercase
        $validatedData['device'] = strtoupper($validatedData['device']);

        ModelIpAddress::create($validatedData);
        return redirect()
            ->route('ipaddress.index')
            ->with('success', 'Berhasil Menambahkan Data IP Address Perangkat');
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
        $device = DB::table('ip_address')
            ->select('*')
            ->where('id', '=', $id)
            ->get();

        return view('ipaddress.ipaddress_edit')->with(compact('device'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'device' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'kantor' => ['required', 'string', 'max:255'],
        ]);

        // Convert the values to uppercase
        $validatedData['device'] = strtoupper($validatedData['device']);

        $modelIpAddress = ModelIpAddress::find($id);

        if (!$modelIpAddress) {
            return redirect()
                ->route('ipaddress.index')
                ->with('error', 'Data IP Address Perangkat tidak ditemukan');
        }

        // Update the record with the validated data
        $modelIpAddress->update($validatedData);

        return redirect()
            ->route('ipaddress.index')
            ->with('success', 'Berhasil Memperbarui Data IP Address Perangkat');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the record by ID
        $modelIpAddress = ModelIpAddress::find($id);

        if (!$modelIpAddress) {
            return redirect()
                ->route('ipaddress.index')
                ->with('error', 'Data IP Address Perangkat tidak ditemukan');
        }

        // Delete the record
        $modelIpAddress->delete();

        return redirect()
            ->route('ipaddress.index')
            ->with('success', 'Berhasil Menghapus Data IP Address Perangkat');
    }
}
