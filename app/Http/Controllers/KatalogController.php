<?php

namespace App\Http\Controllers;

use App\Models\Katalog;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function store(Request $request){
        $bukti = new Katalog();
        $bukti->barber_id = $request->barber_id;
        $bukti->nama_foto = $request->nama_foto;
        $imageName = time().'.'. $request->dokumen->getClientOriginalExtension();
        $request->dokumen->move(public_path('katalog'), $imageName);
        $bukti->file_katalog = $imageName;
       $bukti->save();
        return response()->json($bukti);

        // if($creds['foto']){
            //     
            // }
    }

    public function hapus($id){
        $service = Katalog::findOrFail($id);
        $service->delete();

        return response()->json([
            'data' => $service
        ]);
    }

    public function index($id){
        $res = Katalog::where(['barber_id' => $id])->get();

        return response()->json([
            'data' => $res
        ]);
    }
}
