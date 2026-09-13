<?php

namespace App\Jobs;

use App\Models\Locations\Category;
use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use App\Models\Locations\PendingCheckin;
use App\Models\Memory;
use App\Models\Note;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExportUserData implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $categories = Category::whereBelongsTo($this->user);
        $locations = Location::whereBelongsTo($this->user)->with('categories');
        $checkins = Checkin::whereBelongsTo($this->user);
        $pending = PendingCheckin::whereBelongsTo($this->user);

        $memories = Memory::whereBelongsTo($this->user);
        $notes = Note::whereBelongsTo($this->user);
    }

    public function uniqueId(): string
    {
        return $this->user->id;
    }
}
