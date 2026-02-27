<?php

namespace App\Livewire\Admin;

use App\Models\JenisPinjaman;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Manajemen Jenis Pinjaman')]
class JenisPinjamanManagement extends Component
{
    use WithPagination;

    // Search
    #[Url(as: 'q')]
    public string $search = '';

    // Form fields
    public string $nama_pinjaman = '';
    public string $bunga_persen = '';
    public string $maks_tenor_bulan = '';

    // State
    public ?int $editingId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    protected function rules(): array
    {
        $rules = [
            'nama_pinjaman' => ['required', 'string', 'max:255'],
            'bunga_persen' => ['required', 'numeric', 'min:0', 'max:100'],
            'maks_tenor_bulan' => ['required', 'integer', 'min:1'],
        ];

        if ($this->editingId) {
            $rules['nama_pinjaman'][] = 'unique:jenis_pinjaman,nama_pinjaman,' . $this->editingId;
        } else {
            $rules['nama_pinjaman'][] = 'unique:jenis_pinjaman,nama_pinjaman';
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
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $jenis = JenisPinjaman::findOrFail($id);
        $this->editingId = $id;
        $this->nama_pinjaman = $jenis->nama_pinjaman;
        $this->bunga_persen = $jenis->bunga_persen;
        $this->maks_tenor_bulan = $jenis->maks_tenor_bulan;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $jenis = JenisPinjaman::findOrFail($this->editingId);
            $jenis->update($validated);
            session()->flash('success', 'Jenis pinjaman berhasil diperbarui.');
        } else {
            JenisPinjaman::create($validated);
            session()->flash('success', 'Jenis pinjaman berhasil ditambahkan.');
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

    public function deleteJenisPinjaman(): void
    {
        if ($this->deletingId) {
            JenisPinjaman::destroy($this->deletingId);
            session()->flash('success', 'Jenis pinjaman berhasil dihapus.');
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
        $this->nama_pinjaman = '';
        $this->bunga_persen = '';
        $this->maks_tenor_bulan = '';
        $this->editingId = null;
    }

    public function render()
    {
        $jenisPinjamans = JenisPinjaman::query()
            ->when($this->search, function ($query) {
                $query->where('nama_pinjaman', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.jenis-pinjaman-management', [
            'jenisPinjamans' => $jenisPinjamans,
        ]);
    }
}
