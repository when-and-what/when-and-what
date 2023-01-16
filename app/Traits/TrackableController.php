<?php

namespace App\Traits;

use App\Models\Tracker;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait TrackableController
{
    /**
     * Undocumented function
     *
     * @param string[] $text
     * @param Model $model
     * @param User $user
     * @return Tracker[]
     */
    public function saveTrackers(array $texts, Model $model, User $user)
    {
        $trackers = [];
        foreach ($texts as $text) {
            preg_match_all('/(?<!\w)#\w+/', $text, $matches);
            $tags = $matches[0];
            foreach ($tags as $tag_name) {
                $name = strtolower(substr($tag_name, 1));
                $tracker = Tracker::where('name', $name)
                    ->whereBelongsTo($user)
                    ->first();
                if (!$tracker) {
                    $tracker = new Tracker();
                    $tracker->user_id = $user->id;
                    $tracker->display_name = $tag_name;
                    $tracker->name = $name;
                    $tracker->icon = '';
                    $tracker->save();
                }
                $trackers[] = $tracker;
            }
        }

        $model->trackers()->sync($trackers);
    }
}
