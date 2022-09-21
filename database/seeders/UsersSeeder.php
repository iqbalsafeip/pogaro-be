<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $user = User::create([
            'email'=>'admin@gmail.com',
            'password'=>Hash::make('123123'),
            'name'=>'Iqbal Safei',
        ]);
        $user->roles()->attach(Role::where('slug', 'admin')->first());
        $user = User::create([
            'email'=>'informatika@gmail.com',
            'password'=>Hash::make('123123'),
            'name'=>'informatika',
        ]);
        $user->roles()->attach(Role::where('slug', 'prodi')->first());
        $user->prodi()->attach(Prodi::where('slug', 'tenif1')->first());

        $mahasiswa = Mahasiswa::all();

        foreach($mahasiswa as $mhs){
            $user = User::create([
                'email'=> $mhs->nimhs . '@itg.ac.id',
                'password'=>Hash::make('itg@garut'),
                'name'=> $mhs->nimhs,
            ]);
            $user->roles()->attach(Role::where('slug', 'mhs')->first());
            $user->prodi()->attach(Prodi::where('name', $mhs->nmpst)->first());
        }
    }
}
