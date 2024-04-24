<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportSlikController extends Controller
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
            ->whereMonth('tgl_permintaan', now()->subMonth()->month) // Filter by the current month
            ->orderBy('id', 'desc')
            ->get();

        return view('report_slik.report_slik_view')->with(compact('register'));
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
