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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Support\Facades\File;
use ZipArchive;

#[Timeout(300)]
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
        $dir = storage_path('app/exports/'.$this->user->id);
        if(is_dir($dir)) {
            File::deleteDirectory($dir);
        }
        mkdir($dir, recursive: true);

        $this->writeCsv(Category::whereBelongsTo($this->user), $dir.'/categories.csv');
        $this->writeCsv(Location::whereBelongsTo($this->user)->withTrashed(), $dir.'/locations.csv');
        $this->writeCsv(Checkin::whereBelongsTo($this->user)->withTrashed(), $dir.'/checkins.csv');
        $this->writeCsv(PendingCheckin::whereBelongsTo($this->user), $dir.'/pending_checkins.csv');

        $this->writeCsv(Memory::whereBelongsTo($this->user)->withTrashed(), $dir.'/memories.csv');
        $this->writeCsv(Note::whereBelongsTo($this->user)->withTrashed(), $dir.'/notes.csv');

        $zip = new ZipArchive;
        $zip->open($dir.'.zip', ZipArchive::CREATE);
        foreach(glob($dir.'/*.csv') as $file)
        {
            $zip->addFile($file, basename($file));
        }
        $zip->close();

        File::deleteDirectory($dir);
    }

    private function writeCsv(Builder $query, string $path): void
    {
        $handle = fopen($path, 'w');
        $query->chunk(500, function(Collection $rows) use ($handle) {
            foreach($rows as $row) {
                fputcsv($handle, $row->toArray());
            }
        });
    }

    public function uniqueId(): string
    {
        return $this->user->id;
    }
}
