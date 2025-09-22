@props(['name' => 'content', 'value' => ''])

<div class="wysiwyg-component">
    <input type="hidden" name="{{ $name }}" id="{{ $name }}-hidden" value="{!! htmlentities($value) !!}" />
    <div id="{{ $name }}-editor" class="min-h-[200px] sm:min-h-[300px] bg-white dark:bg-zinc-900 rounded border dark:border-zinc-700 p-3">
        {!! $value !!}
    </div>
</div>

<!-- Quill CDN styles loaded once; keep script loading dynamic to support Livewire navigation -->
@once
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <style>
            /* Sticky toolbar styles */
            .wysiwyg-component .ql-toolbar {
                transition: box-shadow 150ms ease, background-color 150ms ease;
                position: sticky;
                top: 0;
                z-index: 1050;
                background: white;
                border-radius: 8px 8px 0 0;
                border: 1px solid #e5e7eb;
                border-bottom: 1px solid #d1d5db;
            }
            .wysiwyg-component .ql-toolbar.sticky {
                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                z-index: 1060;
            }
            
            /* Dark mode sticky toolbar */
            .dark .wysiwyg-component .ql-toolbar {
                background: rgb(39 39 42);
                border-color: rgb(63 63 70);
            }
            .dark .wysiwyg-component .ql-toolbar.sticky {
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                background: rgb(39 39 42);
            }
            
            .wysiwyg-toolbar-spacer { height: 0; display: none; }
            .wysiwyg-toolbar-spacer.active { display: none; }
            
            /* Enhanced Mobile toolbar optimizations */
            @media (max-width: 640px) {
                .wysiwyg-component {
                    position: relative;
                }
                .wysiwyg-component .ql-toolbar {
                    padding: 10px 8px;
                    overflow-x: auto;
                    white-space: nowrap;
                    scrollbar-width: none;
                    -ms-overflow-style: none;
                    position: relative;
                    z-index: 10;
                }
                .wysiwyg-component .ql-container {
                    position: relative;
                    z-index: 1;
                }
                .wysiwyg-component .ql-toolbar::-webkit-scrollbar {
                    display: none;
                }
                .wysiwyg-component .ql-toolbar .ql-formats {
                    margin-right: 12px;
                    display: inline-block;
                }
                .wysiwyg-component .ql-toolbar button {
                    padding: 8px;
                    margin: 0 2px;
                    min-width: 32px;
                    min-height: 32px;
                    border-radius: 4px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                }
                .wysiwyg-component .ql-toolbar button svg {
                    width: 16px;
                    height: 16px;
                    flex-shrink: 0;
                }
                
                /* Fix icon size consistency for all toolbar elements */
                .wysiwyg-component .ql-toolbar button svg {
                    width: 16px !important;
                    height: 16px !important;
                }
                
                /* Fix all Quill icons including pseudo-elements */
                .wysiwyg-component .ql-toolbar button {
                    font-size: 16px !important;
                    line-height: 1 !important;
                }
                .wysiwyg-component .ql-toolbar button::before {
                    font-size: 16px !important;
                    line-height: 1 !important;
                }
                
                /* Specific fixes for icons after list items */
                .wysiwyg-component .ql-toolbar .ql-align svg,
                .wysiwyg-component .ql-toolbar .ql-link svg,
                .wysiwyg-component .ql-toolbar .ql-image svg,
                .wysiwyg-component .ql-toolbar .ql-clean svg {
                    width: 16px !important;
                    height: 16px !important;
                }
                
                /* Ensure consistent button sizing */
                .wysiwyg-component .ql-toolbar button {
                    min-width: 32px !important;
                    min-height: 32px !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                }
                
                /* Fix Quill's specific icon classes */
                .wysiwyg-component .ql-toolbar .ql-stroke {
                    stroke-width: 1.5;
                    stroke: currentColor;
                }
                .wysiwyg-component .ql-toolbar .ql-fill {
                    stroke-width: 0;
                    fill: currentColor;
                }
                
                /* Ensure Quill pseudo-element icons work properly */
                .wysiwyg-component .ql-toolbar button::before {
                    font-size: 14px;
                    line-height: 1;
                }
                
                /* Remove problematic SVG overrides that break Quill icons */
                .wysiwyg-component .ql-toolbar .ql-picker-label svg {
                    /* Let Quill handle these naturally */
                }
                
                /* Clean dropdown arrow */
                .wysiwyg-component .ql-toolbar .ql-picker-label::after {
                    content: '';
                    width: 0;
                    height: 0;
                    border-left: 4px solid transparent;
                    border-right: 4px solid transparent;
                    border-top: 4px solid #6b7280;
                    margin-left: 8px;
                    transition: transform 0.15s ease;
                }
                .wysiwyg-component .ql-toolbar .ql-picker.ql-expanded .ql-picker-label::after {
                    transform: rotate(180deg);
                }
                
                /* Ensure consistent icon styling for all buttons on mobile */
                .wysiwyg-component .ql-toolbar .ql-stroke {
                    stroke-width: 1.5;
                }
                .wysiwyg-component .ql-toolbar .ql-fill {
                    stroke-width: 0;
                }
                
                /* Override only for custom icons, let Quill handle defaults */
                .wysiwyg-component .ql-toolbar .ql-table svg,
                .wysiwyg-component .ql-toolbar .ql-video svg,
                .wysiwyg-component .ql-toolbar .ql-html svg {
                    width: 16px !important;
                    height: 16px !important;
                }
                
                /* Clean dropdown styling - consistent across devices */
                .wysiwyg-component .ql-toolbar .ql-picker {
                    font-size: 14px;
                    min-width: 50px;
                    height: 32px;
                    display: inline-flex;
                    align-items: center;
                    position: relative;
                }
                .wysiwyg-component .ql-toolbar .ql-picker-label {
                    padding: 6px 8px;
                    border: 1px solid #d1d5db;
                    border-radius: 6px;
                    background: white;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    min-height: 30px;
                    min-width: 50px;
                    max-width: 80px;
                    font-size: 13px;
                    line-height: 1.2;
                    color: #374151;
                    cursor: pointer;
                    transition: all 0.15s ease;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
                .wysiwyg-component .ql-toolbar .ql-picker-label:hover {
                    border-color: #9ca3af;
                    background: #f9fafb;
                }
                .wysiwyg-component .ql-toolbar .ql-picker.ql-expanded .ql-picker-label {
                    border-color: #3b82f6;
                    box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.1);
                }
                .wysiwyg-component .ql-toolbar .ql-picker-options {
                    position: absolute;
                    top: 100%;
                    left: 0;
                    z-index: 1000;
                    margin-top: 2px;
                    background: white;
                    border: 1px solid #d1d5db;
                    border-radius: 6px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    max-height: 200px;
                    overflow-y: auto;
                    min-width: 120px;
                    width: auto;
                }
                
                /* Mobile specific dropdown positioning */
                @media (max-width: 640px) {
                    .wysiwyg-component .ql-toolbar .ql-picker-options {
                        position: fixed;
                        z-index: 9999;
                        max-height: 60vh;
                        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
                    }
                }
                .wysiwyg-component .ql-toolbar .ql-picker-item {
                    padding: 6px 10px;
                    font-size: 13px;
                    line-height: 1.4;
                    color: #374151;
                    cursor: pointer;
                    transition: background-color 0.15s ease;
                    white-space: nowrap;
                }
                .wysiwyg-component .ql-toolbar .ql-picker-item:hover {
                    background: #f3f4f6;
                }
                .wysiwyg-component .ql-toolbar .ql-picker-item.ql-selected {
                    background: #eff6ff;
                    color: #1d4ed8;
                }
                .wysiwyg-component .ql-editor {
                    padding: 16px 12px;
                    font-size: 16px; /* Prevents zoom on iOS */
                    line-height: 1.6;
                    min-height: 200px;
                }
            }
            
            /* Table styles */
            .wysiwyg-component .ql-editor table { width: 100%; border-collapse: collapse; margin: .5rem 0; }
            .wysiwyg-component .ql-editor table td,
            .wysiwyg-component .ql-editor table th { border: 1px solid rgba(0,0,0,0.12); padding: .5rem; vertical-align: top; }
            .wysiwyg-component .ql-editor .ql-table { max-width: 100%; overflow: auto; display: block; }
            .wysiwyg-component .ql-table-popup.hidden { display: none; }
            .wysiwyg-component .ql-table-popup { display: block; }
            .wysiwyg-component .ql-table-embed { display: block; }
            .wysiwyg-component .ql-table-embed table { width: 100%; border-collapse: collapse; }
            .wysiwyg-component .ql-table-embed td, .wysiwyg-component .ql-table-embed th { border: 1px solid rgba(0,0,0,0.12); padding: .5rem; }
            .wysiwyg-component .ql-table-embed thead th { background: rgba(0,0,0,0.04); font-weight: 600; }
            
            /* Dark mode styling (class-based) */
            .dark .wysiwyg-component .ql-toolbar {
                background: rgb(39 39 42);
                border-color: rgb(63 63 70);
                border-bottom-color: rgb(82 82 91);
            }
            .dark .wysiwyg-component .ql-toolbar button {
                color: rgb(212 212 216);
            }
            .dark .wysiwyg-component .ql-toolbar button:hover {
                background: rgb(63 63 70);
                color: white;
            }
            .dark .wysiwyg-component .ql-toolbar button.ql-active {
                background: rgb(59 130 246);
                color: white;
            }
            .dark .wysiwyg-component .ql-toolbar .ql-stroke {
                stroke: rgb(212 212 216);
            }
            .dark .wysiwyg-component .ql-toolbar .ql-fill {
                fill: rgb(212 212 216);
            }
            .dark .wysiwyg-component .ql-toolbar button:hover .ql-stroke,
            .dark .wysiwyg-component .ql-toolbar button.ql-active .ql-stroke {
                stroke: white;
            }
            .dark .wysiwyg-component .ql-toolbar button:hover .ql-fill,
            .dark .wysiwyg-component .ql-toolbar button.ql-active .ql-fill {
                fill: white;
            }
            
            /* Custom toolbar buttons (table, video, html) dark mode styling */
            .dark .wysiwyg-component .ql-toolbar button svg {
                stroke: rgb(212 212 216) !important;
                color: rgb(212 212 216) !important;
            }
            .dark .wysiwyg-component .ql-toolbar button:hover svg {
                stroke: white !important;
                color: white !important;
            }
            .dark .wysiwyg-component .ql-toolbar button.ql-active svg {
                stroke: white !important;
                color: white !important;
            }
            
            /* Mobile dark mode icon adjustments */
            @media (max-width: 640px) {
                .dark .wysiwyg-component .ql-toolbar button svg {
                    stroke: rgb(212 212 216) !important;
                    color: rgb(212 212 216) !important;
                    opacity: 0.9;
                }
                .dark .wysiwyg-component .ql-toolbar button:hover svg,
                .dark .wysiwyg-component .ql-toolbar button:active svg {
                    stroke: white !important;
                    color: white !important;
                    opacity: 1;
                }
                
                /* Clean dark mode picker styling */
                .dark .wysiwyg-component .ql-toolbar .ql-picker-label {
                    background: rgb(39 39 42);
                    border-color: rgb(75 85 99);
                    color: rgb(212 212 216);
                }
                .dark .wysiwyg-component .ql-toolbar .ql-picker-label:hover {
                    background: rgb(55 65 81);
                    border-color: rgb(107 114 128);
                }
                .dark .wysiwyg-component .ql-toolbar .ql-picker.ql-expanded .ql-picker-label {
                    border-color: rgb(59 130 246);
                    box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.2);
                }
                .dark .wysiwyg-component .ql-toolbar .ql-picker-label::after {
                    border-top-color: rgb(156 163 175);
                }
                .dark .wysiwyg-component .ql-toolbar .ql-picker-options {
                    background: rgb(39 39 42);
                    border-color: rgb(75 85 99);
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                }
                .dark .wysiwyg-component .ql-toolbar .ql-picker-item {
                    color: rgb(212 212 216);
                }
                .dark .wysiwyg-component .ql-toolbar .ql-picker-item:hover {
                    background: rgb(55 65 81);
                }
                .dark .wysiwyg-component .ql-toolbar .ql-picker-item.ql-selected {
                    background: rgb(30 58 138);
                    color: rgb(219 234 254);
                }
                
                /* Dark mode mobile dropdown */
                @media (max-width: 640px) {
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-options {
                        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
                    }
                }
            }
            
            .dark .wysiwyg-component .ql-toolbar .ql-picker {
                color: rgb(212 212 216);
            }
            .dark .wysiwyg-component .ql-toolbar .ql-picker-options {
                background: rgb(39 39 42);
                border-color: rgb(63 63 70);
            }
            .dark .wysiwyg-component .ql-toolbar .ql-picker-item {
                color: rgb(212 212 216);
            }
            .dark .wysiwyg-component .ql-toolbar .ql-picker-item:hover {
                background: rgb(63 63 70);
                color: white;
            }
            .dark .wysiwyg-component .ql-editor {
                color: rgb(212 212 216);
                background: rgb(24 24 27);
                border-color: rgb(63 63 70);
            }
            .dark .wysiwyg-component .ql-editor.ql-blank::before {
                color: rgb(113 113 122);
            }
            
            /* Dark mode content styling */
            .dark .wysiwyg-component .ql-editor blockquote {
                border-left-color: rgb(82 82 91);
                background: rgba(255, 255, 255, 0.02);
                color: rgb(212 212 216);
            }
            .dark .wysiwyg-component .ql-editor code {
                background: rgba(255, 255, 255, 0.08);
                color: rgb(228 228 231);
            }
            .dark .wysiwyg-component .ql-editor pre.ql-syntax {
                background: rgba(255, 255, 255, 0.04);
                color: rgb(212 212 216);
                border-color: rgb(63 63 70);
            }
            
            /* Dark mode table styles */
            .dark .wysiwyg-component .ql-table-embed table,
            .dark .wysiwyg-component .ql-editor table { 
                border-color: rgba(255,255,255,0.12); 
            }
            .dark .wysiwyg-component .ql-table-embed td,
            .dark .wysiwyg-component .ql-editor td,
            .dark .wysiwyg-component .ql-table-embed th,
            .dark .wysiwyg-component .ql-editor th { 
                border-color: rgba(255,255,255,0.12); 
                color: rgba(255,255,255,0.92); 
                background: transparent; 
            }
            .dark .wysiwyg-component .ql-table-embed thead th,
            .dark .wysiwyg-component .ql-editor thead th { 
                background: rgba(255,255,255,0.04); 
                color: rgba(255,255,255,0.96); 
                font-weight: 700; 
            }
            
            /* Legacy dark mode support (prefers-color-scheme) */
            @media (prefers-color-scheme: dark) {
                .wysiwyg-component .ql-toolbar {
                    background: rgb(39 39 42);
                }
                .wysiwyg-component .ql-table-embed table,
                .wysiwyg-component .ql-editor table { border-color: rgba(255,255,255,0.12); }
                .wysiwyg-component .ql-table-embed td,
                .wysiwyg-component .ql-editor td,
                .wysiwyg-component .ql-table-embed th,
                .wysiwyg-component .ql-editor th { border-color: rgba(255,255,255,0.12); color: rgba(255,255,255,0.92); background: transparent; }
                .wysiwyg-component .ql-table-embed thead th,
                .wysiwyg-component .ql-editor thead th { background: rgba(255,255,255,0.04); color: rgba(255,255,255,0.96); font-weight: 700; }
            }
        </style>
    @endpush
@endonce

@push('scripts')
<script>
    (function(){
        // Helper: load external script once and cache the promise
        function loadScriptOnce(src){
            window.__loadScriptPromises = window.__loadScriptPromises || {};
            if(window.__loadScriptPromises[src]) return window.__loadScriptPromises[src];
            window.__loadScriptPromises[src] = new Promise(function(resolve, reject){
                var s = document.createElement('script');
                s.src = src;
                s.async = true;
                s.onload = function(){ resolve(); };
                s.onerror = function(e){ reject(e); };
                document.head.appendChild(s);
            });
            return window.__loadScriptPromises[src];
        }

        function ensureQuill(){
            if(typeof Quill !== 'undefined') return Promise.resolve();
            return loadScriptOnce('https://cdn.quilljs.com/1.3.6/quill.min.js').then(function(){
                return new Promise(function(res){ setTimeout(res, 10); });
            });
        }

        function initEditorFor(name){
            const editorEl = document.getElementById(name + '-editor');
            const hidden = document.getElementById(name + '-hidden');
            if(!editorEl || !hidden) return;

            if(editorEl.__quill) return;

            const toolbarOptions = [
                [{ 'header': [2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image', 'clean']
            ];

            const quill = new Quill(editorEl, {
                theme: 'snow',
                modules: { toolbar: toolbarOptions }
            });
            editorEl.__quill = quill;

            // Mobile dropdown positioning fix
            if(window.innerWidth <= 640) {
                setTimeout(function() {
                    const pickers = editorEl.parentNode.querySelectorAll('.ql-picker');
                    pickers.forEach(function(picker) {
                        const label = picker.querySelector('.ql-picker-label');
                        const options = picker.querySelector('.ql-picker-options');
                        
                        if(label && options) {
                            label.addEventListener('click', function() {
                                setTimeout(function() {
                                    if(picker.classList.contains('ql-expanded')) {
                                        const rect = label.getBoundingClientRect();
                                        const viewportHeight = window.innerHeight;
                                        const optionsHeight = Math.min(options.scrollHeight, parseInt(window.innerHeight * 0.6));
                                        
                                        // Calculate positioning
                                        let top = rect.bottom + 4;
                                        if(top + optionsHeight > viewportHeight - 20) {
                                            top = rect.top - optionsHeight - 4;
                                        }
                                        
                                        // Apply positioning
                                        options.style.position = 'fixed';
                                        options.style.left = rect.left + 'px';
                                        options.style.top = Math.max(10, top) + 'px';
                                        options.style.width = Math.max(rect.width, 120) + 'px';
                                        options.style.maxHeight = optionsHeight + 'px';
                                        options.style.zIndex = '9999';
                                    }
                                }, 0);
                            });
                        }
                    });
                }, 100);
            }

            // Register TableBlot (embed raw table HTML) once
            try {
                const BlockEmbed = Quill.import && Quill.import('blots/block/embed');
                if(BlockEmbed && !window.__quill_tableBlot_registered){
                    class TableBlot extends BlockEmbed {
                        static create(value){
                            const node = super.create();
                            node.innerHTML = value;
                            node.setAttribute('data-table-embed', 'true');
                            node.contentEditable = true;
                            return node;
                        }
                        static value(node){ return node.innerHTML; }
                    }
                    TableBlot.blotName = 'tableBlot';
                    TableBlot.tagName = 'div';
                    TableBlot.className = 'ql-table-embed';
                    Quill.register(TableBlot);
                    window.__quill_tableBlot_registered = true;
                }
            } catch(e){ /* ignore */ }

            // --- Add toolbar custom buttons and popups (table, video, html) ---
            (function(){
                const toolbarModule = quill.getModule && quill.getModule('toolbar');
                const container = toolbarModule && toolbarModule.container ? toolbarModule.container : editorEl.parentNode.querySelector('.ql-toolbar');
                if(!container) return;

                // Table button
                if(!container.querySelector('.ql-table')){
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'ql-table';
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><path d="M3 9h18"/><path d="M9 3v18"/></svg>';
                    const preferredTargets = ['ql-blockquote', 'ql-code-block', 'ql-bold', 'ql-italic'];
                    let inserted = false;
                    for(const cls of preferredTargets){
                        const target = container.querySelector('.' + cls);
                        if(target && target.parentNode){ target.parentNode.insertBefore(btn, target.nextSibling); inserted = true; break; }
                    }
                    if(!inserted){ const formats = container.querySelector('.ql-formats') || container; formats.appendChild(btn); }
                }

                // Table popup
                let popup = document.querySelector('.ql-table-popup[data-editor="' + name + '"]');
                if(!popup){
                    popup = document.createElement('div');
                    popup.className = 'ql-table-popup hidden p-2 bg-white dark:bg-zinc-800 border rounded shadow';
                    popup.setAttribute('data-editor', name);
                    popup.style.minWidth = '200px'; popup.style.position = 'absolute'; popup.style.zIndex = 9999;
                    popup.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <label class="text-xs">Rows</label>
                            <input type="number" min="1" value="2" class="ql-table-rows bg-gray-50 dark:bg-zinc-900 p-1 rounded w-16 text-xs" />
                            <label class="text-xs">Cols</label>
                            <input type="number" min="1" value="2" class="ql-table-cols bg-gray-50 dark:bg-zinc-900 p-1 rounded w-16 text-xs" />
                        </div>
                        <div class="mt-2 flex items-center space-x-2">
                            <input type="checkbox" id="ql-table-header-` + name + `" class="ql-table-header" />
                            <label for="ql-table-header-` + name + `" class="text-xs">Header row</label>
                        </div>
                        <div class="mt-2 flex justify-end space-x-2">
                            <button type="button" class="ql-table-insert px-2 py-1 rounded bg-blue-600 text-white text-sm">Insert</button>
                            <button type="button" class="ql-table-cancel px-2 py-1 rounded border text-sm">Cancel</button>
                        </div>
                    `;
                    document.body.appendChild(popup);
                }

                // Video button & popup
                if(!container.querySelector('.ql-video')){
                    const vbtn = document.createElement('button'); vbtn.type='button'; vbtn.className='ql-video';
                    vbtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2" ry="2"/><path d="M10 9l6 3-6 3z"/></svg>';
                    const imgBtn = container.querySelector('.ql-image'); if(imgBtn && imgBtn.parentNode) imgBtn.parentNode.insertBefore(vbtn, imgBtn.nextSibling); else (container.querySelector('.ql-formats')||container).appendChild(vbtn);
                }
                let vpopup = document.querySelector('.ql-video-popup[data-editor="' + name + '"]');
                if(!vpopup){
                    vpopup = document.createElement('div'); vpopup.className='ql-video-popup hidden p-2 bg-white dark:bg-zinc-800 border rounded shadow';
                    vpopup.setAttribute('data-editor', name); vpopup.style.minWidth='260px'; vpopup.style.position='absolute'; vpopup.style.zIndex=9999;
                    vpopup.innerHTML = `
                        <div class="flex flex-col space-y-2">
                            <label class="text-xs">Video URL</label>
                            <input type="text" class="ql-video-url bg-gray-50 dark:bg-zinc-900 p-1 rounded text-sm" placeholder="https://www.youtube.com/watch?v=..." />
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" class="ql-video-iframe" id="ql-video-iframe-` + name + `" />
                                <label for="ql-video-iframe-` + name + `" class="text-xs">Paste iframe HTML</label>
                            </div>
                        </div>
                        <div class="mt-2 flex justify-end space-x-2">
                            <button type="button" class="ql-video-insert px-2 py-1 rounded bg-blue-600 text-white text-sm">Insert</button>
                            <button type="button" class="ql-video-cancel px-2 py-1 rounded border text-sm">Cancel</button>
                        </div>
                    `;
                    document.body.appendChild(vpopup);
                }

                // Video handlers
                const videoBtn = container.querySelector('.ql-video');
                if(videoBtn){
                    videoBtn.addEventListener('click', function(e){ e.preventDefault(); const btnRect = videoBtn.getBoundingClientRect(); const left = Math.max(8, btnRect.left + window.scrollX); const top = btnRect.bottom + window.scrollY + 6; vpopup.style.left = left + 'px'; vpopup.style.top = top + 'px'; vpopup.classList.toggle('hidden'); vpopup.querySelector('.ql-video-url').focus(); });
                    document.addEventListener('click', function(ev){ if(vpopup.classList.contains('hidden')) return; if(ev.target === videoBtn || vpopup.contains(ev.target) || container.contains(ev.target)) return; vpopup.classList.add('hidden'); });
                    vpopup.querySelector('.ql-video-insert').addEventListener('click', function(){
                        let url = vpopup.querySelector('.ql-video-url').value.trim(); const useIframe = !!vpopup.querySelector('.ql-video-iframe').checked; if(!url) return;
                        try { const ytShort = url.match(/https?:\/\/youtu\.be\/(.+)(\?.*)?$/i); if(ytShort) url = 'https://www.youtube.com/embed/' + ytShort[1]; const ytWatch = url.match(/[?&]v=([A-Za-z0-9_-]{6,})/i) || url.match(/https?:\/\/www\.youtube\.com\/watch\/(.+)$/i); if(ytWatch && ytWatch[1]) url = 'https://www.youtube.com/embed/' + ytWatch[1]; const vimeo = url.match(/https?:\/\/vimeo\.com\/(\d+)/i); if(vimeo && vimeo[1]) url = 'https://player.vimeo.com/video/' + vimeo[1]; } catch(e){}
                        try { const BlockEmbed = Quill.import && Quill.import('blots/block/embed'); if(BlockEmbed && !window.__quill_videoBlot_registered){ class VideoBlot extends BlockEmbed { static create(value){ const node = super.create(); node.innerHTML = value; node.setAttribute('data-video-embed', 'true'); node.contentEditable = false; return node; } static value(node){ return node.innerHTML; } } VideoBlot.blotName='videoBlot'; VideoBlot.tagName='div'; VideoBlot.className='ql-video-embed'; Quill.register(VideoBlot); window.__quill_videoBlot_registered = true; } } catch(e){}
                        const range = quill.getSelection(true) || { index: quill.getLength() };
                        try { if(useIframe){ if(window.__quill_videoBlot_registered){ quill.insertEmbed(range.index, 'videoBlot', url, Quill.sources.USER); quill.insertText(range.index + 1, '\n', Quill.sources.SILENT); quill.setSelection(range.index + 2, Quill.sources.SILENT); } else { quill.root.insertAdjacentHTML('beforeend', url); } } else { quill.insertEmbed(range.index, 'video', url, Quill.sources.USER); quill.setSelection(range.index + 1, Quill.sources.SILENT); } } catch(e){ quill.root.insertAdjacentHTML('beforeend', useIframe ? url : '<iframe src="' + url + '"></iframe>'); }
                        vpopup.classList.add('hidden');
                    });
                    vpopup.querySelector('.ql-video-cancel').addEventListener('click', function(){ vpopup.classList.add('hidden'); });
                }

                // HTML button & popup
                if(!container.querySelector('.ql-html')){ const hbtn = document.createElement('button'); hbtn.type='button'; hbtn.className='ql-html'; hbtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 18l6-6-6-6"/><path d="M8 6l-6 6 6 6"/></svg>'; (container.querySelector('.ql-formats')||container).appendChild(hbtn); }
                let hpopup = document.querySelector('.ql-html-popup[data-editor="' + name + '"]');
                if(!hpopup){ hpopup = document.createElement('div'); hpopup.className='ql-html-popup hidden p-2 bg-white dark:bg-zinc-800 border rounded shadow'; hpopup.setAttribute('data-editor', name); hpopup.style.minWidth='320px'; hpopup.style.position='absolute'; hpopup.style.zIndex=9999; hpopup.innerHTML = `
                        <div class="flex flex-col space-y-2">
                            <label class="text-xs">Paste HTML / iframe</label>
                            <textarea class="ql-html-content bg-gray-50 dark:bg-zinc-900 p-1 rounded text-sm" rows="6" placeholder="Paste iframe or HTML here"></textarea>
                        </div>
                        <div class="mt-2 flex justify-end space-x-2">
                            <button type="button" class="ql-html-insert px-2 py-1 rounded bg-blue-600 text-white text-sm">Insert</button>
                            <button type="button" class="ql-html-cancel px-2 py-1 rounded border text-sm">Cancel</button>
                        </div>
                    `; document.body.appendChild(hpopup); }
                const htmlBtn = container.querySelector('.ql-html'); if(htmlBtn){ htmlBtn.addEventListener('click', function(e){ e.preventDefault(); const btnRect = htmlBtn.getBoundingClientRect(); const left = Math.max(8, btnRect.left + window.scrollX); const top = btnRect.bottom + window.scrollY + 6; hpopup.style.left = left + 'px'; hpopup.style.top = top + 'px'; hpopup.classList.toggle('hidden'); hpopup.querySelector('.ql-html-content').focus(); });
                    document.addEventListener('click', function(ev){ if(hpopup.classList.contains('hidden')) return; if(ev.target === htmlBtn || hpopup.contains(ev.target) || container.contains(ev.target)) return; hpopup.classList.add('hidden'); });
                    hpopup.querySelector('.ql-html-insert').addEventListener('click', function(){ const html = hpopup.querySelector('.ql-html-content').value.trim(); if(!html) return; try { const BlockEmbed = Quill.import && Quill.import('blots/block/embed'); if(BlockEmbed && !window.__quill_htmlBlot_registered){ class HtmlBlot extends BlockEmbed { static create(value){ const node = super.create(); node.innerHTML = value; node.setAttribute('data-html-embed', 'true'); node.contentEditable = false; return node; } static value(node){ return node.innerHTML; } } HtmlBlot.blotName='htmlBlot'; HtmlBlot.tagName='div'; HtmlBlot.className='ql-html-embed'; Quill.register(HtmlBlot); window.__quill_htmlBlot_registered = true; } } catch(e){}
                        const range = quill.getSelection(true) || { index: quill.getLength() };
                        try { if(window.__quill_htmlBlot_registered){ quill.insertEmbed(range.index, 'htmlBlot', html, Quill.sources.USER); quill.insertText(range.index + 1, '\n', Quill.sources.SILENT); quill.setSelection(range.index + 2, Quill.sources.SILENT); } else { quill.root.insertAdjacentHTML('beforeend', html); } } catch(e){ quill.root.insertAdjacentHTML('beforeend', html); }
                        hpopup.classList.add('hidden'); });
                    hpopup.querySelector('.ql-html-cancel').addEventListener('click', function(){ hpopup.classList.add('hidden'); }); }

                // Sticky toolbar behavior
                (function(){
                    const toolbarNode = (quill.getModule && quill.getModule('toolbar') && quill.getModule('toolbar').container) || editorEl.parentNode.querySelector('.ql-toolbar');
                    if(!toolbarNode) return;
                    let spacer = toolbarNode.parentNode.querySelector('.wysiwyg-toolbar-spacer');
                    if(!spacer){ spacer = document.createElement('div'); spacer.className = 'wysiwyg-toolbar-spacer'; toolbarNode.parentNode.insertBefore(spacer, toolbarNode.nextSibling); }
                    function setStickyState(sticky){ if(sticky){ toolbarNode.classList.add('sticky'); try{ editorEl.style.paddingTop = toolbarNode.offsetHeight + 'px'; }catch(e){} } else { toolbarNode.classList.remove('sticky'); try{ editorEl.style.paddingTop = ''; }catch(e){} } }
                    try { const observer = new IntersectionObserver(function(entries){ entries.forEach(function(entry){ const isAbove = entry.boundingClientRect.top < 0; setStickyState(isAbove); }); }, { threshold: [0] }); observer.observe(toolbarNode); } catch(e){ function fallback(){ const rect = toolbarNode.getBoundingClientRect(); const editorRect = editorEl.getBoundingClientRect(); setStickyState(rect.top < 0 && (editorRect.bottom > 0)); } window.addEventListener('scroll', function(){ window.requestAnimationFrame(fallback); }, { passive: true }); window.addEventListener('resize', fallback); fallback(); }
                    window.addEventListener('resize', function(){ if(toolbarNode.classList.contains('sticky')){ const editorRect = editorEl.getBoundingClientRect(); toolbarNode.style.left = Math.max(0, editorRect.left + window.scrollX) + 'px'; toolbarNode.style.width = editorRect.width + 'px'; spacer.style.height = toolbarNode.offsetHeight + 'px'; } });
                })();

                const tableBtn = container.querySelector('.ql-table'); if(!tableBtn) return;
                tableBtn.addEventListener('click', function(e){ e.preventDefault(); const btnRect = tableBtn.getBoundingClientRect(); const left = Math.max(8, btnRect.left + window.scrollX); const top = btnRect.bottom + window.scrollY + 6; popup.style.left = left + 'px'; popup.style.top = top + 'px'; popup.classList.toggle('hidden'); popup.querySelector('.ql-table-rows').focus(); });
                document.addEventListener('click', function(ev){ if(popup.classList.contains('hidden')) return; if(ev.target === tableBtn || popup.contains(ev.target) || container.contains(ev.target)) return; popup.classList.add('hidden'); });
                popup.querySelector('.ql-table-insert').addEventListener('click', function(){ const rows = parseInt(popup.querySelector('.ql-table-rows').value) || 0; const cols = parseInt(popup.querySelector('.ql-table-cols').value) || 0; const hasHeader = !!popup.querySelector('.ql-table-header').checked; if(rows <= 0 || cols <= 0) return; let html = '<table class="ql-table" border="1" cellpadding="6">'; if(hasHeader){ html += '<thead><tr>'; for(let c=0;c<cols;c++){ html += '<th><strong>Header</strong></th>'; } html += '</tr></thead>'; } html += '<tbody>'; for(let r=0;r<rows;r++){ html += '<tr>'; for(let c=0;c<cols;c++){ html += '<td><br></td>'; } html += '</tr>'; } html += '</tbody></table><p><br></p>'; const range = quill.getSelection(true); if(range){ try { if(Quill.import && Quill.find && Quill.register && Quill.import('blots/block/embed')){ quill.insertEmbed(range.index, 'tableBlot', html, Quill.sources.USER); quill.insertText(range.index + 1, '\n', Quill.sources.SILENT); quill.setSelection(range.index + 2, Quill.sources.SILENT); } else { const [line] = quill.getLine(range.index); if(line && line.domNode && line.domNode.insertAdjacentHTML){ line.domNode.insertAdjacentHTML('afterend', html); } else { quill.root.insertAdjacentHTML('beforeend', html); } } } catch(e){ quill.root.insertAdjacentHTML('beforeend', html); } } else { quill.root.insertAdjacentHTML('beforeend', html); } popup.classList.add('hidden'); });
                popup.querySelector('.ql-table-cancel').addEventListener('click', function(){ popup.classList.add('hidden'); });
            })();

            // Enter handling inside blockquote
            (function(){ let lastEnterAt = 0; quill.keyboard.addBinding({ key: 13 }, function(range, context) { const formats = quill.getFormat(range); if (!formats.blockquote) return true; const [line] = quill.getLine(range.index); const lineText = (line && line.domNode) ? line.domNode.innerText : ''; const now = Date.now(); const withinDoubleEnterMs = 800; if (lineText.trim() === '' && (now - lastEnterAt) < withinDoubleEnterMs) { quill.formatLine(range.index, 1, 'blockquote', false); setTimeout(() => quill.setSelection(range.index, 0, Quill.sources.SILENT), 0); lastEnterAt = 0; return false; } quill.insertText(range.index, '\n', Quill.sources.USER); quill.setSelection(range.index + 1, Quill.sources.SILENT); lastEnterAt = now; return false; }); })();

            // initial contents from hidden
            try { let initial = hidden.value || ''; if(initial){ initial = initial.replace(/<h1(.*?)>([\s\S]*?)<\/h1>/gi, '<h2$1>$2<\/h2>'); quill.root.innerHTML = initial; } } catch(e){}

            const form = editorEl.closest('form'); if(form){ form.addEventListener('submit', function(){ let html = quill.root.innerHTML || ''; html = html.replace(/<h1(.*?)>([\s\S]*?)<\/h1>/gi, '<h2$1>$2<\/h2>'); hidden.value = html; }); }
            quill.on('text-change', function(){ let html = quill.root.innerHTML || ''; html = html.replace(/<h1(.*?)>([\s\S]*?)<\/h1>/gi, '<h2$1>$2<\/h2>'); hidden.value = html; });
        }

        function initAll(){
            ensureQuill().then(function(){ if(typeof Quill === 'undefined') return; document.querySelectorAll('.wysiwyg-component').forEach(function(wrapper){ const editor = wrapper.querySelector('[id$="-editor"]'); if(!editor) return; const name = editor.id.replace(/-editor$/, ''); try{ initEditorFor(name); }catch(e){ console.error('WYSIWYG init error', e); } }); }).catch(function(err){ console.error('Failed to load Quill editor script', err); });
        }

        if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', initAll); } else { initAll(); }
        if (window.livewire) { window.livewire.on('message.processed', initAll); }
        document.addEventListener('livewire:load', initAll);
        document.addEventListener('livewire:message.processed', initAll);
        document.addEventListener('turbo:load', initAll);
    })();
</script>
@endpush
