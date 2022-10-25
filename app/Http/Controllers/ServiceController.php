<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Transaksi;
use App\Models\MetodePembayaran;
use App\Models\BuktiPembayaran;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index($id){
        $res = Service::where(['barber_id' => $id])->get();

        return response()->json([
            'data' => $res
        ]);
    }

    public function store(Request $request){
        $service = new Service();
        $data = $request->all();
        foreach($data as $d => $f){
            $service->$d = $f;
        }
        $service->save();

        return response()->json([
            'data' => $service
        ]);
    }

    public function hapus($id){
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json([
            'data' => $service
        ]);
    }

    public function transaksi(Request $request){
        $service = new Transaksi();
        $data = $request->all();

       
        foreach($data as $d => $f){
            $service->$d = $f;
        }
        $service->save();

        return response()->json([
            'data' => $service
        ]);
    }

    public function transaksid($id){
        $data = Transaksi::findOrFail($id);
        $data['servis'] = $data->servis;
        $data['barber'] = $data->barber;
        $data['pengguna'] = $data->pengguna;
        $temp = MetodePembayaran::where(['barber_id' => $data['barber']->id])->get();
        $data['metode_pembayaran'] = $temp;
        $data['bukti_pembayaran'] = BuktiPembayaran::where(['transaksi_id' => $data->id])->first();
        return response()->json($data);
    }

    public function uploadBukti(Request $request){
        $bukti = new BuktiPembayaran();
        $bukti->barber_id = $request->barber_id;
        $bukti->metode_id = $request->metode_id;
        $bukti->pengguna_id = $request->pengguna_id;
        $bukti->transaksi_id = $request->transaksi_id;
        $bukti->status = "0";
        $imageName = time().'.'. $request->dokumen->getClientOriginalExtension();
        $request->dokumen->move(public_path('bukti'), $imageName);
        $bukti->dokumen = $imageName;

        if($bukti->save()){
            $transaksi = Transaksi::findOrFail($request->transaksi_id);
            $transaksi->status = 1;
            $transaksi->save();
        }
        return response()->json($bukti);

        // if($creds['foto']){
            //     
            // }
    }

    public function verifikasi($id, Request $request){
        $data = Transaksi::findOrFail($id);
        $data->status = $request->status;
        $data->save();
        return response()->json($data);
    }

    public function riwayat($id){
        $data = Transaksi::where(['pengguna_id'=> $id])->with('servis', 'barber')->get();
        return response()->json($data);
    }

    public function riwayatBarber($id){
        $data = Transaksi::where(['barber_id'=> $id])->with('servis', 'barber', 'pengguna')->get();
        return response()->json($data);
    }
}
