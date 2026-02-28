<?php

namespace App\Livewire\Anggota;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.admin.livewire-layout')]
#[Title('Profil Saya')]
class AnggotaProfile extends Component
{
    use WithFileUploads;

    public string $nama_lengkap = '';
    public string $email = '';
    public string $alamat = '';
    public string $pekerjaan = '';
    public string $no_telp = '';

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public $avatar;
    public ?string $currentAvatar = null;

    public bool $showPasswordSection = false;

    public function mount(): void
    {
        $anggota = Auth::guard('anggota')->user();
        $this->nama_lengkap = $anggota->nama_lengkap;
        $this->email = $anggota->email;
        $this->alamat = $anggota->alamat;
        $this->pekerjaan = $anggota->pekerjaan;
        $this->no_telp = $anggota->no_telp;
        $this->currentAvatar = $anggota->avatar;
    }

    public function togglePasswordSection(): void
    {
        $this->showPasswordSection = !$this->showPasswordSection;
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetValidation(['current_password', 'password', 'password_confirmation']);
    }

    public function updatedAvatar(): void
    {
        $this->validate([
            'avatar' => ['image', 'max:2048'],
        ]);
    }

    public function uploadAvatar(): void
    {
        $this->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $anggota = Auth::guard('anggota')->user();

        if ($anggota->avatar && Storage::exists($anggota->avatar)) {
            Storage::delete($anggota->avatar);
        }

        $path = $this->avatar->store('avatars/anggota', 'public');

        $anggota->avatar = $path;
        $anggota->save();

        $this->currentAvatar = $path;
        $this->avatar = null;

        session()->flash('success', 'Foto profil berhasil diperbarui.');
    }

    public function removeAvatar(): void
    {
        $anggota = Auth::guard('anggota')->user();

        if ($anggota->avatar && Storage::exists($anggota->avatar)) {
            Storage::delete($anggota->avatar);
        }

        $anggota->avatar = null;
        $anggota->save();

        $this->currentAvatar = null;

        session()->flash('success', 'Foto profil berhasil dihapus.');
    }

    public function updateProfile(): void
    {
        $anggota = Auth::guard('anggota')->user();

        $validated = $this->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:anggota,email,' . $anggota->id],
            'alamat' => ['required', 'string'],
            'pekerjaan' => ['required', 'string', 'max:255'],
            'no_telp' => ['required', 'string', 'max:20'],
        ]);

        $anggota->update($validated);

        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(): void
    {
        $anggota = Auth::guard('anggota')->user();

        $this->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($this->current_password, $anggota->password)) {
            $this->addError('current_password', 'Password saat ini tidak sesuai.');
            return;
        }

        $anggota->update([
            'password' => $this->password,
        ]);

        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->showPasswordSection = false;

        session()->flash('success', 'Password berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.anggota.profile');
    }
}
