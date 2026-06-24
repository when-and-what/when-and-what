<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notes\CreateNoteRequest;
use App\Http\Requests\Notes\EditNoteRequest;
use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Note::class, 'note');
    }

    public function index(Request $request)
    {
        return view('notes.index', [
            'notes' => Note::whereBelongsTo($request->user())
                ->dashboard(false)
                ->orderBy('published_at', 'desc')
                ->get(),
        ]);
    }

    public function create()
    {
        return view('notes.edit', [
            'note' => null,
        ]);
    }

    public function store(CreateNoteRequest $request): RedirectResponse
    {
        $note = new Note;
        $note->user_id = $request->user()->id;
        $note->is_all_day = $request->boolean('is_all_day');
        $note->published_at = $this->parsePublishedAt($request);
        $note->fill($request->safe()->except(['published_date', 'published_time']));
        $note->dashboard_visible = $request->boolean('dashboard_visible');
        $note->save();

        return redirect(route('notes.index'));
    }

    public function show(Note $note)
    {
        return view('notes.note', [
            'note' => $note,
        ]);
    }

    public function edit(Note $note)
    {
        return view('notes.edit', [
            'note' => $note,
        ]);
    }

    public function update(EditNoteRequest $request, Note $note): RedirectResponse
    {
        $note->is_all_day = $request->boolean('is_all_day');
        $note->published_at = $this->parsePublishedAt($request);
        $note->fill($request->safe()->except(['published_date', 'published_time']));
        $note->dashboard_visible = $request->boolean('dashboard_visible');
        $note->save();

        $userTime = $note->published_at->copy()->tz($request->user()->timezone);

        return redirect(route('day', [
            'year' => $userTime->year,
            'month' => $userTime->month,
            'day' => $userTime->day,
        ]));
    }

    public function destroy(Note $note): RedirectResponse
    {
        $note->delete();

        return redirect(route('notes.index'));
    }

    private function parsePublishedAt(Request $request): Carbon
    {
        $tz = $request->user()->timezone;

        if ($request->boolean('is_all_day')) {
            return Carbon::createFromFormat('Y-m-d', $request->input('published_date'), $tz)
                ->startOfDay()
                ->tz('UTC');
        }

        $datetime = $request->input('published_date') . 'T' . ($request->input('published_time') ?? '00:00');

        return Carbon::createFromFormat('Y-m-d\TH:i', $datetime, $tz)->tz('UTC');
    }
}
