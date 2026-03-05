<?php

namespace App\Livewire\Auth;

use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.guest.layout')]
#[Title('Daftar Anggota - SIMKA')]
class Register extends Component
{
    public string $nama_lengkap = '';
    public string $nik = '';
    public string $email = '';
    public string $no_telp = '';
    public string $alamat = '';
    public string $pekerjaan = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16'],
            'email' => ['required', 'email', 'max:255', 'unique:anggota,email'],
            'no_telp' => ['required', 'string', 'max:15'],
            'alamat' => ['required', 'string'],
            'pekerjaan' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    protected $messages = [
        'nik.size' => 'NIK harus terdiri dari 16 digit.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ];

    private function generateNoAnggota(): string
    {
        $lastAnggota = Anggota::orderBy('id', 'desc')->first();
        $lastNumber = $lastAnggota ? (int) substr($lastAnggota->no_anggota, 4) : 0;
        return 'AGT-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    public function submit()
    {
        $validated = $this->validate();

        $anggota = Anggota::create([
            'no_anggota' => $this->generateNoAnggota(),
            'nama_lengkap' => $validated['nama_lengkap'],
            'nik' => $validated['nik'],
            'email' => $validated['email'],
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'],
            'pekerjaan' => $validated['pekerjaan'],
            'password' => Hash::make($validated['password']),
            'tgl_bergabung' => now()->toDateString(),
        ]);

        Auth::guard('anggota')->login($anggota);

        session()->regenerate();

        return redirect()->route('anggota.dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
