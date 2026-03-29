<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\CreateNoteRequest;
use App\Http\Requests\Notes\EditNoteRequest;
use App\Http\Requests\Notes\StoreDashboardRequest;
use App\Http\Responses\DashboardResponse;
use App\Models\Note;
use App\Traits\TaggableController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    use TaggableController;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Note::whereBelongsTo($request->user())
            ->orderBy('published_at', 'DESC')
            ->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNoteRequest $request)
    {
        $note = new Note;
        $note->user_id = $request->user()->id;
        if ($request->published_at) {
            $note->published_at = Carbon::parse(
                $request->published_at,
                $request->user()->timezone
            )->tz('GMT');
        } else {
            $note->published_at = new Carbon;
        }
        $note->fill($request->safe()->all());
        $note->dashboard_visible = $request->boolean('dashboard_visible');
        $note->save();

        return response($note, 201);
    }

    public function storeDashboard(StoreDashboardRequest $request)
    {
        $note = new Note;
        $note->user_id = $request->user()->id;
        if ($request->published_at) {
            $note->published_at = Carbon::parse(
                $request->published_at,
                $request->user()->timezone
            )->tz('GMT');
        } else {
            $note->published_at = now();
        }
        $note->fill($request->safe()->all());
        $note->save();

        $this->saveTags([$note->title, $note->sub_title], $note, $request->user());

        $dashboard = new DashboardResponse('notes');
        $dashboard->addEvent(
            id: $note->id,
            date: $note->published_at,
            title: $note->title,
            details: [
                'icon' => $note->icon,
                'subTitle' => $note->sub_title,
                'dateLink' => route('notes.edit', $note),
            ]
        );

        return response($dashboard, 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        Gate::authorize('view', $note);

        return $note;
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(EditNoteRequest $request, Note $note)
    {
        Gate::authorize('update', $note);

        $note->published_at = Carbon::parse($request->published_at, $request->user()->timezone)->tz(
            'GMT'
        );
        $note->fill($request->safe()->all());
        $note->dashboard_visible = $request->boolean('dashboard_visible');
        $note->save();

        return $note;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        Gate::authorize('delete', $note);

        $note->delete();

        return $note;
    }
}
