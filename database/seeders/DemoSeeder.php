<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Attachment;
use App\Models\Planner;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds for Demo Mode.
     * Hanya bisa dijalankan jika DEMO_MODE=true.
     */
    public function run(): void
    {
        if (! config('app.demo_mode')) {
            $this->command->error('PERINGATAN: DemoSeeder hanya diizinkan berjalan saat DEMO_MODE=true.');
            return;
        }

        $this->command->info('Memulai seeding data demo realistis (Konteks Indonesia)...');

        $defaultPassword = Hash::make('DemoPassword123!');

        // 1. BUAT AKUN DEMO UTAMA & PENGGUNA TIM (IDEMPOTENT)
        $admin = User::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Admin Demo (TaskFlow)',
                'role' => 'admin',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
            ]
        );

        $demoUser = User::updateOrCreate(
            ['email' => 'user@demo.com'],
            [
                'name' => 'Dimas Wicaksono (Demo User)',
                'role' => 'user',
                'password' => $defaultPassword,
                'email_verified_at' => now(),
            ]
        );

        $teamUsers = [
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@example.com', 'role' => 'user'],
            ['name' => 'Siti Rahmawati', 'email' => 'siti.rahmawati@example.com', 'role' => 'user'],
            ['name' => 'Rian Pratama', 'email' => 'rian.pratama@example.com', 'role' => 'user'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@example.com', 'role' => 'user'],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@example.com', 'role' => 'user'],
            ['name' => 'Anisa Wijaya', 'email' => 'anisa.wijaya@example.com', 'role' => 'user'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko.prasetyo@example.com', 'role' => 'user'],
            ['name' => 'Maya Indrawati', 'email' => 'maya.indrawati@example.com', 'role' => 'user'],
        ];

        $createdTeam = [];
        foreach ($teamUsers as $u) {
            $createdTeam[] = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'role' => $u['role'],
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                ]
            );
        }

        // 2. SIAPKAN PLACEHOLDER FILE LAMPIRAN DI STORAGE PUBLIC
        $storageDir = storage_path('app/public/attachments');
        if (! File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        $dummyFiles = [
            'sop_security_taskflow_v2.pdf' => '%PDF-1.4 TaskFlow Demo Placeholder PDF - SOP Keamanan & IDOR Protection.',
            'proposal_pengadaan_server_q4.pdf' => '%PDF-1.4 TaskFlow Demo Placeholder PDF - Proposal Pengadaan Infrastruktur Server.',
            'rekap_laporan_pajak_pph21.pdf' => '%PDF-1.4 TaskFlow Demo Placeholder PDF - Rekapitulasi Pajak PPh 21.',
            'arsitektur_cloud_v3.docx' => 'PK Dokumen Arsitektur Cloud TaskFlow (Demo Placeholder File DOCX)',
            'notulensi_rapat_direksi.docx' => 'PK Notulensi Rapat Koordinasi Kuartal III (Demo Placeholder File DOCX)',
        ];

        foreach ($dummyFiles as $fname => $content) {
            $filePath = $storageDir . '/' . $fname;
            if (! File::exists($filePath)) {
                File::put($filePath, $content);
            }
        }

        // 3. SEED DATA PLANNERS / TUGAS (REALISTIS INDONESIA)
        $tasksData = [
            // Tugas untuk Demo User
            [
                'user_id' => $demoUser->id,
                'title' => 'Finalisasi Proposal Pengadaan Server Bare-Metal Q4',
                'description' => 'Menyusun rincian spesifikasi teknis CPU Xeon, RAM 64GB ECC, dan storage NVMe bersama vendor lokal Jakarta.',
                'deadline' => now()->addDays(5)->toDateString(),
                'priority' => 'high',
                'status' => 'pending',
                'attachment' => [
                    'filename' => 'Proposal_Pengadaan_Server_Q4.pdf',
                    'path' => 'attachments/proposal_pengadaan_server_q4.pdf',
                    'size' => 1048576,
                    'mime_type' => 'application/pdf',
                ],
            ],
            [
                'user_id' => $demoUser->id,
                'title' => 'Pembaruan Dokumen SOP Keamanan & Incident Response',
                'description' => 'Evaluasi kepatuhan kontrol akses server Ubuntu 22.04 dan integrasi audit log berkala sesuai ISO 27001.',
                'deadline' => now()->subDays(2)->toDateString(),
                'priority' => 'medium',
                'status' => 'completed',
                'attachment' => [
                    'filename' => 'SOP_Keamanan_TaskFlow_v2.pdf',
                    'path' => 'attachments/sop_security_taskflow_v2.pdf',
                    'size' => 452819,
                    'mime_type' => 'application/pdf',
                ],
            ],
            [
                'user_id' => $demoUser->id,
                'title' => 'Rekapitulasi Faktur Pajak PPh 21 Bulan Oktober',
                'description' => 'Kompilasi bukti potong pajak karyawan tetap dan tenaga ahli lepas bersama divisi Finance & Accounting.',
                'deadline' => now()->addDays(14)->toDateString(),
                'priority' => 'high',
                'status' => 'pending',
                'attachment' => [
                    'filename' => 'Rekap_Pajak_PPh21_Oktober.pdf',
                    'path' => 'attachments/rekap_laporan_pajak_pph21.pdf',
                    'size' => 842100,
                    'mime_type' => 'application/pdf',
                ],
            ],
            [
                'user_id' => $demoUser->id,
                'title' => 'Dokumentasi Blueprint Arsitektur Cloud & Docker Compose',
                'description' => 'Penyusunan panduan multi-container Nginx, PHP-FPM, dan database terpusat untuk deployment portofolio.',
                'deadline' => now()->addDays(20)->toDateString(),
                'priority' => 'medium',
                'status' => 'pending',
                'attachment' => [
                    'filename' => 'Arsitektur_Cloud_v3.docx',
                    'path' => 'attachments/arsitektur_cloud_v3.docx',
                    'size' => 312000,
                    'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
            ],
            [
                'user_id' => $demoUser->id,
                'title' => 'Notulensi Rapat Koordinasi Kuartal III dengan Manajemen',
                'description' => 'Catatan arahan target performa aplikasi, efisiensi resource server, dan roadmap fitur semester depan.',
                'deadline' => now()->subDays(10)->toDateString(),
                'priority' => 'low',
                'status' => 'completed',
                'attachment' => [
                    'filename' => 'Notulensi_Rapat_Kuartal_III.docx',
                    'path' => 'attachments/notulensi_rapat_direksi.docx',
                    'size' => 204800,
                    'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
            ],
            [
                'user_id' => $demoUser->id,
                'title' => 'Audit Validasi Parameter IDOR pada Modul Task',
                'description' => 'Memastikan authorization check planner->user_id === auth()->id() terpasang di semua controller method.',
                'deadline' => now()->subDays(1)->toDateString(),
                'priority' => 'high',
                'status' => 'completed',
            ],
            [
                'user_id' => $demoUser->id,
                'title' => 'Peninjauan Kuota Disk Storage VPS & Cleanup Log',
                'description' => 'Pembersihan cache docker yang tidak terpakai dan pengecekan rotasi log harian sistem operasi.',
                'deadline' => now()->addDays(8)->toDateString(),
                'priority' => 'medium',
                'status' => 'pending',
            ],
            [
                'user_id' => $demoUser->id,
                'title' => 'Implementasi Rate Limiter pada Endpoint Autentikasi',
                'description' => 'Pemberian throttle 5 request per menit untuk mencegah brute-force attack pada halaman login.',
                'deadline' => now()->addDays(3)->toDateString(),
                'priority' => 'high',
                'status' => 'pending',
            ],
            [
                'user_id' => $demoUser->id,
                'title' => 'Konfigurasi Healthcheck Docker & Restart Policy',
                'description' => 'Pengujian toleransi crash container PHP dan Nginx dengan parameter restart unless-stopped.',
                'deadline' => now()->addDays(11)->toDateString(),
                'priority' => 'low',
                'status' => 'pending',
            ],
            [
                'user_id' => $demoUser->id,
                'title' => 'Simulasi Skrip Otomatisasi Reset Database Harian',
                'description' => 'Uji coba dry-run skrip reset-demo.sh pada jam 03:00 WIB agar data demo tetap segar setiap pagi.',
                'deadline' => now()->subDays(5)->toDateString(),
                'priority' => 'high',
                'status' => 'completed',
            ],

            // Tugas Pengguna Lain (Tim Rekanan)
            [
                'user_id' => $createdTeam[0]->id,
                'title' => 'Review Desain UI Dashboard Dark Mode Tailwind',
                'description' => 'Penyesuaian palet warna Plus Jakarta Sans dan efek glassmorphism dengan kontras WCAG AA.',
                'deadline' => now()->addDays(6)->toDateString(),
                'priority' => 'medium',
                'status' => 'pending',
            ],
            [
                'user_id' => $createdTeam[0]->id,
                'title' => 'Penyelarasan Icon FontAwesome 6 Pro',
                'description' => 'Pengecekan konsistensi ikon modul navigasi admin dan user dashboard.',
                'deadline' => now()->subDays(4)->toDateString(),
                'priority' => 'low',
                'status' => 'completed',
            ],
            [
                'user_id' => $createdTeam[1]->id,
                'title' => 'Rekonsiliasi Bank Mandiri & BCA Operasional Kantor',
                'description' => 'Pencocokan rekening koran bulan September dan Oktober dengan pencatatan petty cash.',
                'deadline' => now()->addDays(9)->toDateString(),
                'priority' => 'high',
                'status' => 'pending',
            ],
            [
                'user_id' => $createdTeam[2]->id,
                'title' => 'Pembaruan Dependensi Composer & Node Package',
                'description' => 'Pemeriksaan CVE security advisory pada framework Laravel 12 dan package dev.',
                'deadline' => now()->subDays(8)->toDateString(),
                'priority' => 'medium',
                'status' => 'completed',
            ],
            [
                'user_id' => $createdTeam[3]->id,
                'title' => 'Penyiapan Materi Presentasi Demo Portofolio Klien',
                'description' => 'Pembuatan slide ringkasan fitur utama, arsitektur micro-footprint, dan live demo access.',
                'deadline' => now()->addDays(4)->toDateString(),
                'priority' => 'high',
                'status' => 'pending',
            ],
            [
                'user_id' => $createdTeam[4]->id,
                'title' => 'Benchmarking Latensi Query Eloquent vs Raw SQL',
                'description' => 'Uji beban 50 concurrent connection untuk memastikan response time di bawah 150ms.',
                'deadline' => now()->addDays(15)->toDateString(),
                'priority' => 'low',
                'status' => 'pending',
            ],
            [
                'user_id' => $createdTeam[5]->id,
                'title' => 'Verifikasi Respons Header Keamanan HTTP',
                'description' => 'Pengujian X-Frame-Options, X-Content-Type-Options, dan Strict-Transport-Security.',
                'deadline' => now()->subDays(3)->toDateString(),
                'priority' => 'medium',
                'status' => 'completed',
            ],
            [
                'user_id' => $createdTeam[6]->id,
                'title' => 'Penyusunan Checklist Deployment Staging ke VPS Ubuntu',
                'description' => 'Dokumentasi langkah git clone, pembuatan file .env, dan eksekusi docker compose.',
                'deadline' => now()->addDays(7)->toDateString(),
                'priority' => 'high',
                'status' => 'pending',
            ],
            [
                'user_id' => $createdTeam[7]->id,
                'title' => 'Pengujian Akses Multi-Device Layar Mobile & Desktop',
                'description' => 'Memastikan sidebar responsif dan tampilan kartu task nyaman di browser smartphone.',
                'deadline' => now()->subDays(6)->toDateString(),
                'priority' => 'low',
                'status' => 'completed',
            ],
        ];

        foreach ($tasksData as $t) {
            $attachmentData = $t['attachment'] ?? null;
            unset($t['attachment']);

            $planner = Planner::updateOrCreate(
                [
                    'user_id' => $t['user_id'],
                    'title' => $t['title'],
                ],
                $t
            );

            if ($attachmentData) {
                Attachment::updateOrCreate(
                    [
                        'planner_id' => $planner->id,
                        'filename' => $attachmentData['filename'],
                    ],
                    $attachmentData
                );
            }
        }

        // 4. SEED RIWAYAT AUDIT / ACTIVITY LOGS
        $ips = ['103.28.12.45', '180.252.78.110', '114.124.200.89', '36.85.15.22', '127.0.0.1'];
        $actions = [
            'login',
            'created_planner',
            'uploaded_document',
            'updated_status_to_completed',
            'updated_status_to_pending',
        ];

        // Logs untuk Demo User
        foreach (range(1, 15) as $i) {
            ActivityLog::create([
                'user_id' => $demoUser->id,
                'action' => $actions[array_rand($actions)],
                'ip_address' => $ips[array_rand($ips)],
                'created_at' => now()->subHours($i * 4)->subMinutes(rand(5, 50)),
                'updated_at' => now()->subHours($i * 4)->subMinutes(rand(5, 50)),
            ]);
        }

        // Logs untuk Admin Demo
        foreach (range(1, 10) as $i) {
            ActivityLog::create([
                'user_id' => $admin->id,
                'action' => $i % 2 === 0 ? 'login' : 'view_admin_dashboard',
                'ip_address' => '180.252.78.110',
                'created_at' => now()->subHours($i * 6)->subMinutes(rand(2, 40)),
                'updated_at' => now()->subHours($i * 6)->subMinutes(rand(2, 40)),
            ]);
        }

        // Logs untuk user lainnya
        foreach ($createdTeam as $teamMember) {
            ActivityLog::create([
                'user_id' => $teamMember->id,
                'action' => 'login',
                'ip_address' => $ips[array_rand($ips)],
                'created_at' => now()->subDays(rand(1, 10)),
                'updated_at' => now()->subDays(rand(1, 10)),
            ]);
        }

        $this->command->info('DemoSeeder selesai! Akun demo (admin@demo.com & user@demo.com) siap digunakan.');
    }
}
