<?php

namespace App\Livewire\Admin;

use App\Enum\StatusAktif;
use App\Models\Anggota;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.admin.livewire-layout')]
class AnggotaForm extends Component
{
    public ?int $anggotaId = null;

    // Form fields
    public string $no_anggota = '';
    public string $nik = '';
    public string $nama_lengkap = '';
    public string $alamat = '';
    public string $pekerjaan = '';
    public string $no_telp = '';
    public string $tgl_bergabung = '';
    public string $status_aktif = 'aktif';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(?int $id = null): void
    {
        if ($id) {
            $anggota = Anggota::findOrFail($id);
            $this->anggotaId = $id;
            $this->no_anggota = $anggota->no_anggota;
            $this->nik = $anggota->nik;
            $this->nama_lengkap = $anggota->nama_lengkap;
            $this->alamat = $anggota->alamat;
            $this->pekerjaan = $anggota->pekerjaan;
            $this->no_telp = $anggota->no_telp;
            $this->tgl_bergabung = $anggota->tgl_bergabung;
            $this->status_aktif = $anggota->status_aktif instanceof StatusAktif
                ? $anggota->status_aktif->value
                : $anggota->status_aktif;
            $this->email = $anggota->email;
        } else {
            $this->tgl_bergabung = now()->format('Y-m-d');
        }
    }

    public function getTitle(): string
    {
        return $this->anggotaId ? 'Edit Anggota' : 'Tambah Anggota';
    }

    protected function rules(): array
    {
        $rules = [
            'no_anggota' => ['required', 'string', 'max:50'],
            'nik' => ['required', 'string', 'max:20'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'pekerjaan' => ['required', 'string', 'max:255'],
            'no_telp' => ['required', 'string', 'max:20'],
            'tgl_bergabung' => ['required', 'date'],
            'status_aktif' => ['required', 'in:' . implode(',', StatusAktif::values())],
            'email' => ['required', 'email', 'max:255'],
        ];

        if ($this->anggotaId) {
            $rules['no_anggota'][] = 'unique:anggota,no_anggota,' . $this->anggotaId;
            $rules['email'][] = 'unique:anggota,email,' . $this->anggotaId;
            if ($this->password) {
                $rules['password'] = ['min:8', 'confirmed'];
            }
        } else {
            $rules['no_anggota'][] = 'unique:anggota,no_anggota';
            $rules['email'][] = 'unique:anggota,email';
            $rules['password'] = ['required', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = collect($validated)->only([
            'no_anggota',
            'nik',
            'nama_lengkap',
            'alamat',
            'pekerjaan',
            'no_telp',
            'tgl_bergabung',
            'status_aktif',
            'email',
        ])->toArray();

        if ($this->anggotaId) {
            $anggota = Anggota::findOrFail($this->anggotaId);

            if (!empty($this->password)) {
                $data['password'] = $this->password;
            }

            $anggota->update($data);
            session()->flash('success', 'Data anggota berhasil diperbarui.');
        } else {
            $data['password'] = $validated['password'];
            Anggota::create($data);
            session()->flash('success', 'Data anggota berhasil ditambahkan.');
        }

        $this->redirect(route('admin.anggota'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.anggota-form', [
            'statusOptions' => StatusAktif::cases(),
        ]);
    }
}
