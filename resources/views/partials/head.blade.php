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
<!-- NOTE: Do not load Alpine.js unconditionally via CDN here; multiple instances were detected in some environments. -->
<!-- If you need Alpine in pages, prefer bundling it via Vite or adding it per-page to avoid duplicate instances. -->
<script>
	// Install a global username availability checker to avoid Livewire DOM replacement issues.
	if (!window.__lw_username_check_installed) {
		window.__lw_username_check_installed = true;
		(function(){
			let timeout = null;
			let global = null;

			function ensureGlobal() {
				if (global) return global;
				global = document.createElement('div');
				global.id = 'lw-global-availability';
				global.style.position = 'fixed';
				global.style.right = '1rem';
				global.style.top = '4rem';
				global.style.zIndex = '2147483647';
				global.style.background = 'white';
				global.style.padding = '8px 12px';
				global.style.borderRadius = '6px';
				global.style.boxShadow = '0 6px 18px rgba(0,0,0,0.12)';
				global.style.display = 'none';
				global.style.color = '#0f172a';
				document.addEventListener('DOMContentLoaded', function(){ document.body.appendChild(global); });
				return global;
			}

			let lastTarget = null;
			function doCheck(val, target) {
				const g = ensureGlobal();
				lastTarget = target || lastTarget;
				if (!val || val.trim() === '') { g.style.display = 'none'; return; }
				fetch('/username/check?username=' + encodeURIComponent(val), { credentials: 'same-origin' })
					.then(r => r.json())
					.then(json => {
						// Position popover under the target input when present
						if (lastTarget && lastTarget.getBoundingClientRect) {
							const rect = lastTarget.getBoundingClientRect();
							const left = rect.left + window.scrollX;
							const top = rect.bottom + window.scrollY + 6; // small gap
							g.style.left = Math.max(8, left) + 'px';
							g.style.top = top + 'px';
							g.style.maxWidth = Math.min(window.innerWidth - 16, rect.width) + 'px';
						} else {
							// fallback to fixed top-right
							g.style.left = '';
							g.style.right = '1rem';
							g.style.top = '4rem';
						}

						// Helper: robustly find an inline availability container near the target
						function findInlineContainer(target) {
							if (!target) return null;
							try {
								// 1) Closest data-flux-input wrapper
								const fluxWrap = target.closest('[data-flux-input]');
								if (fluxWrap) {
									const c = fluxWrap.querySelector('.lw-inline-availability');
									if (c) return c;
								}

								// 2) Known wrapper id (username-wrapper)
								const usernameWrapper = target.closest('#username-wrapper');
								if (usernameWrapper) {
									const c = usernameWrapper.querySelector('.lw-inline-availability');
									if (c) return c;
								}

								// 3) Generic form grouping ancestors (common classes)
								const ancestor = target.closest('.flux-input, .field, .form-group, .input-group');
								if (ancestor) {
									const c = ancestor.querySelector('.lw-inline-availability');
									if (c) return c;
								}

								// 4) Search upward for the nearest element that contains an inline container
								let p = target.parentElement;
								for (let i = 0; i < 6 && p; i++) {
									const c = p.querySelector && p.querySelector('.lw-inline-availability');
									if (c) return c;
									p = p.parentElement;
								}

								// 5) Last resort: global username-wrapper
								const fallback = document.querySelector('#username-wrapper .lw-inline-availability');
								if (fallback) return fallback;
							} catch (e) {
								console.error('findInlineContainer error', e);
							}
							return null;
						}

								const inlineContainer = findInlineContainer(lastTarget);
								const wroteInline = Boolean(inlineContainer);
								// clear inline when empty to avoid stale text appearing briefly
								if (!val || val.trim() === '') {
									try { if (inlineContainer) inlineContainer.innerHTML = ''; } catch (e) {}
								}
								if (inlineContainer) {
							try {
								if (json.status === 'available') {
									inlineContainer.innerHTML = `<span class="text-sm text-green-600">Username "${val}" is available.</span>`;
								} else if (json.status === 'taken') {
									const s = json.suggestions || [];
									let formatted = '';
									if (s.length === 1) formatted = s[0];
									else if (s.length === 2) formatted = s[0] + ' or ' + s[1];
									else if (s.length >= 3) formatted = s[0] + ', ' + s[1] + ', or ' + s[2];
									let suggHtml = '';
									if (s.length) {
										suggHtml = `<div class="mt-1 text-zinc-700 text-sm">${formatted} are available.</div>`;
										suggHtml += '<div class="mt-2">';
										s.forEach(function(item){
											suggHtml += `<button data-sugg="${item}" type="button" class="inline-block mr-2 mb-2 px-2 py-1 bg-zinc-100 dark:bg-zinc-800 rounded text-sm text-zinc-800 hover:bg-zinc-200">${item}</button>`;
										});
										suggHtml += '</div>';
									}
									inlineContainer.innerHTML = `<div class="text-sm text-red-600">Username ${val} is not available.</div>` + suggHtml;
								} else if (json.status === 'invalid') {
									inlineContainer.innerHTML = `<div class="text-sm text-red-600">Invalid username. Use letters, numbers, dashes and underscores only.</div>`;
								}
							} catch (e) {
								console.error('write inline error', e);
							}
						}

						// If we didn't write inline, fall back to the floating popover
						if (!wroteInline) {
							if (json.status === 'available') {
								g.innerHTML = `<div style="color:#16a34a">Username "${val}" is available.</div>`;
								g.style.display = '';
							} else if (json.status === 'taken') {
								const s = json.suggestions || [];
								let formatted = '';
								if (s.length === 1) formatted = s[0];
								else if (s.length === 2) formatted = s[0] + ' or ' + s[1];
								else if (s.length >= 3) formatted = s[0] + ', ' + s[1] + ', or ' + s[2];
								let suggHtml = '';
								if (s.length) {
									suggHtml = '<div style="margin-top:6px;color:#0f172a;font-size:0.9em">' + formatted + ' are available.</div>';
									suggHtml += '<div style="margin-top:8px">';
									s.forEach(function(item){
										suggHtml += `<button data-sugg="${item}" type="button" style="margin-right:6px;margin-bottom:6px;padding:6px 8px;border-radius:6px;background:#f3f4f6;border:0;cursor:pointer">${item}</button>`;
									});
									suggHtml += '</div>';
								}
								g.innerHTML = `<div style="color:#dc2626">Username ${val} is not available.</div>` + suggHtml;
								g.style.display = '';
							} else if (json.status === 'invalid') {
								g.innerHTML = `<div style="color:#dc2626">Invalid username. Use letters, numbers, dashes and underscores only.</div>`;
								g.style.display = '';
							} else { g.style.display = 'none'; }
						}
					}).catch(err => { console.error('username check error', err); });
			}

			document.addEventListener('input', function(ev){
				const t = ev.target;
				if (!t || t.tagName !== 'INPUT') return;
				const placeholder = (t.getAttribute('placeholder') || '').toLowerCase();
				const aria = (t.getAttribute('aria-label') || '').toLowerCase();
				const name = (t.getAttribute('name') || '').toLowerCase();
				if (placeholder.includes('username') || aria.includes('username') || name.includes('username') || t.closest('[data-flux-input]')) {
					if (timeout) clearTimeout(timeout);
					timeout = setTimeout(() => doCheck(t.value, t), 250);
				}
			}, { capture: true });

			// Handle suggestion button clicks inside the popover
			document.addEventListener('click', function(ev){
				const btn = ev.target.closest && ev.target.closest('[data-sugg]');
				if (!btn) return;
				const val = btn.getAttribute('data-sugg');
				if (!val) return;
				// populate lastTarget if available, else try to find visible username input
				const target = lastTarget || document.querySelector('input[name="username"]') || document.querySelector('input[placeholder*="username"]');
				if (target) {
					target.focus();
					target.value = val;
					try { target.setAttribute('value', val); } catch (e) {}
					target.dispatchEvent(new Event('input', { bubbles: true }));
					target.dispatchEvent(new Event('change', { bubbles: true }));
					// Immediately re-check for the new value
					doCheck(val, target);
				}
			}, { capture: true });
		})();
	}
</script>
@fluxAppearance
@stack('styles')
