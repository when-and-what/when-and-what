<?php

namespace App\Livewire\Profile;

use App\Jobs\ExportUserData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ExportData extends Component
{
    public ?bool $exportStatus = null;

    public function mount()
    {
        if (! Auth::check()) {
            abort(401);
        }
    }

    public function render()
    {
        return view('livewire.profile.export-data');
    }

    public function exportData()
    {
        $this->exportStatus = RateLimiter::attempt('user-export-'.Auth::id(), 1, function () {
            ExportUserData::dispatch(Auth::user());
        }, 60 * 60 * 24); // 24 hours
    }
}
