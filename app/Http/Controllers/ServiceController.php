<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index($id){
        $res = Service::where(['barber_id' => $id])->all();

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
}
