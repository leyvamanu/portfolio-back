@php
    $icon = $getRecord()?->icon ?? $getState();
@endphp

@if ($icon)
    <img src="{{ $icon }}" alt="Icon" class="w-4 h-4 rounded-full">
@endif
