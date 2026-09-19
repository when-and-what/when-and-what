<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserExportDownload extends Controller
{
    public function __invoke(Request $request)
    {
        $file = 'app/exports/'.$request->user()->id.'.zip';
        if (Storage::exists($file)) {
            return Storage::download('app/exports/'.$request->user()->id.'.zip');
        } else {
            return redirect(route('profile.show').'#export-data')->with('export-message', 'Your export has expired. Use the button below to request a new export');
        }
    }
}
