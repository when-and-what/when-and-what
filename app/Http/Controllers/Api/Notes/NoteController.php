<?php

namespace App\Http\Controllers\Api\Notes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\ApiNoteRequest;
use App\Http\Requests\Notes\IndexNoteRequest;
use App\Models\Note;
use App\Traits\TaggableController;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    use TaggableController;

    public function index(IndexNoteRequest $request): JsonResponse
    {
        $user = $request->user();
        $sort = $request->input('sort', '-published_at');

        $notes = Note::whereBelongsTo($user)
            ->when($request->filled('from'), fn ($query) => $query->where(
                'published_at',
                '>=',
                Carbon::createFromFormat('Y-m-d', $request->input('from'), $user->timezone)->startOfDay()->tz('UTC')
            ))
            ->when($request->filled('to'), fn ($query) => $query->where(
                'published_at',
                '<=',
                Carbon::createFromFormat('Y-m-d', $request->input('to'), $user->timezone)->endOfDay()->tz('UTC')
            ))
            ->when($request->filled('all_day'), fn ($query) => $query->allDay($request->boolean('all_day')))
            ->when($request->filled('dashboard_visible'), fn ($query) => $query->dashboard($request->boolean('dashboard_visible')))
            ->orderBy('published_at', str_starts_with($sort, '-') ? 'desc' : 'asc')
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return response()->json($notes);
    }

    public function store(ApiNoteRequest $request)
    {
        $note = new Note;
        $note->user_id = $request->user()->id;
        $note->fill($request->safe()->except(['published_date', 'published_time']));
        $note->published_at = $request->validated('published_at');
        $note->is_all_day = $request->boolean('is_all_day');
        $note->dashboard_visible = $request->boolean('dashboard_visible');
        $note->save();

        return response()->json($note, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note): JsonResponse
    {
        Gate::authorize('view', $note);

        return response()->json($note);
    }

    public function update(ApiNoteRequest $request, Note $note): JsonResponse
    {
        Gate::authorize('update', $note);

        $note->fill($request->safe()->except(['published_date', 'published_time']));
        $note->published_at = $request->validated('published_at');
        $note->dashboard_visible = $request->boolean('dashboard_visible');
        $note->is_all_day = $request->boolean('is_all_day');
        $note->save();

        return response()->json($note);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note): Response
    {
        Gate::authorize('delete', $note);
        $note->delete();

        return response()->noContent();
    }

    private function parsePublishedAt(Request $request): Carbon
    {
        $tz = $request->user()->timezone;

        if ($request->boolean('is_all_day')) {
            return Carbon::createFromFormat('Y-m-d', $request->input('published_date'), $tz)
                ->startOfDay()
                ->tz('UTC');
        }

        $datetime = $request->input('published_date').'T'.($request->input('published_time') ?? '00:00');

        return Carbon::createFromFormat('Y-m-d\TH:i', $datetime, $tz)->tz('UTC');
    }
}
