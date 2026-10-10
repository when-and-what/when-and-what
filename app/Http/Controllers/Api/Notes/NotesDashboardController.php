<?php

namespace App\Http\Controllers\Api\Notes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\StoreDashboardRequest;
use App\Http\Responses\DashboardResponse;
use App\Models\Note;
use App\Traits\TaggableController;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class NotesDashboardController extends Controller
{
    use TaggableController;

    public function __invoke(StoreDashboardRequest $request): Response
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
}
