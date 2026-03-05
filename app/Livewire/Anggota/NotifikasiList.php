<?php

namespace App\Livewire\Anggota;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.admin.livewire-layout')]
#[Title('Notifikasi')]
class NotifikasiList extends Component
{
    public function tandaiBaca(int $id): void
    {
        $notifikasi = Notifikasi::where('anggota_id', Auth::guard('anggota')->id())
            ->findOrFail($id);

        $notifikasi->update(['dibaca' => true]);
    }

    public function tandaiSemuaBaca(): void
    {
        Notifikasi::where('anggota_id', Auth::guard('anggota')->id())
            ->belumDibaca()
            ->update(['dibaca' => true]);
    }

    public function render()
    {
        $notifikasis = Notifikasi::where('anggota_id', Auth::guard('anggota')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $belumDibaca = Notifikasi::where('anggota_id', Auth::guard('anggota')->id())
            ->belumDibaca()
            ->count();

        return view('livewire.anggota.notifikasi-list', [
            'notifikasis' => $notifikasis,
            'belumDibaca' => $belumDibaca,
        ]);
    }
}
