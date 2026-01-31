<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin (can see all units)
        User::updateOrCreate(
            ['email' => 'kusnadijaya@rsudmurjani.id'],
            [
                'name' => 'Kusnadi Jaya',
                'password' => Hash::make('kusnadijaya1'),
                'role' => 'admin',
                'unit' => null, // Super admin has no unit restriction
            ]
        );

        // List of all hospital units with their passwords
        $units = [
            ['unit' => 'UGD', 'email' => 'ugd@rsudmurjani.id', 'password' => 'ugd12345'],
            ['unit' => 'Poliklinik', 'email' => 'poliklinik@rsudmurjani.id', 'password' => 'poliklinik12345'],
            ['unit' => 'PONEK', 'email' => 'ponek@rsudmurjani.id', 'password' => 'ponek12345'],
            ['unit' => 'OK Cyto', 'email' => 'okcyto@rsudmurjani.id', 'password' => 'okcyto12345'],
            ['unit' => 'Laboratorium Patologi Klinik', 'email' => 'labpk@rsudmurjani.id', 'password' => 'labpk12345'],
            ['unit' => 'Laboratorium Patologi Anatomis', 'email' => 'labpa@rsudmurjani.id', 'password' => 'labpa12345'],
            ['unit' => 'Bank Darah', 'email' => 'bankdarah@rsudmurjani.id', 'password' => 'bankdarah12345'],
            ['unit' => 'Radiologi', 'email' => 'radiologi@rsudmurjani.id', 'password' => 'radiologi12345'],
            ['unit' => 'Depo UGD', 'email' => 'depougd@rsudmurjani.id', 'password' => 'depougd12345'],
            ['unit' => 'Depo Rawat Jalan', 'email' => 'deporj@rsudmurjani.id', 'password' => 'deporj12345'],
            ['unit' => 'Dialisis', 'email' => 'dialisis@rsudmurjani.id', 'password' => 'dialisis12345'],
            ['unit' => 'ICU', 'email' => 'icu@rsudmurjani.id', 'password' => 'icu12345'],
            ['unit' => 'OK Elektif', 'email' => 'okelektif@rsudmurjani.id', 'password' => 'okelektif12345'],
            ['unit' => 'SIM-RS', 'email' => 'simrs@rsudmurjani.id', 'password' => 'simrs12345'],
            ['unit' => 'Rawat Inap Cempaka', 'email' => 'ricempaka@rsudmurjani.id', 'password' => 'ricempaka12345'],
            ['unit' => 'Rawat Inap Seruni', 'email' => 'riseruni@rsudmurjani.id', 'password' => 'riseruni12345'],
            ['unit' => 'Rawat Inap Asoka', 'email' => 'riasoka@rsudmurjani.id', 'password' => 'riasoka12345'],
            ['unit' => 'Rawat Inap Perinatologi', 'email' => 'riperina@rsudmurjani.id', 'password' => 'riperina12345'],
            ['unit' => 'VK', 'email' => 'vk@rsudmurjani.id', 'password' => 'vk12345'],
            ['unit' => 'Rawat Inap Bogenvile', 'email' => 'ribogen@rsudmurjani.id', 'password' => 'ribogen12345'],
            ['unit' => 'Rawat Inap Seroja', 'email' => 'riseroja@rsudmurjani.id', 'password' => 'riseroja12345'],
            ['unit' => 'Rawat Inap Tulip', 'email' => 'ritulip@rsudmurjani.id', 'password' => 'ritulip12345'],
            ['unit' => 'Rawat Inap Teratai', 'email' => 'riteratai@rsudmurjani.id', 'password' => 'riteratai12345'],
            ['unit' => 'Pemulasaran Jenazah', 'email' => 'jenazah@rsudmurjani.id', 'password' => 'jenazah12345'],
            ['unit' => 'IPS-RS', 'email' => 'ipsrs@rsudmurjani.id', 'password' => 'ipsrs12345'],
            ['unit' => 'Security', 'email' => 'security@rsudmurjani.id', 'password' => 'security12345'],
            ['unit' => 'Depo Rawat Inap', 'email' => 'depori@rsudmurjani.id', 'password' => 'depori12345'],
            ['unit' => 'Loundry', 'email' => 'laundry@rsudmurjani.id', 'password' => 'laundry12345'],
            ['unit' => 'Dapur Gizi', 'email' => 'gizi@rsudmurjani.id', 'password' => 'gizi12345'],
            ['unit' => 'Gudang Farmasi', 'email' => 'farmasi@rsudmurjani.id', 'password' => 'farmasi12345'],
            ['unit' => 'Anggrek Tewu', 'email' => 'anggrektewu@rsudmurjani.id', 'password' => 'anggrektewu12345'],
            ['unit' => 'Rekam Medik', 'email' => 'rekammedik@rsudmurjani.id', 'password' => 'rekammedik12345'],
            ['unit' => 'Costumer Service', 'email' => 'cs@rsudmurjani.id', 'password' => 'cs12345'],
            ['unit' => 'Sanitasi', 'email' => 'sanitasi@rsudmurjani.id', 'password' => 'sanitasi12345'],
            ['unit' => 'Pengantar Pasien', 'email' => 'pengantar@rsudmurjani.id', 'password' => 'pengantar12345'],
            ['unit' => 'Code Blue', 'email' => 'codeblue@rsudmurjani.id', 'password' => 'codeblue12345'],
            ['unit' => 'Ambulance Rujukan External', 'email' => 'ambulance@rsudmurjani.id', 'password' => 'ambulance12345'],
            ['unit' => 'CSSD', 'email' => 'cssd@rsudmurjani.id', 'password' => 'cssd12345'],
            ['unit' => 'Depo OK Cyto', 'email' => 'depookcyto@rsudmurjani.id', 'password' => 'depookcyto12345'],
            ['unit' => 'Depo IBS', 'email' => 'depoibs@rsudmurjani.id', 'password' => 'depoibs12345'],
        ];

        foreach ($units as $unitData) {
            User::updateOrCreate(
                ['email' => $unitData['email']],
                [
                    'name' => 'Admin ' . $unitData['unit'],
                    'password' => Hash::make($unitData['password']),
                    'role' => 'admin',
                    'unit' => $unitData['unit'],
                ]
            );
        }
    }
}
