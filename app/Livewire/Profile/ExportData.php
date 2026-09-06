<?php

namespace App\Livewire\Profile;

use App\Jobs\ExportUserData;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ExportData extends Component
{
    public function mount()
    {
        if( !Auth::check()) {
            abort(401);
        }
    }

    public function render()
    {
        return view('livewire.profile.export-data');
    }

    public function exportData()
    {
        ExportUserData::dispatch(Auth::user());
    }
}
