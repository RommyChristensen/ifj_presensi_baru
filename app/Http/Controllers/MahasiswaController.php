<?php

namespace App\Http\Controllers;

use App\DetailKehadiran;
use App\Exports\MahasiswaExport;
use App\HeaderKehadiran;
use App\Jurusan;
use App\Mahasiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel as Excel;

class MahasiswaController extends Controller
{
    public function absensi($slug)
    {
        $header_kehadiran = HeaderKehadiran::where('slug', $slug)->first();

        if($header_kehadiran->status == 1){
            return view('absensi', ['slug' => $slug, 'header_kehadiran' => $header_kehadiran]);
        }
        // dd($header_kehadiran);
        // dd($jurusan);
        return view('belum_buka');
    }

    public function getJurusan()
    {
        return Jurusan::all();
    }

    function absen(Request $req){
        // dd($req->all());
        if(DetailKehadiran::where('nrp', $req->nrp)->where('id_header_kehadiran', $req->id_header_kehadiran)->exists()){
            return 201;
        }

        if($req->has('nama')){
            $data = array(
                'nrp' => $req->nrp,
                'nama' => $req->nama,
                'ttl' => $req->ttl,
                'id_jurusan' => $req->jurusan,
                'created_at' => new Carbon('now'),
                'updated_at' => new Carbon('now')
            );
            Mahasiswa::create($data);
        }
        $detail_kehadiran = $req->only(['id_header_kehadiran','nrp', 'created_at', 'updated_at']);
        // dd($detail_kehadiran);
        DetailKehadiran::create($detail_kehadiran);

        return 200;
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){
            return redirect('/rmh/servant');
        }else{
            return back();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/rmh/login');
    }

    public function servant()
    {
        return view('dashboard');
    }

    public function checkNrp(Request $request)
    {
        $nrp = $request->nrp;
        if(Mahasiswa::where('nrp', $nrp)->exists()){
            $mhs = Mahasiswa::find($nrp);
            return $mhs;
        }else{
            return false;
        }
    }

    public function list_mhs()
    {
        $mahasiswa = Mahasiswa::all();
        // dd($mahasiswa);

        return view('listMahasiswa', ['mahasiswa' => $mahasiswa]);
    }

    public function delete_mhs($nrp)
    {
        Mahasiswa::find($nrp)->delete();
        return back();
    }

    public function getDataMhs($nrp)
    {
        return Mahasiswa::find($nrp);
    }

    public function editMhs(Request $req)
    {
        $mahasiswa = Mahasiswa::find($req->nrp);
        // dd($req->all());
        $mahasiswa->nama = $req->nama;
        $mahasiswa->id_jurusan = $req->jurusan;
        $mahasiswa->ttl = $req->ttl;
        $mahasiswa->save();
        return back();
    }

    public function getEvents()
    {
        $data_mhs = DB::table('mahasiswa')->select('ttl', 'nama')->get();
        $data_event = DB::table('header_kehadiran')->select('tanggal_absensi', 'judul_absensi')->get();


        return array(
            "data_mhs" => $data_mhs,
            "data_event" => $data_event
        );
        // dd($data_mhs);
    }

    public function admin_absensi()
    {
        $header_kehadiran = HeaderKehadiran::latest()->withCount('detail')->get();
        // dd($header_kehadiran);
        // dd($header_kehadiran);
        return view('admin_absensi', ['header_kehadiran' => $header_kehadiran]);
    }

    public function tambah_absensi(Request $req)
    {
        $req->validate([
            'judul_absensi' => 'required',
            'tanggal_absensi' => 'required|unique:header_kehadiran,tanggal_absensi'
        ]);
        $data = $req->all();

        $count = HeaderKehadiran::where('judul_absensi', $req->judul_absensi)->count();
        $slug = '';
        if($count > 0){
            $slug = Str::slug($req->judul_absensi)."-".$count;
        }else{
            $slug = Str::slug($req->judul_absensi);
        }
        $data['link_absensi'] = 'localhost:8000/absensi/'.$slug;
        $data['slug'] = $slug;

        HeaderKehadiran::create($data);
        return back()->with('code', 200);
    }

    public function lihat_data_absensi(Request $request)
    {
        $filter = $request->filter;
        $kehadiran = null;
        if($filter == "all"){
            $kehadiran = HeaderKehadiran::withCount('detail')->get();
        }else{
            $kehadiran = HeaderKehadiran::where('status', $filter)->withCount('detail')->get();
        }
        return $kehadiran;
    }

    public function buka(HeaderKehadiran $header_kehadiran)
    {
        $header_kehadiran->status = 1;
        $header_kehadiran->save();
        return back();
    }

    public function tutup(HeaderKehadiran $header_kehadiran)
    {
        $header_kehadiran->status = 2;
        $header_kehadiran->save();
        return back();
    }

    public function selesai(HeaderKehadiran $header_kehadiran)
    {
        $header_kehadiran->status = 3;
        $header_kehadiran->save();
        return back();
    }

    public function detail_absensi($id)
    {
        $detail_kehadiran = $this->getDetailKehadiran($id);
        return view('detail_absensi', ['detail_kehadiran' => $detail_kehadiran]);
        // dd($detail_kehadiran);
    }

    public function getDetailKehadiran($id)
    {
        return DetailKehadiran::where('id_header_kehadiran', $id)->with('mahasiswa')->orderBy('created_at', 'desc')->get();
    }

    public function delete_absensi($id)
    {
        HeaderKehadiran::find($id)->delete();
        return back()->with('code', 1000);
    }

    public function laporan()
    {
        return view('laporan');
    }

    public function getLaporanPerUser($date_from, $date_to)
    {
        // dd($date_from, $date_to);
        // $laporan = DB::select(DB::raw("SELECT mhs.nrp, mhs.nama, count(dk.nrp) as jumlah_kehadiran FROM mahasiswa mhs JOIN detail_kehadiran dk ON dk.nrp = mhs.nrp WHERE dk.created_at BETWEEN '$date_from' AND '$date_to' GROUP BY mhs.nrp, mhs.nama"));
        $laporan = DB::table('mahasiswa', 'mhs')
        ->select(DB::raw('mhs.nrp, mhs.nama, count(detail_kehadiran.nrp) as jumlah_kehadiran'))
        ->join('detail_kehadiran', 'detail_kehadiran.nrp', '=', 'mhs.nrp')
        ->whereBetween('detail_kehadiran.created_at', [$date_from, $date_to])
        ->where('mhs.konfirmasi', 1)
        ->groupBy('mhs.nrp', 'mhs.nama')
        ->orderBy('mhs.nrp')
        ->get();
        return $laporan;
    }

    public function convertExcel($date_from, $date_to)
    {
        // $data = DB::table('mahasiswa', 'mhs')
        //     ->select(DB::raw('mhs.nrp, mhs.nama, count(detail_kehadiran.nrp) as jumlah_kehadiran'))
        //     ->join('detail_kehadiran', 'detail_kehadiran.nrp', '=', 'mhs.nrp')
        //     ->whereBetween('detail_kehadiran.created_at', [$date_from, $date_to])
        //     ->groupBy('mhs.nrp', 'mhs.nama')
        //     ->orderBy('mhs.nrp');
        // dd($data);
        return (new MahasiswaExport($date_from, $date_to))->download("laporan_periode_$date_from-$date_to.xlsx");
        // return Excel::download(new MahasiswaExport($date_from, $date_to), "laporan_periode_$date_from-$date_to.xlsx");
    }

    public function delete(Request $req)
    {
        DetailKehadiran::where('nrp', $req->nrp)->delete();
        Mahasiswa::find($req->nrp)->delete();
        return back();
    }

    public function konfirmasi($nrp, $value)
    {
        $mhs = Mahasiswa::find($nrp);
        $mhs->konfirmasi = $value;
        $mhs->save();
        return back();
    }
}
