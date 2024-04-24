<?php

namespace App\Http\Controllers;

use App\Models\ModelMemo;
use App\Models\ModelMemoClp;
use App\Models\ModelMemoPwt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;
use Illuminate\Support\Carbon;

class MemoController extends Controller
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
                $tableName = 'memo';
                break;
            case 'Kantor Cabang Purwokerto':
                $tableName = 'memo_pwt';
                break;
            case 'Kantor Cabang Cilacap':
                $tableName = 'memo_clp';
                break;
            default:
                $tableName = '';
        }

        $memos = DB::table($tableName)
            ->select('id', 'marketing', 'nik_debitur', 'nama_debitur', 'nik_penjamin', 'nama_penjamin', 'alamat_debitur', 'created_at', 'updated_at')
            ->whereMonth('created_at', now()->month)  // Filter by current month
            ->whereYear('created_at', now()->year)    // Filter by current year
            ->orderBy('id', 'desc')
            ->get();
        return view('memo.memo_view')->with(compact('memos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
        $startOfWeek = Carbon::now()->startOfWeek(); // Start of the current week
        $endOfWeek = Carbon::now()->endOfWeek();
        // get data
        $register = DB::table($tableName)
            ->select('id', 'nama', 'nik', 'tgl_lahir', 'tempat_lahir', 'alamat', 'tipe')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('id', 'desc')
            ->get();
        // $register = DB::table($tableName)
        //     ->select('id', 'nama', 'nik', 'tgl_lahir', 'tempat_lahir', 'alamat', 'tipe')
        //     ->orderBy('id', 'desc')
        //     ->get();
        return view('memo.memo_create')->with(compact('register'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_register' => ['required'],
            'marketing' => ['required', 'string', 'max:255'],
            // debitur
            'nama_debitur' => ['required', 'string', 'max:255'],
            'nik_debitur' => ['required', 'max:20'],
            'tempat_lahir_debitur' => ['required', 'string', 'max:255'],
            'tgl_lahir_debitur' => ['required', 'string', 'max:20'],
            'alamat_debitur' => ['required', 'string', 'max:255'],
            'tipe_debitur' => ['required', 'string', 'max:255'],
            'file_debitur' => ['mimes:json', 'nullable'],
            // penjamin
            'nama_penjamin' => ['string', 'max:255', 'nullable'],
            'nik_penjamin' => ['max:20', 'nullable'],
            'tempat_lahir_penjamin' => ['string', 'max:255', 'nullable'],
            'tgl_lahir_penjamin' => ['string', 'max:20', 'nullable'],
            'alamat_penjamin' => ['string', 'max:255', 'nullable'],
            'tipe_penjamin' => ['string', 'max:255', 'nullable'],
            'file_penjamin' => ['mimes:json,txt', 'nullable'],
        ]);


        $userKantor = auth()->user()->kantor;

        switch ($userKantor) {
            case 'Kantor Pusat Operasional':
                $kantor = 'pst';
                break;
            case 'Kantor Cabang Purwokerto':
                $kantor = 'pwt';
                break;
            case 'Kantor Cabang Cilacap':
                $kantor = 'clp';
                break;
            default:
                $kantor = '';
        }


        if ($request->hasFile('file_debitur')) {
            $debiturFile = $request->file('file_debitur');
            $debiturName = $kantor . '_' . Str::slug($request->nik_debitur) . '_' . now()->format('YmdHis') . '_' . rand(1000, 9999) . '.txt';
            $debiturPath = $debiturFile->storeAs('slik/debitur', $debiturName);
            $validatedData['file_debitur'] = mb_convert_encoding($debiturFile->get(), 'UTF-8');
        } else {
            $validatedData['file_debitur'] = null;
        }

        if ($request->hasFile('file_penjamin')) {
            $penjaminFile = $request->file('file_penjamin');
            $penjaminName = $kantor . '_' . Str::slug($request->nik_penjamin) . '_' . now()->format('YmdHis') . '_' . rand(1000, 9999) . '.txt';
            $penjaminPath = $penjaminFile->storeAs('slik/penjamin', $penjaminName);
            $validatedData['file_penjamin'] =  mb_convert_encoding($penjaminFile->get(), 'UTF-8');
        } else {
            $validatedData['file_penjamin'] = null;
        }

        $validatedData['marketing'] = strtoupper($validatedData['marketing']);
        // debitur
        $validatedData['nama_debitur'] = strtoupper($validatedData['nama_debitur']);
        $validatedData['tempat_lahir_debitur'] = strtoupper($validatedData['tempat_lahir_debitur']);
        $validatedData['alamat_debitur'] = strtoupper($validatedData['alamat_debitur']);

        // penjamin
        $validatedData['nama_penjamin'] = strtoupper($validatedData['nama_penjamin']);
        $validatedData['tempat_lahir_penjamin'] = strtoupper($validatedData['tempat_lahir_penjamin']);
        $validatedData['alamat_penjamin'] = strtoupper($validatedData['alamat_penjamin']);




        switch ($userKantor) {
            case 'Kantor Pusat Operasional':
                ModelMemo::create($validatedData);
                break;
            case 'Kantor Cabang Purwokerto':
                ModelMemoPwt::create($validatedData);
                break;
            case 'Kantor Cabang Cilacap':
                ModelMemoClp::create($validatedData);
                break;
            default:
                break;
        }

        return redirect()
            ->route('memo.index')
            ->with('success', 'Berhasil Menambahkan Data Memo SLIK Nasabah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userKantor = auth()->user()->kantor;

        switch ($userKantor) {
            case 'Kantor Pusat Operasional':
                $tableName = 'memo';
                break;
            case 'Kantor Cabang Purwokerto':
                $tableName = 'memo_pwt';
                break;
            case 'Kantor Cabang Cilacap':
                $tableName = 'memo_clp';
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
                $memo = ModelMemo::find($id);
                break;
            case 'Kantor Cabang Purwokerto':
                $memo = ModelMemoPwt::find($id);
                break;
            case 'Kantor Cabang Cilacap':
                $memo = ModelMemoClp::find($id);
                break;
            default:
                break;
        }

        return view('memo.memo_edit', compact('memo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // Fetch the existing data by ID

        $validatedData = $request->validate([
            'marketing' => ['required', 'string', 'max:255'],
            // debitur
            'nama_debitur' => ['required', 'string', 'max:255'],
            'nik_debitur' => ['required', 'max:20'],
            'tempat_lahir_debitur' => ['required', 'string', 'max:255'],
            'tgl_lahir_debitur' => ['required', 'string', 'max:20'],
            'alamat_debitur' => ['required', 'string', 'max:255'],
            'file_debitur' => ['mimes:json', 'nullable'],
            // penjamin
            'nama_penjamin' => ['string', 'max:255', 'nullable'],
            'nik_penjamin' => ['max:20', 'nullable'],
            'tempat_lahir_penjamin' => ['string', 'max:255', 'nullable'],
            'tgl_lahir_penjamin' => ['string', 'max:20', 'nullable'],
            'alamat_penjamin' => ['string', 'max:255', 'nullable'],
            'file_penjamin' => ['mimes:json,txt', 'nullable'],
        ]);


        $userKantor = auth()->user()->kantor;
        switch ($userKantor) {
            case 'Kantor Pusat Operasional':
                $memo = ModelMemo::findOrFail($id);
                $kantor = 'pst';
                break;
            case 'Kantor Cabang Purwokerto':
                $memo = ModelMemoPwt::findOrFail($id);
                $kantor = 'pwt';
                break;
            case 'Kantor Cabang Cilacap':
                $memo = ModelMemoClp::findOrFail($id);
                $kantor = 'clp';
                break;
            default:
                break;
        }

        if ($request->hasFile('file_debitur')) {
            $debiturFile = $request->file('file_debitur');
            $debiturName = $kantor . '_' . Str::slug($request->nik_debitur) . '_' . now()->format('YmdHis') . '_' . rand(1000, 9999) . '.txt';
            $debiturPath = $debiturFile->storeAs('slik/debitur', $debiturName);
            $validatedData['file_debitur'] = mb_convert_encoding($debiturFile->get(), 'UTF-8');
        } else {
            $validatedData['file_debitur'] = $memo->file_debitur;
        }

        if ($request->hasFile('file_penjamin')) {
            $penjaminFile = $request->file('file_penjamin');
            $penjaminName = $kantor . '_' . Str::slug($request->nik_penjamin) . '_' . now()->format('YmdHis') . '_' . rand(1000, 9999) . '.txt';
            $penjaminPath = $penjaminFile->storeAs('slik/penjamin', $penjaminName);
            $validatedData['file_penjamin'] = mb_convert_encoding($penjaminFile->get(), 'UTF-8');
        } else {
            $validatedData['file_penjamin'] = $memo->file_penjamin;
        }

        $validatedData['marketing'] = strtoupper($validatedData['marketing']);
        // debitur
        $validatedData['nama_debitur'] = strtoupper($validatedData['nama_debitur']);
        $validatedData['tempat_lahir_debitur'] = strtoupper($validatedData['tempat_lahir_debitur']);
        $validatedData['alamat_debitur'] = strtoupper($validatedData['alamat_debitur']);

        // penjamin
        $validatedData['nama_penjamin'] = strtoupper($validatedData['nama_penjamin']);
        $validatedData['tempat_lahir_penjamin'] = strtoupper($validatedData['tempat_lahir_penjamin']);
        $validatedData['alamat_penjamin'] = strtoupper($validatedData['alamat_penjamin']);






        // Update the memo
        $memo->update($validatedData);

        return redirect()
            ->route('memo.index')
            ->with('success', 'Berhasil Mengupdate Data Memo SLIK Nasabah');
    }

    public function cetak(string $id)
    {
        $userKantor = auth()->user()->kantor;

        switch ($userKantor) {
            case 'Kantor Pusat Operasional':
                $tableName = 'memo';
                break;
            case 'Kantor Cabang Purwokerto':
                $tableName = 'memo_pwt';
                break;
            case 'Kantor Cabang Cilacap':
                $tableName = 'memo_clp';
                break;
            default:
                $tableName = '';
        }
        // get data
        $memo = DB::table($tableName)->find($id);
        // $file = $memo->file_debitur;
        // dd(json_decode($file));

        $pdf = PDF::loadView('memo.memo_cetak_view', ['memo' => $memo]);

        return $pdf->download("Memo " . $memo->nama_debitur . '.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
