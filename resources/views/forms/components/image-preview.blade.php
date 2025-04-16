@php
    $image = $getRecord()?->image ?? $getState();
@endphp

@if ($image)
    <img src="{{ $image }}" alt="Image" class="w-1/2">
@endif
