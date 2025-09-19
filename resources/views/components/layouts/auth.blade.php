@props(['title' => null])

@php
    // When an auth layout is used we forward the optional title as $pageTitle
    // so the head partial can render "Site Title — Page Title".
    $pageTitle = $title;
@endphp

<x-layouts.auth.split :title="$title ?? null">
    {{ $slot }}
</x-layouts.auth.split>
