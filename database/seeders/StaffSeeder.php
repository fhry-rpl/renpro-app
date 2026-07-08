<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            ['name' => 'Dr. Andi Wijaya, S.T., M.T.', 'position' => 'Kepala UPBU Budiarto', 'order' => 1, 'is_active' => true],
            ['name' => 'Ir. Siti Rahmawati, M.M.', 'position' => 'Kepala Seksi Renpro', 'order' => 2, 'is_active' => true],
            ['name' => 'Bambang Susilo, S.E.', 'position' => 'Kepala Seksi Keuangan', 'order' => 3, 'is_active' => true],
            ['name' => 'Dewi Sartika, S.H.', 'position' => 'Kepala Seksi Umum', 'order' => 4, 'is_active' => true],
            ['name' => 'Ahmad Fauzi, S.Si.T.', 'position' => 'Analis Program', 'order' => 5, 'is_active' => true],
            ['name' => 'Rina Marlina, A.Md.', 'position' => 'Staf Administrasi', 'order' => 6, 'is_active' => true],
        ];

        foreach ($staff as $member) {
            Staff::firstOrCreate(['name' => $member['name'], 'position' => $member['position']], $member);
        }
    }
}
