<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cabang;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Cabang::create([
            'name' => 'Cabang Jakarta',
            'address' => 'Jl. Sudirman No. 123, Jakarta',
            'contact' => '021-123456',
        ]);

        Cabang::create([
            'name' => 'Cabang Surabaya',
            'address' => 'Jl. Pahlawan No. 45, Surabaya',
            'contact' => '031-654321',
        ]);
    }
}
