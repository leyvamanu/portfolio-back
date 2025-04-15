@php
    $avatar = $getRecord()?->avatar ?? $getState();
@endphp

@if ($avatar)
    <img src="{{ $avatar }}" alt="Avatar" class="w-20 h-20 rounded-full">
@endif
