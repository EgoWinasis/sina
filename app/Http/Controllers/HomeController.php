<?php

namespace App\Http\Controllers;

use App\Models\ModelAoBaru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    protected function getCountImportSpkByKodeAoAndDate($kodeAoValue, $currentMonth, $currentYear)
    {
        return DB::connection('mysql_nusamba')
            ->table('spk2')
            ->join('data_ao', 'data_ao.kode_ao', '=', 'spk2.jaminan')
            ->where('data_ao.kode_ao', $kodeAoValue)
            ->whereMonth('spk2.tgl', $currentMonth)
            ->whereYear('spk2.tgl', $currentYear)
            ->whereDate('spk2.tgl', '<=', now()->toDateString())
            ->count();
    }

    protected function getCountImportSpkByKodeAoAndToday($kodeAoValue)
    {
        return DB::connection('mysql_nusamba')
            ->table('spk2')
            ->join('data_ao', 'data_ao.kode_ao', '=', 'spk2.jaminan')
            ->where('data_ao.kode_ao', $kodeAoValue)
            ->whereDate('spk2.tgl', now()->toDateString())
            ->count();
    }
    protected function getCountNasabahBaruAndLama($kodeAoValue, $type, $currentMonth, $currentYear)
    {
        return DB::connection('mysql_nusamba')
            ->table('spk2')
            ->join('data_ao', 'data_ao.kode_ao', '=', 'spk2.jaminan')
            ->where('data_ao.kode_ao', $kodeAoValue)
            ->where('spk2.jns_p', $type) // Add condition for jns_p
            ->whereMonth('spk2.tgl', $currentMonth)
            ->whereYear('spk2.tgl', $currentYear)
            ->whereDate('spk2.tgl', '<=', now()->toDateString())
            ->count();
    }
    protected function getSumPlafonByKodeAoAndDate($kodeAoValue, $currentMonth, $currentYear)
    {
        return DB::connection('mysql_nusamba')
            ->table('spk2')
            ->join('data_ao', 'data_ao.kode_ao', '=', 'spk2.jaminan')
            ->where('data_ao.kode_ao', $kodeAoValue)
            ->whereYear('spk2.tgl', $currentYear)
            ->whereMonth('spk2.tgl', $currentMonth)
            ->whereDate('spk2.tgl', '<=', now()->format('Y-m-d'))
            ->sum('plafon');
    }
    protected function getSumPlafonTomorrowByKodeAo($kodeAoValue)
    {
        return DB::connection('mysql_nusamba')
            ->table('spk2')
            ->join('data_ao', 'data_ao.kode_ao', '=', 'spk2.jaminan')
            ->where('data_ao.kode_ao', $kodeAoValue)
            ->whereDate('spk2.tgl', '>', now()->format('Y-m-d'))
            ->sum('plafon');
    }
    protected function getSumPlafonByKodeAoAndToday($kodeAoValue)
    {
        return DB::connection('mysql_nusamba')
            ->table('spk2')
            ->join('data_ao', 'data_ao.kode_ao', '=', 'spk2.jaminan')
            ->where('data_ao.kode_ao', $kodeAoValue)
            ->whereDate('spk2.tgl', now()->toDateString())
            ->sum('plafon');
    }
    // ao baru
    protected function getCountImportSpkByKodeAoAndTodayAoBaru($kodeAoValue)
    {
        return DB::table('ao_baru')
            ->where('kode_ao', $kodeAoValue)
            ->whereDate('created_at', now()->toDateString())
            ->count();
    }

    protected function getSumPlafonByKodeAoAndTodayAoBaru($kodeAoValue)
    {
        return DB::table('ao_baru')
            ->where('kode_ao', $kodeAoValue)
            ->whereDate('created_at', now()->toDateString())
            ->sum('plafon');
    }

    protected function getSumPlafonByKodeAoAndDateAoBaru($kodeAoValue, $currentMonth, $currentYear)
    {
        return DB::table('ao_baru')
            ->where('kode_ao', $kodeAoValue)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->whereDate('created_at', '<=', now()->format('Y-m-d'))
            ->sum('plafon');
    }

    protected function getCountImportSpkByKodeAoAndDateAoBaru($kodeAoValue, $currentMonth, $currentYear)
    {
        return DB::table('ao_baru')
            ->where('kode_ao', $kodeAoValue)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->isActive == 0) {
            Auth::logout();
            return redirect()->route('login')->with('inactive', 'Your account is inactive. Please contact Admin support.');
        } else {
            // get date
            $currentMonth = now()->format('m');
            $currentYear = now()->format('Y');

            // get data kode ao
            $dataAo = DB::connection('mysql_nusamba')
                ->table('data_ao')
                ->select('kode_ao', 'nama')
                ->orderBy('kode_ao')
                ->get();

            $dataAoKodeAoValues = $dataAo->pluck('kode_ao')->toArray();
            // $dataAoKodeAoValues[] = 'ML';
            $results = [];
            $droping = [];
            // store data to array
            foreach ($dataAoKodeAoValues as $kodeAoValue) {
                // Get count for the current month and year
                $countImportSpk = $this->getCountImportSpkByKodeAoAndDate($kodeAoValue, $currentMonth, $currentYear);
                // Get count for today
                $countImportSpkToday = $this->getCountImportSpkByKodeAoAndToday($kodeAoValue);
                // Get sum of plafon for the current month and year
                $sumPlafon = $this->getSumPlafonByKodeAoAndDate($kodeAoValue, $currentMonth, $currentYear);
                // Get sum of plafon for today
                $sumPlafonToday = $this->getSumPlafonByKodeAoAndToday($kodeAoValue);
                // Get sum of plafon for today
                $sumPlafonPending = $this->getSumPlafonTomorrowByKodeAo($kodeAoValue);
                // Assuming $dataAo is a collection with 'nama_ao' field
                if ($kodeAoValue == 'ML') {
                    $namaAo = "Kredit Karyawan";
                } else {
                    $namaAo = $dataAo->where('kode_ao', $kodeAoValue)->first()->nama;
                }
                

                // nsabah baru
                $nsbhBaru = $this->getCountNasabahBaruAndLama($kodeAoValue, 'B', $currentMonth, $currentYear);
                $nsbhLama = $this->getCountNasabahBaruAndLama($kodeAoValue, 'L', $currentMonth, $currentYear);
                $nsbhLama += $this->getCountNasabahBaruAndLama($kodeAoValue, 'D', $currentMonth, $currentYear);

                // Store the results for each kode_ao value
                if (Auth::user()->kode_ao == $kodeAoValue) {
                    $droping = [
                        'count_spk' => $countImportSpk,
                        'sum_plafon' => $sumPlafon,
                        'sum_plafon_today' => $sumPlafonToday,
                    ];
                }
                $results[$kodeAoValue] = [
                    'kode_ao' => $kodeAoValue,
                    'nama_ao' => $namaAo,
                    'count_spk' => $countImportSpk,
                    'count_spk_today' => $countImportSpkToday,
                    'sum_plafon' => $sumPlafon,
                    'sum_plafon_today' => $sumPlafonToday,
                    'sum_plafon_pending' => $sumPlafonPending,
                    'nsbhBaru' => $nsbhBaru,
                    'nsbhLama' => $nsbhLama,
                ];
            }

            $dataAoBaru = DB::connection('mysql_nusamba')
                ->table('data_ao')
                ->select('kode_ao', 'nama')
                ->orderBy('kode_ao')
                ->get();

            $dataKodeAoBaru = ModelAoBaru::groupBy('kode_ao')->pluck('kode_ao');
            $resultsAobaru = [];

            foreach ($dataKodeAoBaru as $kodeAoBaruValue) {
                // Get count for the current month and year
                $countNsbhAoBaru = $this->getCountImportSpkByKodeAoAndDateAoBaru($kodeAoBaruValue, $currentMonth, $currentYear);
                // Get count for today
                $countNsbhAoBaruToday = $this->getCountImportSpkByKodeAoAndTodayAoBaru($kodeAoBaruValue);
                // Get sum of plafon for the current month and year
                $sumPlafonAoBaru = $this->getSumPlafonByKodeAoAndDateAoBaru($kodeAoBaruValue, $currentMonth, $currentYear);
                // Get sum of plafon for today
                $sumPlafonAoBaruToday = $this->getSumPlafonByKodeAoAndTodayAoBaru($kodeAoBaruValue);
                // Get sum of plafon for today

                $resultsAobaru[$kodeAoBaruValue] = [
                    'kode_ao' => $kodeAoBaruValue,
                    'count_spk' => $countNsbhAoBaru,
                    'count_spk_today' => $countNsbhAoBaruToday,
                    'sum_plafon' => $sumPlafonAoBaru,
                    'sum_plafon_today' => $sumPlafonAoBaruToday,
                ];
            }

            DB::connection('mysql_nusamba')->disconnect();


            return view('dashboard')->with(compact('results', 'resultsAobaru', 'droping'));
        }
    }
}
