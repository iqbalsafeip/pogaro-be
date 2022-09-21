<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProdiSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Schema::disableForeignKeyConstraints();
        DB::table('prodis')->truncate();
        Schema::enableForeignKeyConstraints();

        $prodis = [
            ['name' => 'Teknik Industri (S1)', 'slug' => 'tekin1'],
            ['name' => 'Teknik Sipil (S1)', 'slug' => 'tepil1'],
            ['name' => 'Teknik Informatika (S1)', 'slug' => 'tenif1'],
            ['name' => 'Arsitektur (S1)', 'slug' => 'arstek1'],
            ['name' => 'Sistem Informasi (S1)', 'slug' => 'sisof1']
        ];

        collect($prodis)->each(function ($prodi) {
            Prodi::create($prodi);
        });
    }
}
