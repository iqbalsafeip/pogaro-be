<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        Schema::enableForeignKeyConstraints();

        $roles = [
            ['name' => 'Administrator', 'slug' => 'admin'],
            ['name' => 'CDC', 'slug' => 'cdc'],
            ['name' => 'Program Studi', 'slug' => 'prodi'],
            ['name' => 'Ketua Program Studi', 'slug' => 'kprodi'],
            ['name' => 'Perpustakaan', 'slug' => 'perpus'],
            ['name' => 'Keuangan', 'slug' => 'keuangan'],
            ['name' => 'Mahasiswa', 'slug' => 'mhs'],
        ];

        collect($roles)->each(function ($role) {
            Role::create($role);
        });
    }
}
