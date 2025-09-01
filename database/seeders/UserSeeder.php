<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $jakarta = Cabang::where('name', 'Cabang Jakarta')->first();
        $surabaya = Cabang::where('name', 'Cabang Surabaya')->first();

        // GA (pusat)
        User::create([
            'name' => 'Admin GA',
            'email' => 'ga@example.com',
            'username' => 'ga_admin',
            'password' => Hash::make('password'),
            'nip' => 'GA001',
            'role' => 'GA',
            'cabang_id' => null,
        ]);

        // PIC Jakarta
        User::create([
            'name' => 'PIC Jakarta',
            'email' => 'pic.jkt@example.com',
            'username' => 'pic_jakarta',
            'password' => Hash::make('password'),
            'nip' => 'PIC001',
            'role' => 'PIC',
            'cabang_id' => $jakarta->id,
        ]);

        // PIC Surabaya
        User::create([
            'name' => 'PIC Surabaya',
            'email' => 'pic.sby@example.com',
            'username' => 'pic_surabaya',
            'password' => Hash::make('password'),
            'nip' => 'PIC002',
            'role' => 'PIC',
            'cabang_id' => $surabaya->id,
        ]);
    }
}
