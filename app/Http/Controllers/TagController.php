<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tags\CreateTagRequest;
use App\Http\Requests\Tags\EditTagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Tag::class, 'tag');
    }

    public function index(Request $request)
    {
        return view('tags.index', [
            // must filter by user id, access not managed by policy class
            'tags' => Tag::whereBelongsTo($request->user())->get(),
        ]);
    }

    public function create()
    {
        return view('tags.edit', ['tag' => null]);
    }

    public function store(CreateTagRequest $request)
    {
        $tag = new Tag();
        $tag->name = $request->name;
        $tag->user_id = $request->user()->id;
        $tag->fill($request->safe()->only('display_name', 'icon'));
        $tag->save();

        return redirect(route('tags.show', $tag));
    }

    public function show(Tag $tag)
    {
        return view('tags.tag', ['tag' => $tag]);
    }

    public function edit(Tag $tag)
    {
        return view('tags.edit', ['tag' => $tag]);
    }

    public function update(EditTagRequest $request, Tag $tag)
    {
        $tag->fill($request->safe()->all());
        $tag->save();

        return redirect(route('tags.show', $tag));
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect(route('tags.index'));
    }
}
