<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

class DashboardResponse implements Jsonable
{
    public string $color;
    protected $events;
    protected $items;
    protected $lines;
    protected $pins;
    protected string $slug;

    public function __construct(string $slug, string $color = '#000000')
    {
        $this->color = $color;
        $this->events = collect();
        $this->items = collect();
        $this->lines = collect();
        $this->pins = collect();
        $this->slug = $slug;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return collect([
            'color' => $this->color,
            'events' => $this->events,
            'items' => $this->items,
            'lines' => $this->lines,
            'pins' => $this->pins,
        ])->toJson();
    }

    public function addEvent($id, Carbon $date, string $title, array $details = [])
    {
        $this->events->add([
            'id' => $this->id($id),
            'date' => $date->toISOString(),
            'title' => $title,
            'icon' => isset($details['icon']) ? $details['icon'] : '',
            'subTitle' => isset($details['subTitle']) ? $details['subTitle'] : '',
            'titleLink' => isset($details['titleLink']) ? $details['titleLink'] : '',
            'subTitleLink' => isset($details['subTitleLink']) ? $details['subTitleLink'] : '',
            'dateLink' => isset($details['dateLink']) ? $details['dateLink'] : '',
        ]);
    }

    public function addItem(string $name, $value, string $icon = '')
    {
        $this->items->add([
            'name' => $name,
            'icon' => $icon,
            'value' => $value,
        ]);
    }

    public function addLine($id, array $cords)
    {
        $this->lines->add([
            'id' => $this->id($id),
            'cords' => $cords,
        ]);
    }

    public function addPin($id, $latitude, $longitude, string $title = '')
    {
        $this->pins->add([
            'id' => $this->id($id),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'title' => $title,
        ]);
    }

    protected function id($id): string
    {
        return $this->slug . '-' . $id;
    }
}
