<?php

namespace App\Traits;

use App\Models\Tag;
use App\Models\User;

trait TaggableContent
{
    public function tagLinks(string $text, User $user): string
    {
        $html = $text;
        preg_match_all('/(?<!\w)#\w+/', $text, $matches);
        $found = $matches[0];
        foreach ($found as $tag_name) {
            $name = strtolower(substr($tag_name, 1));
            $tag = Tag::where('name', $name)
                ->whereBelongsTo($user)
                ->first();
            str_replace($tag_name, '<a href="#">' . $tag->display_name . '</a>', $html);
        }
        return $html;
    }
}
