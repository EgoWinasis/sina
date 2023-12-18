<?php

namespace App\Http\Controllers;

use App\Models\ModelMemo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;

class MemoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $memos = DB::table('memo')
            ->select('id','marketing','nik_debitur','nama_debitur','nik_penjamin','nama_penjamin','alamat_debitur','created_at','updated_at')
            ->orderBy('id')
            ->get();
        return view('memo.memo_view')->with(compact('memos'));
        // return view('memo.memo_view');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('memo.memo_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'marketing' => ['required', 'string', 'max:255'],
            // debitur
            'nama_debitur' => ['required', 'string', 'max:255'],
            'nik_debitur' => ['required', 'max:20'],
            'tempat_lahir_debitur' => ['required', 'string', 'max:255'],
            'tgl_lahir_debitur' => ['required', 'string', 'max:20'],
            'alamat_debitur' => ['required', 'string', 'max:255'],
            'file_debitur' => ['mimes:json','nullable'],
            // penjamin
            'nama_penjamin' => ['string', 'max:255', 'nullable'],
            'nik_penjamin' => ['max:20', 'nullable'],
            'tempat_lahir_penjamin' => ['string', 'max:255','nullable'],
            'tgl_lahir_penjamin' => ['string', 'max:20','nullable'],
            'alamat_penjamin' => ['string', 'max:255','nullable'],
            'file_penjamin' => ['mimes:json','nullable'],
        ]);

        if ($request->hasFile('file_debitur')) {
            $debiturFile = $request->file('file_debitur');
            $debiturName = Str::slug($request->nik_debitur) . '_' . now()->format('YmdHis') . '_' . rand(1000, 9999) . '.txt';
            $debiturPath = $debiturFile->storeAs('slik/debitur', $debiturName);
            $validatedData['file_debitur'] = $debiturFile->get();
        } else {
            $validatedData['file_debitur'] = null;
        }

        if ($request->hasFile('file_penjamin')) {
            $penjaminFile = $request->file('file_penjamin');
            $penjaminName = Str::slug($request->nik_penjamin) . '_' . now()->format('YmdHis') . '_' . rand(1000, 9999) . '.txt';
            $penjaminPath = $penjaminFile->storeAs('slik/penjamin', $penjaminName);
            $validatedData['file_penjamin'] = $penjaminFile->get();
        } else {
            $validatedData['file_penjamin'] = null;
        }

        // Optionally, you may want to delete the uploaded files after processing
        // Uncomment the following lines if you want to delete the files
        // Storage::delete($debiturPath);
        // Storage::delete($penjaminPath);

        // Convert the values to uppercase
        $validatedData['marketing'] = strtoupper($validatedData['marketing']);
        // debitur
        $validatedData['nama_debitur'] = strtoupper($validatedData['nama_debitur']);
        $validatedData['tempat_lahir_debitur'] = strtoupper($validatedData['tempat_lahir_debitur']);
        $validatedData['alamat_debitur'] = strtoupper($validatedData['alamat_debitur']);

        // penjamin
        $validatedData['nama_penjamin'] = strtoupper($validatedData['nama_penjamin']);
        $validatedData['tempat_lahir_penjamin'] = strtoupper($validatedData['tempat_lahir_penjamin']);
        $validatedData['alamat_penjamin'] = strtoupper($validatedData['alamat_penjamin']);






        ModelMemo::create($validatedData);
        return redirect()
            ->route('memo.index')
            ->with('success', 'Berhasil Menambahkan Data Memo SLIK Nasabah');
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

    public function cetak(string $id)
    {
        $memo = DB::table('memo')->find($id);

        
        $pdf = PDF::loadView('memo.memo_cetak_view', ['memo' => $memo]);

        return $pdf->download("Memo ". $memo->nama_debitur .'.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
