<?php

namespace App\Livewire\Guest;

use App\Models\Anggota;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.guest.layout')]
#[Title('SIMKA - Sistem Informasi Pengelolaan Simpan Pinjam Kredit Union (CU) Mentari Kasih TP Pomalaa')]
class LandingPage extends Component
{
    public function render()
    {
        return view('livewire.guest.landing-page', [
            'totalAnggota' => Anggota::count(),
        ]);
    }
}
