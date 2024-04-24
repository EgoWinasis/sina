<?php

namespace App\Http\Controllers;

use App\Models\Models\Sid1Model;
use App\Models\RegisterSlikModel;
use App\Models\RegisterSlikModelClp;
use App\Models\RegisterSlikModelPwt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SlikController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->check()) {
                abort(403, 'Unauthorized');
            }

            $user = auth()->user();

            if ($user->role !== 'super' && $user->role !== 'admin') {
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

        $userKantor = auth()->user()->kantor;

        switch ($userKantor) {
            case 'Kantor Pusat Operasional':
                $tableName = 'register_slik';
                break;
            case 'Kantor Cabang Purwokerto':
                $tableName = 'register_slik_pwt';
                break;
            case 'Kantor Cabang Cilacap':
                $tableName = 'register_slik_clp';
                break;
            default:
                $tableName = '';
        }

        $register = DB::table($tableName)
            ->select('id', 'no_ref', 'tgl_permintaan', 'nik', 'nama', 'tempat_lahir', 'tgl_lahir', 'tujuan_permintaan')
            ->whereMonth('tgl_permintaan', now()->month) // Filter by the current month
            ->orderBy('id', 'desc')
            ->get();

        return view('slik.slik_view')->with(compact('register'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // cek kantor 
        $userKantor = auth()->user()->kantor;

        if ($userKantor == 'Kantor Pusat Operasional') {
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
        } else {
            $dataSidaImport = [];
        }



        switch ($userKantor) {
            case 'Kantor Pusat Operasional':
                $tableName = 'register_slik';
                $kantor = '01';
                break;
            case 'Kantor Cabang Purwokerto':
                $tableName = 'register_slik_pwt';
                $kantor = '02';
                break;
            case 'Kantor Cabang Cilacap':
                $tableName = 'register_slik_clp';
                $kantor = '03';
                break;
            default:
                $tableName = '';
        }
        $lastRow = DB::table($tableName)
            ->orderBy('created_at', 'desc')
            ->first();




        if ($lastRow) {
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
        } else {
            $currentMonthNumber = date('m'); // Get the current month number (01 to 12)
            $romawiNow = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][$currentMonthNumber - 1];
            $no_ref = "1/SLIK/$kantor/$romawiNow/" . date('Y');
        }
        // Retrieve the 'filter_type' cookie value
        $filterType = $_COOKIE['filter'];

        if ($filterType == 'individu') {
            return view('slik.slik_create')->with(compact('no_ref', 'dataSidaImport'));
        } else {
            return view('slik.slik_create_perusahaan')->with(compact('no_ref', 'dataSidaImport'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userKantor = auth()->user()->kantor;
        if ($userKantor == 'Kantor Pusat Operasional') {
            $validatedData = $request->validate([
                'no_ref' => ['required', 'string', 'max:255', Rule::unique('register_slik', 'no_ref')],
                'tgl_permintaan' => ['required', 'string', 'max:255'],
                'nik' => ['required', 'max:20', 'regex:/^[0-9]+$/'],
                'tujuan_permintaan' => ['required', 'string', 'max:3'],
                'nama' => ['required', 'string', 'max:255'],
                'tgl_lahir' => ['required', 'string', 'max:8'],
                'tempat_lahir' => ['required', 'string', 'max:255'],
                'alamat' => ['required', 'string', 'max:255'],
                'petugas' => ['required', 'string', 'max:255'],
                'tipe' => ['required', 'string', 'max:255'],
            ]);
        } elseif ($userKantor == 'Kantor Cabang Purwokerto') {
            $validatedData = $request->validate([
                'no_ref' => ['required', 'string', 'max:255', Rule::unique('register_slik_pwt', 'no_ref')],
                'tgl_permintaan' => ['required', 'string', 'max:255'],
                'nik' => ['required', 'max:20', 'regex:/^[0-9]+$/'],
                'tujuan_permintaan' => ['required', 'string', 'max:3'],
                'nama' => ['required', 'string', 'max:255'],
                'tgl_lahir' => ['required', 'string', 'max:8'],
                'tempat_lahir' => ['required', 'string', 'max:255'],
                'alamat' => ['required', 'string', 'max:255'],
                'petugas' => ['required', 'string', 'max:255'],
                'tipe' => ['required', 'string', 'max:255'],
            ]);
        } else {
            $validatedData = $request->validate([
                'no_ref' => ['required', 'string', 'max:255', Rule::unique('register_slik_clp', 'no_ref')],
                'tgl_permintaan' => ['required', 'string', 'max:255'],
                'nik' => ['required', 'max:20', 'regex:/^[0-9]+$/'],
                'tujuan_permintaan' => ['required', 'string', 'max:3'],
                'nama' => ['required', 'string', 'max:255'],
                'tgl_lahir' => ['required', 'string', 'max:8'],
                'tempat_lahir' => ['required', 'string', 'max:255'],
                'alamat' => ['required', 'string', 'max:255'],
                'petugas' => ['required', 'string', 'max:255'],
                'tipe' => ['required', 'string', 'max:255'],
            ]);
        }



        $validatedData['nama'] = strtoupper($validatedData['nama']);
        $validatedData['tempat_lahir'] = strtoupper($validatedData['tempat_lahir']);
        $validatedData['alamat'] = strtoupper($validatedData['alamat']);
        $validatedData['petugas'] = strtoupper($validatedData['petugas']);

        switch ($userKantor) {
            case 'Kantor Pusat Operasional':
                RegisterSlikModel::create($validatedData);
                break;
            case 'Kantor Cabang Purwokerto':
                RegisterSlikModelPwt::create($validatedData);
                break;
            case 'Kantor Cabang Cilacap':
                RegisterSlikModelClp::create($validatedData);
                break;
            default:
                break;
        }
        return redirect()
            ->route('slik.index')
            ->with('success', 'Berhasil Menambahkan Data Register SLIK Nasabah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userKantor = auth()->user()->kantor;

        switch ($userKantor) {
            case 'Kantor Pusat Operasional':
                $tableName = 'register_slik';
                break;
            case 'Kantor Cabang Purwokerto':
                $tableName = 'register_slik_pwt';
                break;
            case 'Kantor Cabang Cilacap':
                $tableName = 'register_slik_clp';
                break;
            default:
                $tableName = '';
        }
        $data = DB::table($tableName)
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
        $userKantor = auth()->user()->kantor;
        switch ($userKantor) {
            case 'Kantor Pusat Operasional':
                $regis = RegisterSlikModel::find($id);
                break;
            case 'Kantor Cabang Purwokerto':
                $regis = RegisterSlikModelPwt::find($id);
                break;
            case 'Kantor Cabang Cilacap':
                $regis = RegisterSlikModelClp::find($id);
                break;
            default:
                break;
        }
        // dd($regis);
        return view('slik.slik_edit', compact('regis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $userKantor = auth()->user()->kantor;
        $validatedData = $request->validate([
            'no_ref' => ['required', 'string', 'max:255'],
            'tgl_permintaan' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'max:20', 'regex:/^[0-9]+$/'],
            'tujuan_permintaan' => ['required', 'string', 'max:3'],
            'nama' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'string', 'max:8'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'petugas' => ['required', 'string', 'max:255']
        ]);

        $validatedData['nama'] = strtoupper($validatedData['nama']);
        $validatedData['tempat_lahir'] = strtoupper($validatedData['tempat_lahir']);
        $validatedData['alamat'] = strtoupper($validatedData['alamat']);
        $validatedData['petugas'] = strtoupper($validatedData['petugas']);

        switch ($userKantor) {
            case 'Kantor Pusat Operasional':
                RegisterSlikModel::where('id', $id)->update($validatedData);
                break;
            case 'Kantor Cabang Purwokerto':
                RegisterSlikModelPwt::where('id', $id)->update($validatedData);
                break;
            case 'Kantor Cabang Cilacap':
                RegisterSlikModelClp::where('id', $id)->update($validatedData);
                break;
            default:
                break;
        }
        return redirect()
            ->route('slik.index')
            ->with('success', 'Berhasil Mengupdate Data Register SLIK Nasabah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userKantor = auth()->user()->kantor;
        // cek kantor
        if ($userKantor == 'Kantor Pusat Operasional') {
            $register = RegisterSlikModel::find($id);
        } elseif ($userKantor == 'Kantor Cabang Purwokerto') {
            $register = RegisterSlikModelPwt::find($id);
        } else {
            $register = RegisterSlikModelClp::find($id);
        }

        // 
        if (!$register) {
            return redirect()->route('slik.index')->with('error', 'register not found.');
        }

        $register->delete();

        return redirect()->route('slik.index')->with('success', 'register deleted successfully.');
    }
}
