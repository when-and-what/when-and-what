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
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('notes.index', [
            // must filter by user id, access not managed by policy class
            'notes' => Note::whereBelongsTo($request->user())
                ->orderBy('published_at', 'desc')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notes.edit', [
            'note' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
        $note->save();

        return redirect(route('notes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        return view('notes.note', [
            'note' => $note,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        return view('notes.edit', [
            'note' => $note,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(EditNoteRequest $request, Note $note)
    {
        $note->published_at = Carbon::parse($request->published_at, $request->user()->timezone)->tz(
            'GMT'
        );
        $note->fill($request->safe()->all());
        $note->save();

        return redirect(route('notes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return redirect(route('notes.index'));
    }
}
