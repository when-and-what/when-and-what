@props(['seconds'])

@php
$minutes = floor($seconds / 60);
$hours = floor($minutes / 60);
if($hours > 0) {
    $minutes = $minutes - ($hours * 60);
}
@endphp

<span class="badge bg-primary">
@if($hours < 0 && $minutes < 0)
    {{ $seconds }} seconds
@else
    @if($hours > 0)
        {{ $hours }}hr
    @endif
    {{ $minutes }}min
@endif
</span>
