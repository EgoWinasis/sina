<?php

namespace App\Http\Controllers;

use App\Models\Models\Sid1Model;
use App\Models\RegisterSlikModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SlikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $register = DB::table('register_slik')
            ->select('id', 'no_ref', 'tgl_permintaan', 'nik', 'nama', 'tujuan_permintaan')
            ->orderBy('id', 'desc')
            ->get();
        return view('slik.slik_view')->with(compact('register'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // get data from sida
        $currentYear = Carbon::now()->year;
        $importRegisterSida = DB::connection('mysql_nusamba')->select('SELECT dir FROM sid2 WHERE status != "DOWNLOAD" AND YEAR(tgl) = ?', [$currentYear]);

        $importRegisterSidaDirs = array_column($importRegisterSida, 'dir');


        $importRegisterSidaDirs = array_map(function ($dir) {
            return str_replace('.rar', '', $dir);
        }, $importRegisterSidaDirs);
        
        if (!empty($importRegisterSidaDirs)) {
            $dataSidaImport = Sid1Model::whereYear('tgl', $currentYear)
                ->whereIn('nmfile', $importRegisterSidaDirs)
                ->get();
        } else {
            $dataSidaImport = [];
        }
        

        $lastRow = DB::table('register_slik')
            ->orderBy('created_at', 'desc')
            ->first();

        $no_ref = $lastRow->no_ref;
        list($no, $slik, $tipe, $romawi, $year) = explode('/', $no_ref);
        $currentMonthNumber = date('m'); // Get the current month number (01 to 12)
        $romawiNow = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][$currentMonthNumber - 1];
        if ($romawi == $romawiNow) {
            $no = (int) $no;
            $no++;
        } else {
            $no = 1;
            $romawi = $romawiNow;
        }

        if ($year != date('Y')) {
            $year = date('Y');
        }
        $no_ref = "$no/$slik/$tipe/$romawi/$year";

        return view('slik.slik_create')->with(compact('no_ref', 'dataSidaImport'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'no_ref' => ['required', 'string', 'max:255', Rule::unique('register_slik', 'no_ref')],
            'tgl_permintaan' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'max:20'],
            'tujuan_permintaan' => ['required', 'string', 'max:3'],
            'nama' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'string', 'max:20'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'petugas' => ['required', 'string', 'max:255'],
        ]);

        $validatedData['nama'] = strtoupper($validatedData['nama']);
        $validatedData['tempat_lahir'] = strtoupper($validatedData['tempat_lahir']);
        $validatedData['alamat'] = strtoupper($validatedData['alamat']);
        $validatedData['petugas'] = strtoupper($validatedData['petugas']);

        RegisterSlikModel::create($validatedData);
        return redirect()
            ->route('slik.index')
            ->with('success', 'Berhasil Menambahkan Data Register SLIK Nasabah');
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
