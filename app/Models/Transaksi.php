<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    public function servis(){
        return $this->belongsTo(Service::class, 'servis_id');
    }

    public function barber(){
        return $this->belongsTo(Barber::class, 'barber_id');
    }

    public function pengguna(){
        return $this->belongsTo(Profile::class, 'pengguna_id');
    }
}
