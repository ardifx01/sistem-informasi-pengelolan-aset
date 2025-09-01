<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Category;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $it = Category::where('name', 'IT')->first();

        Barang::create([
            'name' => 'Laptop Lenovo Thinkpad',
            'serial_number' => 'SN-001-LENOVO',
            'category_id' => $it->id,
            'stock' => 10,
        ]);

        Barang::create([
            'name' => 'Proyektor Epson',
            'serial_number' => 'SN-002-EPSON',
            'category_id' => $it->id,
            'stock' => 5,
        ]);
    }
}
