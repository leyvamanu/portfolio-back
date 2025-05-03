@php
    $logo = $getRecord()?->logo ?? $getState();
@endphp

@if ($logo)
    <img src="{{ $logo }}" alt="Logo" class="w-1/2">
@endif
