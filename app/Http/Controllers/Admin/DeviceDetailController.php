<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class DeviceDetailController extends Controller
{
    public function show(Device $device)
    {
        $notes = collect();
        $editNote = null;

        if (Schema::hasTable('device_notes')) {
            $notes = $device->notes()
                ->with('user')
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->get();

            $editNoteId = request()->integer('edit_note');
            if ($editNoteId > 0) {
                $editNote = $notes->firstWhere('id', $editNoteId);

                abort_if(! $editNote, 404);
                Gate::authorize('update', $editNote);
            }
        }

        return view('admin.device-detail', compact('device', 'notes', 'editNote'));
    }
}
