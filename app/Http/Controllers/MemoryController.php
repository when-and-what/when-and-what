<?php

namespace App\Http\Controllers;

use App\Http\Requests\Memory\CreateMemoryRequest;
use App\Http\Requests\Memory\EditMemoryRequest;
use App\Models\Memory;
use App\Models\Tag;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MemoryController extends Controller
{
    public function index(Request $request): View
    {
        $memories = Memory::whereBelongsTo($request->user())
            ->with('tags')
            ->orderByDesc('start_date')
            ->get();

        return view('memories.index', ['memories' => $memories]);
    }

    public function create(Request $request): View
    {
        $tags = Tag::whereBelongsTo($request->user())->orderBy('display_name')->get();

        return view('memories.create', ['tags' => $tags]);
    }

    public function store(CreateMemoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $memory = new Memory($data);
        $memory->user_id = $request->user()->id;
        $memory->save();
        $memory->tags()->sync($data['tags'] ?? []);

        return redirect(route('memories.show', $memory));
    }

    /** @throws AuthorizationException */
    public function show(Memory $memory): View
    {
        Gate::authorize('view', $memory);

        return view('range', [
            'end' => $memory->end_date,
            'endTime' => $memory->end_time,
            'memory' => $memory,
            'start' => $memory->start_date,
            'startTime' => $memory->start_time,
        ]);
    }

    /** @throws AuthorizationException */
    public function edit(Request $request, Memory $memory): View
    {
        Gate::authorize('update', $memory);

        $tags = Tag::whereBelongsTo($request->user())->orderBy('display_name')->get();

        return view('memories.edit', ['memory' => $memory->load('tags'), 'tags' => $tags]);
    }

    /** @throws AuthorizationException */
    public function update(EditMemoryRequest $request, Memory $memory): RedirectResponse
    {
        Gate::authorize('update', $memory);

        $data = $request->validated();

        $memory->update($data);
        $memory->tags()->sync($data['tags'] ?? []);

        return redirect()->route('memories.show', $memory);
    }

    /** @throws AuthorizationException */
    public function destroy(Memory $memory): RedirectResponse
    {
        Gate::authorize('delete', $memory);

        $memory->delete();

        return redirect()->route('memories.index');
    }
}
