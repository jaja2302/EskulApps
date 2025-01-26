<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Eskul;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Create Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('admin');

        // Create Pembimbing
        $pembimbings = [];
        for ($i = 1; $i <= 3; $i++) {
            $pembimbing = User::create([
                'name' => "Pembimbing {$i}",
                'email' => "pembimbing{$i}@example.com",
                'password' => Hash::make('password123'),
            ]);
            $pembimbing->assignRole('pembimbing');
            $pembimbings[] = $pembimbing;
        }

        // Create Pelatih
        $pelatihs = [];
        for ($i = 1; $i <= 10; $i++) {
            $pelatih = User::create([
                'name' => "Pelatih {$i}",
                'email' => "pelatih{$i}@example.com",
                'password' => Hash::make('password123'),
            ]);
            $pelatih->assignRole('pelatih');
            $pelatihs[] = $pelatih;
        }

        // Create Eskul
        $eskulNames = [
            'Basket', 'Futsal', 'Pramuka', 'PMR', 'Musik', 
            'Tari', 'Robotik', 'English Club', 'Jurnalistik', 'Seni Rupa'
        ];

        $eskuls = [];
        foreach ($eskulNames as $index => $name) {
            $eskul = Eskul::create([
                'name' => $name,
                'description' => $faker->paragraph(),
                'image' => 'eskul/default.jpg', // Pastikan ada default image
                'pelatih_id' => $pelatihs[$index]->id,
                'pembimbing_id' => $pembimbings[array_rand($pembimbings, 1)]->id,
                'quota' => rand(20, 30),
                'is_active' => true,
                'meeting_location' => $faker->randomElement(['Lapangan', 'Aula', 'Ruang Kelas A1', 'Lab Komputer', 'Studio Musik']),
                'requirements' => $faker->sentences(3, true),
                'category' => $faker->randomElement(['Olahraga', 'Seni', 'Akademik', 'Sosial']),
            ]);
            $eskuls[] = $eskul;
        }

        // Create Siswa
        for ($i = 1; $i <= 110; $i++) {
            $siswa = User::create([
                'name' => $faker->name,
                'email' => "siswa{$i}@example.com",
                'password' => Hash::make('password123'),
            ]);
            $siswa->assignRole('siswa');
        }

        // Create some schedules for each eskul
        foreach ($eskuls as $eskul) {
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
            $randomDay = $faker->randomElement($days);
            
            \DB::table('eskul_schedules')->insert([
                'eskul_id' => $eskul->id,
                'day' => $randomDay,
                'start_time' => '14:00',
                'end_time' => '16:00',
                'location' => $eskul->meeting_location,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create some announcements
        foreach ($eskuls as $eskul) {
            \DB::table('announcements')->insert([
                'eskul_id' => $eskul->id,
                'created_by' => $eskul->pelatih_id,
                'title' => "Pengumuman Kegiatan " . $eskul->name,
                'content' => $faker->paragraph(),
                'is_important' => $faker->boolean(),
                'publish_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 