<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Eskul;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

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
        // Create Pembina
        $pembinas = [];
        $namaPembimbing = [
            'Soeherman, S.Pd',
            'Indah Maisyarah Daulay, S.Pd',
            'Drs. Parlaungan Hasibuan',
            'Rommel Lumbangaol, S. Pd',
            'Muh. Nur Prabowo',
        ];
        foreach ($namaPembimbing as $index => $nama) {
            $pembina = User::create([
                'name' => $nama,
                'email' => "pembina" . ($index + 1) . "@example.com",
                'password' => Hash::make('password123'),
            ]);
            $pembina->assignRole('pembina');
            $pembinas[] = $pembina;
        }

        // Create Pelatih
        $pelatihs = [];
        $namaPelatih = [
            'Muhammad Ichsan Pranata, S.H',
            'M. Leo Hamzah Hutabarat',
            'Haniful Sundana, S. Sos',
            'Ramadhan Pratama',
            'Irfandi'
        ];

        // Create accounts for specific named coaches
        foreach ($namaPelatih as $index => $nama) {
            $pelatih = User::create([
                'name' => $nama,
                'email' => "pelatih" . ($index + 1) . "@example.com",
                'password' => Hash::make('password123'),
            ]);
            $pelatih->assignRole('pelatih');
            $pelatihs[] = $pelatih;
        }

        // Create Pimpinan
        $pimpinan = User::create([
            'name' => 'Dr. H. Ahmad Suryadi, M.Pd',
            'email' => 'pimpinan@example.com',
            'password' => Hash::make('password123'),
        ]);
        $pimpinan->assignRole('pimpinan');

        // Create Eskul
        $eskulData = [
            [
                'name' => 'Paskibra',
                'description' => 'Paskibra adalah ekstrakurikuler yang melatih disiplin, kepemimpinan, dan kekompakan melalui baris-berbaris serta pengibaran bendera.',
                'pelatih' => 'Muhammad Ichsan Pranata, S.H',
                'pembina' => 'Soeherman, S.Pd'
            ],
            [
                'name' => 'Palang Merah Remaja',
                'description' => 'PMR (Palang Merah Remaja) adalah ekstrakurikuler yang mengajarkan keterampilan pertolongan pertama, kepedulian sosial, dan kesehatan.',
                'pelatih' => 'M. Leo Hamzah Hutabarat',
                'pembina' => 'Indah Maisyarah Daulay, S.Pd'
            ],
            [
                'name' => 'Rohis',
                'description' => 'Rohis (Rohani Islam) adalah ekstrakurikuler yang membina keimanan dan akhlak siswa melalui kajian keislaman, tilawah, serta kegiatan sosial.',
                'pelatih' => 'Haniful Sundana, S. Sos',
                'pembina' => 'Drs. Parlaungan Hasibuan'
            ],
            [
                'name' => 'Futsal',
                'description' => 'Futsal adalah ekstrakurikuler yang berfokus pada pengembangan keterampilan bermain, kerjasama tim, dan sportivitas melalui latihan dan pertandingan futsal.',
                'pelatih' => 'Ramadhan Pratama',
                'pembina' => 'Rommel Lumbangaol, S. Pd'
            ],
            [
                'name' => 'Pencak Silat',
                'description' => 'Pencak Silat adalah ekstrakurikuler yang mengajarkan seni bela diri tradisional, mengembangkan keterampilan fisik, mental, serta rasa hormat kepada sesama.',
                'pelatih' => 'Irfandi',
                'pembina' => 'Muh. Nur Prabowo'
            ],
        ];

        $eskuls = [];
        foreach ($eskulData as $data) {
            // Find the pelatih and pembina IDs
            $pelatihId = null;
            $pembinaId = null;

            foreach ($pelatihs as $pelatih) {
                if ($pelatih->name === $data['pelatih']) {
                    $pelatihId = $pelatih->id;
                    break;
                }
            }

            foreach ($pembinas as $pembina) {
                if ($pembina->name === $data['pembina']) {
                    $pembinaId = $pembina->id;
                    break;
                }
            }

            $eskul = Eskul::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'image' => 'eskul/default.jpg', // Pastikan ada default image
                'pelatih_id' => $pelatihId,
                'pembina_id' => $pembinaId,
                'quota' => rand(20, 30),
                'is_active' => true,
                'meeting_location' => $faker->randomElement(['Lapangan', 'Aula', 'Ruang Kelas A1', 'Lab Komputer', 'Studio Musik']),
                'requirements' => $faker->sentences(3, true),
                'category' => $faker->randomElement(['Olahraga', 'Seni', 'Akademik', 'Sosial']),
            ]);
            $eskuls[] = $eskul;
        }



        // Create Siswa
        $filePathSiswa = storage_path('app/Daftarmurid.csv');
        if (!file_exists($filePathSiswa)) {
            echo "File CSV siswa tidak ditemukan: $filePathSiswa\n";
            return;
        }

        $csvSiswa = Reader::createFromPath($filePathSiswa, 'r');
        $csvSiswa->setDelimiter(','); // Changed from semicolon to comma
        $csvSiswa->setHeaderOffset(0);

        $batchSize = 1000;
        $dataBatch = [];
        $i = 1;

        foreach ($csvSiswa as $record) {
            $student = User::create([
                'name' => $record['nama'],
                'email' => "siswa{$i}@example.com",
                'password' => Hash::make('password123'),
            ]);
            $student->assignRole('siswa');
            $i++;
        }
    }
}
