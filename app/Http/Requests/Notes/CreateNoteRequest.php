<?php

namespace App\Http\Requests\Notes;

use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'icon' => 'nullable|string',
            'title' => 'required|string',
            'sub_title' => 'nullable|string',
            'published_date' => 'required|date_format:Y-m-d',
            'published_time' => 'nullable|date_format:H:i',
            'is_all_day' => ['boolean', 'prohibits:dashboard_visible'],
            'note' => 'nullable|string',
            'dashboard_visible' => 'boolean',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (! $this->boolean('is_all_day')) {
                    return;
                }

                $date = Carbon::createFromFormat('Y-m-d', $this->input('published_date'), $this->user()->timezone)
                    ->startOfDay()
                    ->tz('UTC');

                $exists = Note::whereBelongsTo($this->user())
                    ->where('is_all_day', true)
                    ->whereDate('published_at', $date->toDateString())
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('published_date', 'A day summary already exists for this date.');
                }
            },
        ];
    }
}
