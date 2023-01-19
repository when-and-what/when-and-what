<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\StoreDashboardRequest;
use App\Http\Responses\DashboardResponse;
use App\Models\Note;
use App\Traits\TaggableController;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    use TaggableController;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $note = new Note();
        $note->user_id = $request->user()->id;
    }

    public function storeDashboard(StoreDashboardRequest $request)
    {
        $note = new Note();
        $note->user_id = $request->user()->id;
        if ($request->published_at) {
            $date = new Carbon($request->published_at, $request->user()->timezone);
            $date->setTimezone('UTC');
            $note->published_at = $date;
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
                'dateLink' => route('notes.show', $note),
            ]
        );
        return $dashboard;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        //
    }
}
