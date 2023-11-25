<?php

namespace App\Http\Requests\Podcasts;

use App\Models\Podcasts\Podcast;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PodcastRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var Podcast $podcast */
        $podcast = $this->route('podcast');
        return $podcast == null || $podcast->created_by === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'name' => [
                'required',
                $request->podcast
                    ? Rule::unique('podcasts')->ignore($request->podcast->id)
                    : 'unique:podcasts,name',
            ],
            'nickname' => 'nullable|max:255',
            'image' => 'nullable|url',
            'website' => 'nullable|url',
            'feed' => 'nullable|url',
        ];
    }
}
