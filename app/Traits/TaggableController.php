<?php

namespace App\Traits;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait TaggableController
{
    /**
     * Parse the given text(s) for any tags and associate them with the given model
     *
     * @param string|string[] $text
     * @param Model $model
     * @param User $user
     * @return Tag[]
     */
    public function saveTags($texts, Model $model, User $user)
    {
        $tags = collect([]);
        if (!is_array($texts)) {
            $texts = [$texts];
        }
        foreach ($texts as $text) {
            preg_match_all('/(?<!\w)#\w+/', $text, $matches);
            $found = $matches[0];
            foreach ($found as $tag_name) {
                $name = strtolower(substr($tag_name, 1));
                $tag = Tag::where('name', $name)
                    ->whereBelongsTo($user)
                    ->first();

                if (!$tag) {
                    $tag = new Tag();
                    $tag->user_id = $user->id;
                    $tag->display_name = $tag_name;
                    $tag->name = $name;
                    $tag->icon = '';
                    $tag->save();
                }
                $tags->concat($tag);
            }
        }

        ray($tags->pluck('id'));
        $model->tags()->sync($tags->pluck('id'));
        return $tags;
    }
}
