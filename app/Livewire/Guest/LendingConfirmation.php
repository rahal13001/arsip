<?php

namespace App\Livewire\Guest;

use Livewire\Component;

class LendingConfirmation extends Component
{
    public function render()
    {
        return view('livewire.guest.lending-confirmation')
            ->layout('components.layouts.app');
    }
}
