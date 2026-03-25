<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Jsonable;

class DashboardResponse implements Jsonable
{
    public string $color;

    protected $events;

    protected $items;

    protected $lines;

    protected $pins;

    protected string $slug;

    protected bool $collapsible = false;

    protected ?string $groupLabel = null;

    protected ?string $groupIcon = null;

    public function __construct(string $slug, string $color = '#000000')
    {
        $this->color = $color;
        $this->slug = $slug;

        $this->events = collect();
        $this->items = collect();
        $this->lines = collect();
        $this->pins = collect();
    }

    public function collapsible(string $groupLabel, string $groupIcon)
    {
        $this->collapsible = true;
        $this->groupLabel = $groupLabel;
        $this->groupIcon = $groupIcon;
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
            'collapsible' => $this->collapsible,
            'groupLabel' => $this->groupLabel,
            'groupIcon' => $this->groupIcon,
            'events' => $this->events,
            'items' => $this->items,
            'lines' => $this->lines,
            'pins' => $this->pins,
        ])->toJson();
    }

    public function addEvent(int|string $id, Carbon $date, string $title, array $details = [])
    {
        $this->events->add([
            'id' => $this->id($id),
            'date' => $date->toISOString(),
            'title' => $title,
            'icon' => isset($details['icon']) ? $details['icon'] : $this->groupIcon,
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

    protected function id(int|string $id): string
    {
        return $this->slug.'-'.$id;
    }
}
