<?php

namespace App\Livewire\Admin;

use App\Enum\StatusAktif;
use App\Models\Anggota;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Manajemen Anggota')]
class AnggotaManagement extends Component
{
    use WithPagination;

    // Search
    #[Url(as: 'q')]
    public string $search = '';

    // Form fields
    public string $no_anggota = '';
    public string $nik = '';
    public string $nama_lengkap = '';
    public string $alamat = '';
    public string $pekerjaan = '';
    public string $no_telp = '';
    public string $tgl_bergabung = '';
    public string $status_aktif = 'aktif';

    // State
    public ?int $editingId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

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
        ];

        if ($this->editingId) {
            $rules['no_anggota'][] = 'unique:anggota,no_anggota,' . $this->editingId;
        } else {
            $rules['no_anggota'][] = 'unique:anggota,no_anggota';
        }

        return $rules;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->tgl_bergabung = now()->format('Y-m-d');
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $anggota = Anggota::findOrFail($id);
        $this->editingId = $id;
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
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $anggota = Anggota::findOrFail($this->editingId);
            $anggota->update($validated);
            session()->flash('success', 'Data anggota berhasil diperbarui.');
        } else {
            // Create user for the anggota
            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'email' => strtolower(str_replace(' ', '', $validated['nama_lengkap'])) . '@simka.local',
                'password' => bcrypt('password'),
                'role' => 'anggota',
            ]);

            Anggota::create(array_merge($validated, ['user_id' => $user->id]));
            session()->flash('success', 'Data anggota berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteAnggota(): void
    {
        if ($this->deletingId) {
            Anggota::destroy($this->deletingId);
            session()->flash('success', 'Data anggota berhasil dihapus.');
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    protected function resetForm(): void
    {
        $this->no_anggota = '';
        $this->nik = '';
        $this->nama_lengkap = '';
        $this->alamat = '';
        $this->pekerjaan = '';
        $this->no_telp = '';
        $this->tgl_bergabung = '';
        $this->status_aktif = 'aktif';
        $this->editingId = null;
    }

    public function render()
    {
        $anggotas = Anggota::query()
            ->when($this->search, function ($query) {
                $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('no_anggota', 'like', '%' . $this->search . '%')
                    ->orWhere('nik', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.anggota-management', [
            'anggotas' => $anggotas,
            'statusOptions' => StatusAktif::cases(),
        ]);
    }
}
