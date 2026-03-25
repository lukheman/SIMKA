#!/usr/bin/env php
<?php

/**
 * Script Update Project Laravel
 *
 * Script ini akan melakukan update project dari repository Git:
 * 1. Git pull untuk mengambil perubahan terbaru
 * 2. Composer install jika ada perubahan dependencies
 * 3. Menjalankan migrasi database
 * 4. Membersihkan cache aplikasi
 * 5. Mengoptimasi aplikasi
 *
 * Penggunaan: php update.php
 *
 * @author Akmal
 * @instagram @lukheeman
 * @phone 082250212121
 * @portfolio https://lukheman.github.io/portfolio/
 */

// Deteksi sistem operasi
define('IS_WINDOWS', PHP_OS_FAMILY === 'Windows');

// Enable ANSI colors di Windows 10+
if (IS_WINDOWS && function_exists('sapi_windows_vt100_support')) {
    @sapi_windows_vt100_support(STDOUT, true);
    @sapi_windows_vt100_support(STDERR, true);
}

// Warna untuk output console
class Warna
{
    public const RESET = "\033[0m";
    public const HIJAU = "\033[32m";
    public const MERAH = "\033[31m";
    public const KUNING = "\033[33m";
    public const BIRU = "\033[34m";
    public const CYAN = "\033[36m";
    public const TEBAL = "\033[1m";
}

/**
 * Cetak pesan dengan warna
 */
function cetakPesan(string $pesan, string $warna = Warna::RESET): void
{
    echo $warna . $pesan . Warna::RESET . PHP_EOL;
}

/**
 * Cetak header untuk setiap langkah
 */
function cetakLangkah(int $langkah, string $judul): void
{
    echo PHP_EOL;
    cetakPesan("===============================================================", Warna::CYAN);
    cetakPesan("  Langkah {$langkah}: {$judul}", Warna::TEBAL . Warna::CYAN);
    cetakPesan("===============================================================", Warna::CYAN);
    echo PHP_EOL;
}

/**
 * Cetak pesan sukses
 */
function cetakSukses(string $pesan): void
{
    cetakPesan("  ✓ " . $pesan, Warna::HIJAU);
}

/**
 * Cetak pesan error
 */
function cetakError(string $pesan): void
{
    cetakPesan("  ✗ " . $pesan, Warna::MERAH);
}

/**
 * Cetak pesan peringatan
 */
function cetakPeringatan(string $pesan): void
{
    cetakPesan("  ⚠ " . $pesan, Warna::KUNING);
}

/**
 * Cetak pesan info
 */
function cetakInfo(string $pesan): void
{
    cetakPesan("  → " . $pesan, Warna::BIRU);
}

/**
 * Jalankan command dan tampilkan output
 */
function jalankanCommand(string $command, bool $tampilkanOutput = true): bool
{
    cetakInfo("Menjalankan: {$command}");
    echo PHP_EOL;

    $descriptorSpec = [
        0 => STDIN,
        1 => STDOUT,
        2 => STDERR,
    ];

    $proses = proc_open($command, $descriptorSpec, $pipes);

    if (is_resource($proses)) {
        $kodeReturn = proc_close($proses);
        echo PHP_EOL;
        return $kodeReturn === 0;
    }

    return false;
}

/**
 * Jalankan command dan dapatkan output sebagai string
 */
function dapatkanOutput(string $command): string
{
    if (IS_WINDOWS) {
        return shell_exec("{$command} 2>NUL") ?? '';
    }
    return shell_exec("{$command} 2>/dev/null") ?? '';
}

/**
 * Cek apakah command tersedia di sistem
 */
function commandTersedia(string $command): bool
{
    if (IS_WINDOWS) {
        $result = shell_exec("where {$command} 2>NUL");
    } else {
        $result = shell_exec("which {$command} 2>/dev/null");
    }
    return !empty(trim($result ?? ''));
}

/**
 * Cek apakah ada perubahan pada composer.lock
 */
function adaPerubahanComposer(): bool
{
    $output = dapatkanOutput("git diff --name-only HEAD@{1} HEAD 2>/dev/null");
    return str_contains($output, 'composer.lock') || str_contains($output, 'composer.json');
}

/**
 * Cek apakah ada perubahan pada folder migrations
 */
function adaPerubahanMigrasi(): bool
{
    $output = dapatkanOutput("git diff --name-only HEAD@{1} HEAD 2>/dev/null");
    return str_contains($output, 'database/migrations');
}

/**
 * Tampilkan informasi kontak developer
 */
function tampilkanKontak(): void
{
    cetakPesan(PHP_EOL);
    cetakPesan("+---------------------------------------------------------------+", Warna::TEBAL . Warna::CYAN);
    cetakPesan("|                     📞 INFORMASI KONTAK 📞                    |", Warna::TEBAL . Warna::CYAN);
    cetakPesan("+---------------------------------------------------------------+", Warna::TEBAL . Warna::CYAN);
    cetakPesan("|                                                               |", Warna::TEBAL . Warna::CYAN);
    cetakPesan("|   Dikembangkan oleh:                                          |", Warna::TEBAL . Warna::CYAN);
    cetakPesan("|                                                               |", Warna::TEBAL . Warna::CYAN);
    cetakPesan("|   👤 Nama      : Akmal                                        |", Warna::TEBAL . Warna::CYAN);
    cetakPesan("|   📱 Instagram : @lukheeman                                   |", Warna::TEBAL . Warna::CYAN);
    cetakPesan("|   📞 No. HP    : 082250212121                                 |", Warna::TEBAL . Warna::CYAN);
    cetakPesan("|   🌐 Portfolio : https://lukheman.github.io/portfolio/        |", Warna::TEBAL . Warna::CYAN);
    cetakPesan("|   🌐 Website   : https://aplikasita.my.id/                    |", Warna::TEBAL . Warna::CYAN);
    cetakPesan("|                                                               |", Warna::TEBAL . Warna::CYAN);
    cetakPesan("|   Silahkan hubungi untuk pertanyaan atau bantuan!             |", Warna::TEBAL . Warna::CYAN);
    cetakPesan("|                                                               |", Warna::TEBAL . Warna::CYAN);
    cetakPesan("+---------------------------------------------------------------+", Warna::TEBAL . Warna::CYAN);
    cetakPesan(PHP_EOL);
}

// =============================================================================
// SCRIPT UTAMA
// =============================================================================

$pathProject = __DIR__;
chdir($pathProject);

$waktuMulai = microtime(true);

cetakPesan(PHP_EOL);
cetakPesan("+---------------------------------------------------------------+", Warna::TEBAL . Warna::HIJAU);
cetakPesan("|                                                               |", Warna::TEBAL . Warna::HIJAU);
cetakPesan("|             SIMKA - SCRIPT UPDATE APLIKASI                    |", Warna::TEBAL . Warna::HIJAU);
cetakPesan("|                                                               |", Warna::TEBAL . Warna::HIJAU);
cetakPesan("+---------------------------------------------------------------+", Warna::TEBAL . Warna::HIJAU);
cetakPesan(PHP_EOL);

cetakInfo("Lokasi Project: {$pathProject}");
cetakInfo("Waktu: " . date('Y-m-d H:i:s'));

// =============================================================================
// LANGKAH 0: CEK KEBUTUHAN
// =============================================================================

cetakLangkah(0, "Mengecek Kebutuhan");

// Cek Git
if (!commandTersedia('git')) {
    cetakError("Git tidak ditemukan! Silahkan install Git terlebih dahulu.");
    exit(1);
}
cetakSukses("Git ditemukan");

// Cek apakah ini repository Git
if (!file_exists("{$pathProject}/.git")) {
    cetakError("Folder ini bukan repository Git!");
    exit(1);
}
cetakSukses("Repository Git terdeteksi");

// Cek Composer
if (!commandTersedia('composer')) {
    cetakError("Composer tidak ditemukan! Silahkan install Composer terlebih dahulu.");
    exit(1);
}
cetakSukses("Composer ditemukan");

// =============================================================================
// LANGKAH 1: CEK STATUS GIT
// =============================================================================

cetakLangkah(1, "Mengecek Status Git");

// Tampilkan branch saat ini
$currentBranch = trim(dapatkanOutput("git branch --show-current"));
cetakInfo("Branch saat ini: {$currentBranch}");

// Cek apakah ada perubahan lokal yang belum di-commit
$statusOutput = dapatkanOutput("git status --porcelain");
if (!empty(trim($statusOutput))) {
    cetakPeringatan("Ada perubahan lokal yang belum di-commit:");
    echo Warna::KUNING . $statusOutput . Warna::RESET;
    cetakPesan("");
    cetakPeringatan("Perubahan lokal akan tetap dipertahankan.");
    cetakPesan("");
}

// =============================================================================
// LANGKAH 2: GIT PULL
// =============================================================================

cetakLangkah(2, "Mengambil Pembaruan dari Repository (Git Pull)");

// Simpan hash commit sebelum pull untuk perbandingan
$hashSebelum = trim(dapatkanOutput("git rev-parse HEAD"));

if (!jalankanCommand("git pull")) {
    cetakError("Git pull gagal!");
    cetakPesan("");
    cetakPeringatan("Kemungkinan penyebab:");
    cetakPesan("  1. Tidak ada koneksi internet", Warna::KUNING);
    cetakPesan("  2. Ada konflik dengan perubahan lokal", Warna::KUNING);
    cetakPesan("  3. Remote repository tidak tersedia", Warna::KUNING);
    exit(1);
}

// Cek hash setelah pull
$hashSesudah = trim(dapatkanOutput("git rev-parse HEAD"));

if ($hashSebelum === $hashSesudah) {
    cetakSukses("Project sudah versi terbaru, tidak ada pembaruan.");
} else {
    cetakSukses("Pembaruan berhasil diambil!");

    // Tampilkan perubahan
    cetakInfo("Commit baru:");
    $logOutput = dapatkanOutput("git log --oneline {$hashSebelum}..{$hashSesudah}");
    echo Warna::CYAN . $logOutput . Warna::RESET;
}

// =============================================================================
// LANGKAH 3: UPDATE DEPENDENCIES COMPOSER (JIKA ADA PERUBAHAN)
// =============================================================================

cetakLangkah(3, "Update Dependencies Composer");

// Cek apakah ada perubahan pada composer.json atau composer.lock
$composerUpdated = false;
if ($hashSebelum !== $hashSesudah) {
    // Ada update dari git pull, cek apakah composer files berubah
    $changedFiles = dapatkanOutput("git diff --name-only {$hashSebelum} {$hashSesudah}");

    if (str_contains($changedFiles, 'composer.json') || str_contains($changedFiles, 'composer.lock')) {
        cetakInfo("Terdeteksi perubahan pada composer.json/composer.lock");
        cetakInfo("Menjalankan composer install...");

        if (!jalankanCommand("composer install --no-interaction --optimize-autoloader")) {
            cetakError("Composer install gagal!");
            exit(1);
        }

        $composerUpdated = true;
        cetakSukses("Dependencies Composer berhasil diupdate!");
    } else {
        cetakSukses("Tidak ada perubahan dependencies, melewati composer install.");
    }
} else {
    cetakSukses("Tidak ada update, melewati composer install.");
}

// =============================================================================
// LANGKAH 4: MIGRASI DATABASE
// =============================================================================

cetakLangkah(4, "Menjalankan Migrasi Database");

cetakInfo("Mengecek dan menjalankan migrasi baru...");

if (!jalankanCommand("php artisan migrate:fresh --seed")) {
    cetakError("Migrasi database gagal!");
    cetakPesan("");
    cetakPeringatan("Kemungkinan penyebab:");
    cetakPesan("  1. Database tidak terhubung", Warna::KUNING);
    cetakPesan("  2. Kredensial database pada .env salah", Warna::KUNING);
    cetakPesan("  3. Ada error pada file migrasi", Warna::KUNING);
    exit(1);
}

cetakSukses("Migrasi database selesai!");

// =============================================================================
// LANGKAH 5: MEMBERSIHKAN CACHE
// =============================================================================

cetakLangkah(5, "Membersihkan Cache Aplikasi");

cetakInfo("Membersihkan semua cache...");

// Clear config cache
jalankanCommand("php artisan config:clear");

// Clear route cache
jalankanCommand("php artisan route:clear");

// Clear view cache
jalankanCommand("php artisan view:clear");

// Clear application cache
jalankanCommand("php artisan cache:clear");

// Clear compiled classes
jalankanCommand("php artisan clear-compiled");

cetakSukses("Semua cache berhasil dibersihkan!");

// =============================================================================
// LANGKAH 6: OPTIMASI APLIKASI
// =============================================================================

cetakLangkah(6, "Mengoptimasi Aplikasi");

// Regenerate autoload
cetakInfo("Regenerate autoload...");
jalankanCommand("composer dump-autoload --optimize");

cetakSukses("Aplikasi berhasil dioptimasi!");

// =============================================================================
// SELESAI!
// =============================================================================

$waktuSelesai = microtime(true);
$durasiDetik = round($waktuSelesai - $waktuMulai, 2);

cetakPesan(PHP_EOL);
cetakPesan("+---------------------------------------------------------------+", Warna::TEBAL . Warna::HIJAU);
cetakPesan("|                                                               |", Warna::TEBAL . Warna::HIJAU);
cetakPesan("|           ✅ UPDATE PROJECT BERHASIL! ✅                      |", Warna::TEBAL . Warna::HIJAU);
cetakPesan("|                                                               |", Warna::TEBAL . Warna::HIJAU);
cetakPesan("+---------------------------------------------------------------+", Warna::TEBAL . Warna::HIJAU);
cetakPesan(PHP_EOL);

cetakInfo("Waktu proses: {$durasiDetik} detik");
cetakInfo("Project berhasil diupdate ke versi terbaru!");
cetakPesan("");

// Ringkasan
$composerStatus = $composerUpdated ? "Selesai" : "Dilewati (tidak ada perubahan)";
cetakPesan("  Ringkasan Update:", Warna::TEBAL . Warna::CYAN);
cetakPesan("  ├─ Git Pull: Selesai", Warna::CYAN);
cetakPesan("  ├─ Composer Install: {$composerStatus}", Warna::CYAN);
cetakPesan("  ├─ Database Migration: Selesai", Warna::CYAN);
cetakPesan("  ├─ Clear Cache: Selesai", Warna::CYAN);
cetakPesan("  └─ Optimasi: Selesai", Warna::CYAN);
cetakPesan("");

cetakPesan("  Untuk menjalankan aplikasi, gunakan:", Warna::KUNING);
cetakPesan("  " . Warna::TEBAL . "php serve.php" . Warna::RESET . " atau " . Warna::TEBAL . "php artisan serve" . Warna::RESET);
cetakPesan("");

// Tampilkan informasi kontak
tampilkanKontak();
