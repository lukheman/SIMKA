<?php

namespace App\Livewire\Admin;

use App\Models\JenisSimpanan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Manajemen Jenis Simpanan')]
class JenisSimpananManagement extends Component
{
    use WithPagination;

    // Search
    #[Url(as: 'q')]
    public string $search = '';

    // Form fields
    public string $nama_simpanan = '';
    public string $minimal_setor = '';

    // State
    public ?int $editingId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    protected function rules(): array
    {
        $rules = [
            'nama_simpanan' => ['required', 'string', 'max:255'],
            'minimal_setor' => ['required', 'numeric', 'min:0'],
        ];

        if ($this->editingId) {
            $rules['nama_simpanan'][] = 'unique:jenis_simpanan,nama_simpanan,' . $this->editingId;
        } else {
            $rules['nama_simpanan'][] = 'unique:jenis_simpanan,nama_simpanan';
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
        $jenis = JenisSimpanan::findOrFail($id);
        $this->editingId = $id;
        $this->nama_simpanan = $jenis->nama_simpanan;
        $this->minimal_setor = $jenis->minimal_setor;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $jenis = JenisSimpanan::findOrFail($this->editingId);
            $jenis->update($validated);
            session()->flash('success', 'Jenis simpanan berhasil diperbarui.');
        } else {
            JenisSimpanan::create($validated);
            session()->flash('success', 'Jenis simpanan berhasil ditambahkan.');
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

    public function deleteJenisSimpanan(): void
    {
        if ($this->deletingId) {
            JenisSimpanan::destroy($this->deletingId);
            session()->flash('success', 'Jenis simpanan berhasil dihapus.');
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
        $this->nama_simpanan = '';
        $this->minimal_setor = '';
        $this->editingId = null;
    }

    public function render()
    {
        $jenisSimpanans = JenisSimpanan::query()
            ->when($this->search, function ($query) {
                $query->where('nama_simpanan', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.jenis-simpanan-management', [
            'jenisSimpanans' => $jenisSimpanans,
        ]);
    }
}
