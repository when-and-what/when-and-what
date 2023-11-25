<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notes\CreateNoteRequest;
use App\Http\Requests\Notes\EditNoteRequest;
use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Note::class, 'note');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('notes.index', [
            // must filter by user id, access not managed by policy class
            'notes' => Note::whereBelongsTo($request->user())
                ->dashboard(false)
                ->orderBy('published_at', 'desc')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.edit', [
            'note' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNoteRequest $request)
    {
        $note = new Note();
        $note->user_id = $request->user()->id;
        if ($request->published_at) {
            $note->published_at = Carbon::parse(
                $request->published_at,
                $request->user()->timezone
            )->tz('GMT');
        } else {
            $note->published_at = new Carbon();
        }
        $note->fill($request->safe()->all());
        $note->dashboard_visible = $request->boolean('dashboard');
        $note->save();

        return redirect(route('notes.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        return view('notes.note', [
            'note' => $note,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        return view('notes.edit', [
            'note' => $note,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditNoteRequest $request, Note $note)
    {
        $note->published_at = Carbon::parse($request->published_at, $request->user()->timezone)->tz(
            'GMT'
        );
        $note->fill($request->safe()->all());
        $note->dashboard_visible = $request->boolean('dashboard');
        $note->save();

        return redirect(route('notes.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return redirect(route('notes.index'));
    }
}
