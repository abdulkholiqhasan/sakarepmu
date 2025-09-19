<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<meta name="csrf-token" content="{{ csrf_token() }}">

@php
	// Prefer an explicit page title provided via a Blade section 'title',
	// otherwise fall back to $pageTitle or $title. If still empty and this is
	// the homepage prefer the site tagline. This lets child views define
	// `@section('title', '...')` which will be available to the parent layout
	// when composing the <title>.
	$site = config('app.name');
	$sectionTitle = trim($__env->yieldContent('title'));

	$page = $sectionTitle !== '' ? $sectionTitle : ($pageTitle ?? $title ?? null);

	if (! $page && (request()->routeIs('home') || request()->is('/'))) {
		$page = data_get(config('app'), 'tagline') ?: null;
	}

	$fullTitle = $page ? ($site . ' - ' . $page) : $site;
@endphp

<title>{{ $fullTitle }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
@stack('styles')
