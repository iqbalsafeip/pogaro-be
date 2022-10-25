<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use Illuminate\Http\Request;

class MetodePembayaranController extends Controller
{
    public function index($id){
        $res = MetodePembayaran::where(['barber_id' => $id])->get();

        return response()->json([
            'data' => $res
        ]);
    }

    public function store(Request $request){
        $service = new MetodePembayaran();
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
        $service = MetodePembayaran::findOrFail($id);
        $service->delete();

        return response()->json([
            'data' => $service
        ]);
    }
}
