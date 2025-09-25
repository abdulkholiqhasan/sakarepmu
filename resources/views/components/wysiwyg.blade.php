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
        <link href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css" rel="stylesheet">
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
                    padding: 8px;
                    overflow-x: auto;
                    white-space: nowrap;
                    scrollbar-width: none;
                    -ms-overflow-style: none;
                    position: relative;
                    z-index: 10;
                    display: flex;
                    align-items: center;
                    gap: 2px;
                }
                .wysiwyg-component .ql-container {
                    position: relative;
                    z-index: 1;
                }
                .wysiwyg-component .ql-toolbar::-webkit-scrollbar {
                    display: none;
                }
                .wysiwyg-component .ql-toolbar .ql-formats {
                    margin-right: 8px;
                    display: inline-flex;
                    align-items: center;
                    gap: 2px;
                    flex-shrink: 0;
                }
                .wysiwyg-component .ql-toolbar .ql-formats:last-child {
                    margin-right: 0;
                }
                .wysiwyg-component .ql-toolbar button {
                    padding: 6px;
                    margin: 0;
                    min-width: 36px !important;
                    min-height: 36px !important;
                    max-width: 36px;
                    max-height: 36px;
                    border-radius: 6px;
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    flex-shrink: 0;
                    border: 1px solid transparent;
                    background: transparent;
                    transition: all 0.15s ease;
                    -webkit-tap-highlight-color: transparent;
                    touch-action: manipulation;
                }
                .wysiwyg-component .ql-toolbar button:hover {
                    background: rgba(0, 0, 0, 0.05);
                    border-color: rgba(0, 0, 0, 0.1);
                }
                .wysiwyg-component .ql-toolbar button:active {
                    background: rgba(0, 0, 0, 0.1);
                    transform: scale(0.95);
                }
                .wysiwyg-component .ql-toolbar button.ql-active {
                    background: #3b82f6;
                    color: white;
                    border-color: #2563eb;
                }
                .wysiwyg-component .ql-toolbar button svg {
                    width: 18px !important;
                    height: 18px !important;
                    flex-shrink: 0;
                    pointer-events: none;
                }
                
                /* Fix icon size consistency for all toolbar elements */
                .wysiwyg-component .ql-toolbar button {
                    font-size: 16px !important;
                    line-height: 1 !important;
                }
                .wysiwyg-component .ql-toolbar button::before {
                    font-size: 16px !important;
                    line-height: 1 !important;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                /* Picker (dropdown) styling for mobile */
                .wysiwyg-component .ql-toolbar .ql-picker {
                    font-size: 14px;
                    min-width: 50px;
                    max-width: 90px;
                    height: 36px;
                    display: inline-flex;
                    align-items: center;
                    position: relative;
                    flex-shrink: 0;
                }
                .wysiwyg-component .ql-toolbar .ql-picker-label {
                    padding: 4px 8px;
                    border: 1px solid #d1d5db;
                    border-radius: 6px;
                    background: white;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    min-height: 32px;
                    width: 100%;
                    font-size: 12px;
                    line-height: 1.2;
                    color: #374151;
                    cursor: pointer;
                    transition: all 0.15s ease;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    -webkit-tap-highlight-color: transparent;
                    touch-action: manipulation;
                }
                .wysiwyg-component .ql-toolbar .ql-picker-label:hover {
                    border-color: #9ca3af;
                    background: #f9fafb;
                }
                .wysiwyg-component .ql-toolbar .ql-picker-label:active {
                    transform: scale(0.98);
                }
                .wysiwyg-component .ql-toolbar .ql-picker.ql-expanded .ql-picker-label {
                    border-color: #3b82f6;
                    box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.1);
                    background: #eff6ff;
                }
                .wysiwyg-component .ql-toolbar .ql-picker-options {
                    position: fixed;
                    z-index: 9999;
                    margin-top: 4px;
                    background: white;
                    border: 1px solid #d1d5db;
                    border-radius: 8px;
                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
                    max-height: 50vh;
                    overflow-y: auto;
                    min-width: 140px;
                    max-width: 200px;
                }
                .wysiwyg-component .ql-toolbar .ql-picker-item {
                    padding: 8px 12px;
                    font-size: 13px;
                    line-height: 1.4;
                    color: #374151;
                    cursor: pointer;
                    transition: background-color 0.15s ease;
                    white-space: nowrap;
                    -webkit-tap-highlight-color: transparent;
                    touch-action: manipulation;
                }
                .wysiwyg-component .ql-toolbar .ql-picker-item:hover,
                .wysiwyg-component .ql-toolbar .ql-picker-item:active {
                    background: #f3f4f6;
                }
                .wysiwyg-component .ql-toolbar .ql-picker-item.ql-selected {
                    background: #eff6ff;
                    color: #1d4ed8;
                    font-weight: 500;
                }
                
                /* Clean dropdown arrow */
                .wysiwyg-component .ql-toolbar .ql-picker-label::after {
                    content: '';
                    width: 0;
                    height: 0;
                    border-left: 4px solid transparent;
                    border-right: 4px solid transparent;
                    border-top: 5px solid #6b7280;
                    margin-left: 6px;
                    transition: transform 0.15s ease;
                    flex-shrink: 0;
                }
                .wysiwyg-component .ql-toolbar .ql-picker.ql-expanded .ql-picker-label::after {
                    transform: rotate(180deg);
                }
                
                /* Editor text size for mobile */
                .wysiwyg-component .ql-editor {
                    padding: 16px 12px;
                    font-size: 16px; /* Prevents zoom on iOS */
                    line-height: 1.6;
                    min-height: 200px;
                }
            }
            
            /* Desktop toolbar styling - Improved consistency */
            @media (min-width: 641px) {
                .wysiwyg-component .ql-toolbar {
                    padding: 10px 12px;
                    border-radius: 8px 8px 0 0;
                    display: flex;
                    flex-wrap: wrap;
                    align-items: center;
                    gap: 2px;
                    background: white;
                    border: 1px solid #e5e7eb;
                    border-bottom: 1px solid #d1d5db;
                }
                
                /* Format groups with consistent spacing */
                .wysiwyg-component .ql-toolbar .ql-formats {
                    margin-right: 8px;
                    display: inline-flex;
                    align-items: center;
                    gap: 1px;
                    padding: 2px 4px;
                    border-radius: 6px;
                    background: rgba(0, 0, 0, 0.02);
                }
                .wysiwyg-component .ql-toolbar .ql-formats:last-child {
                    margin-right: 0;
                }
                
                /* Consistent button styling - MATCH mobile exactly: 36x36px */
                .wysiwyg-component .ql-toolbar button {
                    padding: 6px !important;
                    margin: 0 !important;
                    width: 36px !important;
                    height: 36px !important;
                    min-width: 36px !important;
                    min-height: 36px !important;
                    max-width: 36px !important;
                    max-height: 36px !important;
                    border-radius: 6px !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    border: 1px solid transparent !important;
                    background: transparent !important;
                    transition: all 0.15s ease !important;
                    cursor: pointer !important;
                    position: relative !important;
                    flex-shrink: 0 !important;
                    box-sizing: border-box !important;
                    font-size: 16px !important;
                    line-height: 1 !important;
                    vertical-align: top !important;
                }
                
                .wysiwyg-component .ql-toolbar button:hover {
                    background: rgba(0, 0, 0, 0.05) !important;
                    border-color: rgba(0, 0, 0, 0.1) !important;
                }
                
                .wysiwyg-component .ql-toolbar button:active {
                    background: rgba(0, 0, 0, 0.1) !important;
                    transform: scale(0.95) !important;
                }
                
                .wysiwyg-component .ql-toolbar button.ql-active {
                    background: #3b82f6 !important;
                    color: white !important;
                    border-color: #2563eb !important;
                }
                
                /* Consistent icon sizing - MATCH mobile exactly: 18x18px */
                .wysiwyg-component .ql-toolbar button svg {
                    width: 18px !important;
                    height: 18px !important;
                    min-width: 18px !important;
                    min-height: 18px !important;
                    max-width: 18px !important;
                    max-height: 18px !important;
                    flex-shrink: 0 !important;
                    pointer-events: none !important;
                    stroke: currentColor !important;
                    fill: none !important;
                    stroke-width: 1.5 !important;
                    stroke-linecap: round !important;
                    stroke-linejoin: round !important;
                }
                
                /* Fix Quill's default icon fonts - MATCH mobile exactly: 18x18px */
                .wysiwyg-component .ql-toolbar button::before {
                    font-size: 16px !important;
                    line-height: 18px !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    width: 18px !important;
                    height: 18px !important;
                    min-width: 18px !important;
                    min-height: 18px !important;
                    max-width: 18px !important;
                    max-height: 18px !important;
                    text-align: center !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    box-sizing: border-box !important;
                    overflow: hidden !important;
                }
                
                /* FORCE all Quill built-in icons to be consistent - 18x18px like mobile */
                .wysiwyg-component .ql-toolbar .ql-bold::before,
                .wysiwyg-component .ql-toolbar .ql-italic::before,
                .wysiwyg-component .ql-toolbar .ql-underline::before,
                .wysiwyg-component .ql-toolbar .ql-strike::before,
                .wysiwyg-component .ql-toolbar .ql-blockquote::before,
                .wysiwyg-component .ql-toolbar .ql-code-block::before,
                .wysiwyg-component .ql-toolbar .ql-list::before,
                .wysiwyg-component .ql-toolbar .ql-link::before,
                .wysiwyg-component .ql-toolbar .ql-image::before,
                .wysiwyg-component .ql-toolbar .ql-clean::before,
                .wysiwyg-component .ql-toolbar .ql-align::before {
                    font-size: 16px !important;
                    line-height: 18px !important;
                    width: 18px !important;
                    height: 18px !important;
                    min-width: 18px !important;
                    min-height: 18px !important;
                    max-width: 18px !important;
                    max-height: 18px !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    text-align: center !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    box-sizing: border-box !important;
                    overflow: hidden !important;
                }
                
                /* Fix header picker text */
                .wysiwyg-component .ql-toolbar .ql-header .ql-picker-label::before {
                    font-size: 12px !important;
                    font-weight: 600 !important;
                    line-height: 1 !important;
                    width: auto !important;
                    height: auto !important;
                }
                
                /* Stroke and fill consistency - OVERRIDE any conflicts */
                .wysiwyg-component .ql-toolbar .ql-stroke {
                    stroke: currentColor !important;
                    stroke-width: 1.5 !important;
                    fill: none !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-fill {
                    fill: currentColor !important;
                    stroke: none !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-even {
                    fill-rule: evenodd !important;
                }
                
                /* Align and List - now individual buttons (NO MORE PICKER) */
                }
                
                /* Align buttons (now individual like other buttons) - NO MORE PICKER */
                .wysiwyg-component .ql-toolbar .ql-align {
                    width: 36px !important;
                    height: 36px !important;
                    min-width: 36px !important;
                    min-height: 36px !important;
                    max-width: 36px !important;
                    max-height: 36px !important;
                }
                
                /* List buttons (individual like align now) */
                .wysiwyg-component .ql-toolbar .ql-list {
                    width: 36px !important;
                    height: 36px !important;
                    min-width: 36px !important;
                    min-height: 36px !important;
                    max-width: 36px !important;
                    max-height: 36px !important;
                }
                
                /* Align and List button icons - consistent sizing */
                .wysiwyg-component .ql-toolbar .ql-align::before,
                .wysiwyg-component .ql-toolbar .ql-list::before {
                    font-size: 16px !important;
                    line-height: 18px !important;
                    width: 18px !important;
                    height: 18px !important;
                    min-width: 18px !important;
                    min-height: 18px !important;
                    max-width: 18px !important;
                    max-height: 18px !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    text-align: center !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    box-sizing: border-box !important;
                    overflow: hidden !important;
                }
                    align-items: center !important;
                    justify-content: center !important;
                }
                
                /* Custom buttons - MATCH mobile exactly: 36x36px */
                .wysiwyg-component .ql-toolbar .ql-table,
                .wysiwyg-component .ql-toolbar .ql-video,
                .wysiwyg-component .ql-toolbar .ql-html {
                    width: 36px !important;
                    height: 36px !important;
                    min-width: 36px !important;
                    min-height: 36px !important;
                    max-width: 36px !important;
                    max-height: 36px !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-table svg,
                .wysiwyg-component .ql-toolbar .ql-video svg,
                .wysiwyg-component .ql-toolbar .ql-html svg {
                    width: 18px !important;
                    height: 18px !important;
                    min-width: 18px !important;
                    min-height: 18px !important;
                    max-width: 18px !important;
                    max-height: 18px !important;
                    stroke: currentColor !important;
                    fill: none !important;
                    stroke-width: 1.5 !important;
                    stroke-linecap: round !important;
                    stroke-linejoin: round !important;
                    stroke-linecap: round !important;
                    stroke-linejoin: round !important;
                }
                /* Ensure custom table/video/html svg parts follow currentColor so dark mode rules apply */
                .wysiwyg-component .ql-toolbar .ql-table svg .ql-stroke,
                .wysiwyg-component .ql-toolbar .ql-video svg .ql-stroke,
                .wysiwyg-component .ql-toolbar .ql-html svg .ql-stroke {
                    stroke: currentColor !important;
                    fill: none !important;
                }
                
                /* Picker styling - MATCH mobile exactly: 36x36px */
                .wysiwyg-component .ql-toolbar .ql-picker {
                    font-size: 14px !important;
                    height: 36px !important;
                    min-height: 36px !important;
                    max-height: 36px !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    position: relative !important;
                    margin: 0 !important;
                    flex-shrink: 0 !important;
                }
                
                /* Regular picker widths for font, size, header */
                .wysiwyg-component .ql-toolbar .ql-font,
                .wysiwyg-component .ql-toolbar .ql-size,
                .wysiwyg-component .ql-toolbar .ql-header {
                    min-width: 60px !important;
                    max-width: 100px !important;
                    width: auto !important;
                }
                
                /* Color and background pickers - exactly 36x36px like buttons */
                .wysiwyg-component .ql-toolbar .ql-color,
                .wysiwyg-component .ql-toolbar .ql-background,
                .wysiwyg-component .ql-toolbar .ql-align {
                    width: 36px !important;
                    height: 36px !important;
                    min-width: 36px !important;
                    min-height: 36px !important;
                    max-width: 36px !important;
                    max-height: 36px !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-picker-label {
                    padding: 6px 8px !important;
                    border: 1px solid #d1d5db !important;
                    border-radius: 6px !important;
                    background: white !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    height: 32px !important;
                    min-height: 32px !important;
                    max-height: 32px !important;
                    width: 100% !important;
                    font-size: 13px !important;
                    line-height: 1.2 !important;
                    color: #374151 !important;
                    cursor: pointer !important;
                    transition: all 0.15s ease !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: nowrap !important;
                    box-sizing: border-box !important;
                }
                
                /* Override for color picker labels only - exactly 32x32px inner */
                .wysiwyg-component .ql-toolbar .ql-color .ql-picker-label,
                .wysiwyg-component .ql-toolbar .ql-background .ql-picker-label {
                    width: 32px !important;
                    height: 32px !important;
                    min-width: 32px !important;
                    min-height: 32px !important;
                    max-width: 32px !important;
                    max-height: 32px !important;
                    padding: 6px !important;
                    justify-content: center !important;
                }
                
                /* SVG icons in pickers - 18x18px like mobile */
                .wysiwyg-component .ql-toolbar .ql-picker-label svg {
                    width: 18px !important;
                    height: 18px !important;
                    min-width: 18px !important;
                    min-height: 18px !important;
                    max-width: 18px !important;
                    max-height: 18px !important;
                    stroke: currentColor !important;
                    fill: none !important;
                    stroke-width: 1.5 !important;
                    stroke-linecap: round !important;
                    stroke-linejoin: round !important;
                }
                
                /* Pseudo-element icons in pickers - 18x18px like mobile */
                .wysiwyg-component .ql-toolbar .ql-picker-label::before {
                    font-size: 16px !important;
                    line-height: 18px !important;
                    width: 18px !important;
                    height: 18px !important;
                    min-width: 18px !important;
                    min-height: 18px !important;
                    max-width: 18px !important;
                    max-height: 18px !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    text-align: center !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    box-sizing: border-box !important;
                    overflow: hidden !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-picker-label:hover {
                    border-color: rgba(0, 0, 0, 0.1) !important;
                    background: rgba(0, 0, 0, 0.05) !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-picker-label:active {
                    transform: scale(0.95) !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-picker.ql-expanded .ql-picker-label {
                    border-color: #3b82f6;
                    box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.2);
                    background: #eff6ff;
                    transform: translateY(-1px);
                }
                
                .wysiwyg-component .ql-toolbar .ql-picker-options {
                    position: absolute;
                    top: 100%;
                    left: 0;
                    z-index: 1000;
                    margin-top: 6px;
                    background: white;
                    border: 1px solid #d1d5db;
                    border-radius: 8px;
                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
                    max-height: 240px;
                    overflow-y: auto;
                    min-width: 140px;
                    backdrop-filter: blur(8px);
                }
                
                .wysiwyg-component .ql-toolbar .ql-picker-item {
                    padding: 8px 12px;
                    font-size: 13px;
                    line-height: 1.4;
                    color: #374151;
                    cursor: pointer;
                    transition: all 0.15s ease;
                    white-space: nowrap;
                    border-radius: 4px;
                    margin: 2px 4px;
                }
                
                .wysiwyg-component .ql-toolbar .ql-picker-item:hover {
                    background: #f3f4f6;
                    transform: translateX(2px);
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }
                
                .wysiwyg-component .ql-toolbar .ql-picker-item.ql-selected {
                    background: #eff6ff;
                    color: #1d4ed8;
                    font-weight: 500;
                    border: 1px solid #3b82f6;
                }
                
                /* Dropdown arrow */
                .wysiwyg-component .ql-toolbar .ql-picker-label::after {
                    content: '';
                    width: 0;
                    height: 0;
                    border-left: 3px solid transparent;
                    border-right: 3px solid transparent;
                    border-top: 4px solid #6b7280;
                    margin-left: 6px;
                    transition: transform 0.2s ease;
                    flex-shrink: 0;
                }
                
                .wysiwyg-component .ql-toolbar .ql-picker.ql-expanded .ql-picker-label::after {
                    transform: rotate(180deg);
                }
                
                /* Color picker positioning and animation improvements */
                .wysiwyg-component .ql-toolbar .ql-picker {
                    position: relative;
                }
                
                .wysiwyg-component .ql-toolbar .ql-picker-options {
                    opacity: 0;
                    visibility: hidden;
                    transform: translateY(-10px) scale(0.95);
                    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                }
                
                .wysiwyg-component .ql-toolbar .ql-picker.ql-expanded .ql-picker-options {
                    opacity: 1;
                    visibility: visible;
                    transform: translateY(0) scale(1);
                }
                
                /* Color picker specific enhancements */
                .wysiwyg-component .ql-color .ql-picker-options,
                .wysiwyg-component .ql-background .ql-picker-options {
                    right: 0;
                    left: auto;
                    min-width: 200px;
                }
                
                /* Add subtle glow effect for active color pickers */
                .wysiwyg-component .ql-toolbar .ql-picker.ql-expanded .ql-picker-label {
                    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2), 0 4px 12px rgba(59, 130, 246, 0.15);
                    background: #eff6ff;
                }
                
                /* Enhanced animation for color pickers */
                .enhanced-picker-animation {
                    animation: colorPickerSlideIn 0.25s cubic-bezier(0.4, 0, 0.2, 1) forwards !important;
                }
                
                @keyframes colorPickerSlideIn {
                    from {
                        opacity: 0;
                        transform: translateY(-15px) scale(0.9);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }
                
                /* ABSOLUTE CONSISTENCY ENFORCEMENT - Override any remaining inconsistencies */
                .wysiwyg-component .ql-toolbar * {
                    box-sizing: border-box !important;
                }
                
                /* Force ALL toolbar buttons to be exactly 36x36 (MATCH mobile) */
                .wysiwyg-component .ql-toolbar button,
                .wysiwyg-component .ql-toolbar .ql-color,
                .wysiwyg-component .ql-toolbar .ql-background,
                .wysiwyg-component .ql-toolbar .ql-table,
                .wysiwyg-component .ql-toolbar .ql-video,
                .wysiwyg-component .ql-toolbar .ql-html,
                .wysiwyg-component .ql-toolbar .ql-formula,
                .wysiwyg-component .ql-toolbar .ql-align,
                .wysiwyg-component .ql-toolbar .ql-list {
                    width: 36px !important;
                    height: 36px !important;
                    min-width: 36px !important;
                    min-height: 36px !important;
                    max-width: 36px !important;
                    max-height: 36px !important;
                    flex-shrink: 0 !important;
                    flex-grow: 0 !important;
                }
                
                /* SPECIAL STYLING for individual align buttons to match their function */
                .wysiwyg-component .ql-toolbar .ql-align[value=""]::before,
                .wysiwyg-component .ql-toolbar button.ql-align[value=""]::before {
                    content: "⬅" !important; /* Left align icon */
                    font-size: 14px !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-align[value="center"]::before,
                .wysiwyg-component .ql-toolbar button.ql-align[value="center"]::before {
                    content: "↔" !important; /* Center align icon */
                    font-size: 14px !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-align[value="right"]::before,
                .wysiwyg-component .ql-toolbar button.ql-align[value="right"]::before {
                    content: "➡" !important; /* Right align icon */
                    font-size: 14px !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-align[value="justify"]::before,
                .wysiwyg-component .ql-toolbar button.ql-align[value="justify"]::before {
                    content: "⬌" !important; /* Justify align icon */
                    font-size: 14px !important;
                }
                
                /* Force ALL icons to be exactly 16x16 */
                .wysiwyg-component .ql-toolbar button svg,
                .wysiwyg-component .ql-toolbar .ql-color svg,
                .wysiwyg-component .ql-toolbar .ql-background svg,
                .wysiwyg-component .ql-toolbar .ql-table svg,
                .wysiwyg-component .ql-toolbar .ql-video svg,
                .wysiwyg-component .ql-toolbar .ql-html svg {
                    width: 16px !important;
                    height: 16px !important;
                    min-width: 16px !important;
                    min-height: 16px !important;
                    max-width: 16px !important;
                    max-height: 16px !important;
                }
                
                /* Force consistent font sizing for pseudo elements */
                .wysiwyg-component .ql-toolbar button::before,
                .wysiwyg-component .ql-toolbar .ql-bold::before,
                .wysiwyg-component .ql-toolbar .ql-italic::before,
                .wysiwyg-component .ql-toolbar .ql-underline::before,
                .wysiwyg-component .ql-toolbar .ql-strike::before,
                .wysiwyg-component .ql-toolbar .ql-blockquote::before,
                .wysiwyg-component .ql-toolbar .ql-code-block::before,
                .wysiwyg-component .ql-toolbar .ql-list::before,
                .wysiwyg-component .ql-toolbar .ql-link::before,
                .wysiwyg-component .ql-toolbar .ql-image::before,
                .wysiwyg-component .ql-toolbar .ql-clean::before {
                    font-size: 16px !important;
                    line-height: 18px !important;
                    width: 18px !important;
                    height: 18px !important;
                }
                
                /* Override any Quill default that might interfere */
                .wysiwyg-component .ql-toolbar .ql-picker:not(.ql-color):not(.ql-background) {
                    width: auto !important;
                    min-width: 75px !important;
                    height: 32px !important;
                }
                
                /* FINAL OVERRIDE - Force align icons to be exactly right size */
                .wysiwyg-component .ql-toolbar .ql-align .ql-picker-label svg,
                .wysiwyg-component .ql-toolbar .ql-align button svg {
                    width: 16px !important;
                    height: 16px !important;
                    stroke-width: 1.5 !important;
                }
                
                /* REMOVED - Align picker styling (now using individual buttons) */
                
                /* Target specific Quill CSS classes that might override */
                .wysiwyg-component .ql-toolbar .ql-stroke {
                    stroke-width: 1.5 !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-fill {
                    stroke: none !important;
                }
                
                /* REMOVED - Align dropdown/picker styling (now using individual buttons) */
                    font-family: "quill-icons" !important;
                    font-size: 14px !important;
                    font-weight: normal !important;
                    font-style: normal !important;
                    line-height: 1 !important;
                    width: 16px !important;
                    height: 16px !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    margin: 0 !important;
                    padding: 0 !important;
                }
                
                /* Target all possible Quill align states */
                .wysiwyg-component .ql-toolbar span.ql-align,
                .wysiwyg-component .ql-toolbar button.ql-align,
                .wysiwyg-component .ql-toolbar .ql-picker.ql-align {
                    font-size: 14px !important;
                }
                
                .wysiwyg-component .ql-toolbar span.ql-align::before,
                .wysiwyg-component .ql-toolbar button.ql-align::before,
                .wysiwyg-component .ql-toolbar .ql-picker.ql-align::before,
                .wysiwyg-component .ql-toolbar .ql-picker.ql-align .ql-picker-label::before {
                    font-size: 14px !important;
                    width: 16px !important;
                    height: 16px !important;
                    line-height: 1 !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                }
                
                /* Custom buttons consistency */
                .wysiwyg-component .ql-toolbar .ql-table,
                .wysiwyg-component .ql-toolbar .ql-video,
                .wysiwyg-component .ql-toolbar .ql-html {
                    width: 32px !important;
                    height: 32px !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-table svg,
                .wysiwyg-component .ql-toolbar .ql-video svg,
                .wysiwyg-component .ql-toolbar .ql-html svg {
                    width: 16px !important;
                    height: 16px !important;
                    stroke: currentColor !important;
                    fill: none !important;
                    stroke-width: 1.5 !important;
                    stroke-linecap: round !important;
                    stroke-linejoin: round !important;
                }
            }
                    justify-content: center !important;
                    border: 1px solid #d1d5db !important;
                    background: white !important;
                    margin: 0 !important;
                    box-sizing: border-box !important;
                    position: relative;
                }

                /* SVG icons standardization */
                .wysiwyg-component .ql-color .ql-picker-label svg,
                .wysiwyg-component .ql-background .ql-picker-label svg,
                .wysiwyg-component .ql-toolbar .ql-color .ql-picker-label svg,
                .wysiwyg-component .ql-toolbar .ql-background .ql-picker-label svg {
                    width: 16px !important;
                    height: 16px !important;
                    min-width: 16px !important;
                    min-height: 16px !important;
                    max-width: 16px !important;
                    max-height: 16px !important;
                    stroke: currentColor !important;
                    fill: none !important;
                    stroke-width: 1.5 !important;
                    stroke-linecap: round !important;
                    stroke-linejoin: round !important;
                }

                /* DARK MODE - Consistent theming (color pickers only) */
                .dark .wysiwyg-component .ql-color .ql-picker-label,
                .dark .wysiwyg-component .ql-background .ql-picker-label,
                .dark .wysiwyg-component .ql-toolbar .ql-color .ql-picker-label,
                .dark .wysiwyg-component .ql-toolbar .ql-background .ql-picker-label {
                    background: rgb(39 39 42) !important;
                    border-color: rgb(75 85 99) !important;
                    color: rgb(212 212 216) !important;
                }

                .dark .wysiwyg-component .ql-color .ql-picker-label:hover,
                .dark .wysiwyg-component .ql-background .ql-picker-label:hover,
                .dark .wysiwyg-component .ql-toolbar .ql-color .ql-picker-label:hover,
                .dark .wysiwyg-component .ql-toolbar .ql-background .ql-picker-label:hover {
                    background: rgb(55 65 81) !important;
                    border-color: rgb(107 114 128) !important;
                    color: white !important;
                }

                .dark .wysiwyg-component .ql-color .ql-picker-label svg,
                .dark .wysiwyg-component .ql-background .ql-picker-label svg,
                .dark .wysiwyg-component .ql-toolbar .ql-color .ql-picker-label svg,
                .dark .wysiwyg-component .ql-toolbar .ql-background .ql-picker-label svg {
                    stroke: rgb(212 212 216) !important;
                }

                .dark .wysiwyg-component .ql-color .ql-picker-label:hover svg,
                .dark .wysiwyg-component .ql-background .ql-picker-label:hover svg,
                .dark .wysiwyg-component .ql-toolbar .ql-color .ql-picker-label:hover svg,
                .dark .wysiwyg-component .ql-toolbar .ql-background .ql-picker-label:hover svg {
                    stroke: white !important;
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
            
            /* Image alignment styles */
            .wysiwyg-component .ql-editor img {
                max-width: 100%;
                height: auto;
                display: block;
                margin: 0.5rem 0;
                border-radius: 4px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                transition: box-shadow 0.2s ease;
            }
            
            .wysiwyg-component .ql-editor img:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
            
            .wysiwyg-component .ql-editor .ql-align-center img,
            .wysiwyg-component .ql-editor p.ql-align-center img {
                margin-left: auto;
                margin-right: auto;
                display: block;
            }
            
            .wysiwyg-component .ql-editor .ql-align-right img,
            .wysiwyg-component .ql-editor p.ql-align-right img {
                margin-left: auto;
                margin-right: 0;
                display: block;
            }
            
            .wysiwyg-component .ql-editor .ql-align-left img,
            .wysiwyg-component .ql-editor p.ql-align-left img {
                margin-left: 0;
                margin-right: auto;
                display: block;
            }
            
            .wysiwyg-component .ql-editor .ql-align-justify img,
            .wysiwyg-component .ql-editor p.ql-align-justify img {
                margin-left: 0;
                margin-right: auto;
                display: block;
            }
            
            /* Mobile responsive image alignment */
            @media (max-width: 640px) {
                .wysiwyg-component .ql-editor img {
                    max-width: 100%;
                    margin: 0.5rem auto;
                }
                
                .wysiwyg-component .ql-editor .ql-align-right img,
                .wysiwyg-component .ql-editor p.ql-align-right img,
                .wysiwyg-component .ql-editor .ql-align-left img,
                .wysiwyg-component .ql-editor p.ql-align-left img {
                    /* On mobile, center all images for better readability */
                    margin-left: auto;
                    margin-right: auto;
                }
            }
            
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
                    .dark .wysiwyg-component .ql-toolbar button {
                        background: transparent;
                        border-color: transparent;
                        color: rgb(212 212 216);
                    }
                    .dark .wysiwyg-component .ql-toolbar button:hover {
                        background: rgba(255, 255, 255, 0.1);
                        border-color: rgba(255, 255, 255, 0.2);
                        color: white;
                    }
                    .dark .wysiwyg-component .ql-toolbar button:active {
                        background: rgba(255, 255, 255, 0.2);
                    }
                    .dark .wysiwyg-component .ql-toolbar button.ql-active {
                        background: rgb(59 130 246);
                        border-color: rgb(37 99 235);
                        color: white;
                    }
                    .dark .wysiwyg-component .ql-toolbar button svg {
                        stroke: rgb(212 212 216) !important;
                        fill: rgb(212 212 216) !important;
                        color: rgb(212 212 216) !important;
                        opacity: 0.9;
                    }
                    .dark .wysiwyg-component .ql-toolbar button:hover svg,
                    .dark .wysiwyg-component .ql-toolbar button:active svg {
                        stroke: white !important;
                        fill: white !important;
                        color: white !important;
                        opacity: 1;
                    }
                    .dark .wysiwyg-component .ql-toolbar button.ql-active svg {
                        stroke: white !important;
                        fill: white !important;
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
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-label:active {
                        background: rgb(75 85 99);
                    }
                    .dark .wysiwyg-component .ql-toolbar .ql-picker.ql-expanded .ql-picker-label {
                        border-color: rgb(59 130 246);
                        box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.2);
                        background: rgb(30 58 138);
                        color: rgb(219 234 254);
                    }
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-label::after {
                        border-top-color: rgb(156 163 175);
                    }
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-options {
                        background: rgb(39 39 42);
                        border-color: rgb(75 85 99);
                        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
                    }
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-item {
                        color: rgb(212 212 216);
                    }
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-item:hover,
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-item:active {
                        background: rgb(55 65 81);
                        color: white;
                    }
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-item.ql-selected {
                        background: rgb(30 58 138);
                        color: rgb(219 234 254);
                    }
                }
                
                /* Desktop dark mode styling - Consistent with light mode */
                @media (min-width: 641px) {
                    .dark .wysiwyg-component .ql-toolbar {
                        background: rgb(39 39 42);
                        border-color: rgb(63 63 70);
                        border-bottom-color: rgb(82 82 91);
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar .ql-formats {
                        background: rgba(255, 255, 255, 0.03);
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar button {
                        color: rgb(212 212 216);
                        border-color: transparent;
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar button:hover {
                        background: rgba(59, 130, 246, 0.15);
                        border-color: rgba(59, 130, 246, 0.3);
                        color: white;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar button.ql-active {
                        background: rgb(59 130 246);
                        color: white;
                        border-color: rgb(37 99 235);
                        box-shadow: 0 2px 6px rgba(59, 130, 246, 0.4);
                    }
                    
                    /* Dark mode icon consistency */
                    .dark .wysiwyg-component .ql-toolbar button svg {
                        stroke: rgb(212 212 216) !important;
                        fill: rgb(212 212 216) !important;
                        color: rgb(212 212 216) !important;
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar button:hover svg {
                        stroke: white !important;
                        fill: white !important;
                        color: white !important;
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar button.ql-active svg {
                        stroke: white !important;
                        fill: white !important;
                        color: white !important;
                    }
                    
                    /* Dark mode Quill built-in icons */
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
                    
                    /* Dark mode align icon consistency */
                    .dark .wysiwyg-component .ql-toolbar .ql-align .ql-picker-label::before,
                    .dark .wysiwyg-component .ql-toolbar .ql-align::before,
                    .dark .wysiwyg-component .ql-toolbar button[class*="ql-align"]::before {
                        color: rgb(212 212 216) !important;
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar .ql-align:hover .ql-picker-label::before,
                    .dark .wysiwyg-component .ql-toolbar .ql-align.ql-expanded .ql-picker-label::before {
                        color: white !important;
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar .ql-align .ql-picker-label svg,
                    .dark .wysiwyg-component .ql-toolbar .ql-align button svg {
                        stroke: rgb(212 212 216) !important;
                        fill: rgb(212 212 216) !important;
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar .ql-align:hover .ql-picker-label svg,
                    .dark .wysiwyg-component .ql-toolbar .ql-align.ql-expanded .ql-picker-label svg {
                        stroke: white !important;
                        fill: white !important;
                    }
                    
                    /* Dark mode picker styling */
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-label {
                        background: rgb(39 39 42);
                        border-color: rgb(75 85 99);
                        color: rgb(212 212 216);
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-label:hover {
                        background: rgb(55 65 81);
                        border-color: rgb(107 114 128);
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar .ql-picker.ql-expanded .ql-picker-label {
                        border-color: rgb(59 130 246);
                        box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.3);
                        background: rgb(30 58 138);
                        color: rgb(219 234 254);
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-label::after {
                        border-top-color: rgb(156 163 175);
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-options {
                        background: rgb(39 39 42);
                        border-color: rgb(75 85 99);
                        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
                        backdrop-filter: blur(8px);
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-item {
                        color: rgb(212 212 216);
                        border-radius: 4px;
                        margin: 2px 4px;
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-item:hover {
                        background: rgb(55 65 81);
                        color: white;
                        transform: translateX(2px);
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                    }
                    
                    .dark .wysiwyg-component .ql-toolbar .ql-picker-item.ql-selected {
                        background: rgb(30 58 138);
                        color: rgb(219 234 254);
                        border: 1px solid rgb(59 130 246);
                    }
                    
                    /* Enhanced dark mode color picker styling */
                    .dark .wysiwyg-component .ql-color .ql-picker-options,
                    .dark .wysiwyg-component .ql-background .ql-picker-options {
                        background: rgba(17, 24, 39, 0.98) !important;
                        border: 1px solid rgba(75, 85, 99, 0.8) !important;
                        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.8) !important;
                        backdrop-filter: blur(12px) !important;
                        border-radius: 12px !important;
                    }
                    
                    .dark .wysiwyg-component .ql-color .ql-picker-item:hover,
                    .dark .wysiwyg-component .ql-background .ql-picker-item:hover {
                        border-color: rgba(156, 163, 175, 0.8) !important;
                        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.7) !important;
                    }
                    
                    .dark .wysiwyg-component .ql-color .ql-picker-item.ql-selected,
                    .dark .wysiwyg-component .ql-background .ql-picker-item.ql-selected {
                        border-color: rgb(59 130 246) !important;
                        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.4), 0 4px 16px rgba(59, 130, 246, 0.5) !important;
                    }
                    
                    /* Dark mode expanded picker glow effect */
                    .dark .wysiwyg-component .ql-toolbar .ql-picker.ql-expanded .ql-picker-label {
                        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3), 0 4px 16px rgba(59, 130, 246, 0.2);
                        background: rgb(30 58 138);
                        color: rgb(219 234 254);
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
            
            /* Dark mode image styles */
            .dark .wysiwyg-component .ql-editor img {
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .dark .wysiwyg-component .ql-editor img:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
                border-color: rgba(255, 255, 255, 0.2);
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
            
            /* Formula/Math styles */
            .wysiwyg-component .ql-formula {
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 4px;
                padding: 4px 8px;
                margin: 2px 0;
                display: inline-block;
                font-family: 'KaTeX_Main', 'Times New Roman', serif;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            }
            
            .wysiwyg-component .ql-formula:hover {
                background: #e2e8f0;
                border-color: #cbd5e0;
            }
            
            .wysiwyg-component .ql-editor .ql-formula {
                background: transparent;
                border: none;
                padding: 0;
                margin: 0 2px;
            }
            
            .wysiwyg-component .ql-toolbar .ql-formula {
                position: relative;
            }
            
            /* Formula button styling - Desktop consistency */
            @media (min-width: 641px) {
                .wysiwyg-component .ql-toolbar .ql-formula {
                    width: 36px !important;
                    height: 36px !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    position: relative;
                    font-weight: 600;
                    font-style: italic;
                    border-radius: 6px !important;
                    border: 1px solid transparent !important;
                    background: transparent !important;
                    transition: all 0.15s ease !important;
                    cursor: pointer !important;
                    flex-shrink: 0 !important;
                    box-sizing: border-box !important;
                    line-height: 1 !important;
                    vertical-align: top !important;
                }
                
                .wysiwyg-component .ql-toolbar .ql-formula:after {
                    content: "f(x)";
                    font-style: italic;
                    font-weight: bold;
                    font-size: 12px;
                    color: currentColor;
                    line-height: 1;
                    display: block;
                }
                
                .wysiwyg-component .ql-toolbar .ql-formula:hover {
                    background: rgba(0, 0, 0, 0.05) !important;
                    border-color: rgba(0, 0, 0, 0.1) !important;
                }
                
                /* Dark mode formula button */
                .dark .wysiwyg-component .ql-toolbar .ql-formula {
                    color: rgb(212 212 216);
                    border-color: transparent !important;
                    background: transparent !important;
                }
                
                .dark .wysiwyg-component .ql-toolbar .ql-formula:hover {
                    color: white;
                    background: rgba(255, 255, 255, 0.05) !important;
                    border-color: rgba(255, 255, 255, 0.1) !important;
                }
                
                .dark .wysiwyg-component .ql-toolbar .ql-formula.ql-active {
                    color: white;
                    background: rgba(59, 130, 246, 0.1) !important;
                    border-color: rgba(59, 130, 246, 0.3) !important;
                }
            }
            
            /* Hide the built-in Quill formula button SVG and use our custom styling */
            .wysiwyg-component .ql-toolbar .ql-formula svg {
                display: none !important;
            }
            
            .wysiwyg-component .ql-toolbar .ql-formula:after {
                content: "f(x)";
                font-style: italic;
                font-weight: bold;
                font-size: 12px;
                color: currentColor;
                line-height: 1;
                display: block;
            }
            
            /* Hide any potential duplicate formula buttons */
            .wysiwyg-component .ql-toolbar .ql-fx,
            .wysiwyg-component .ql-toolbar button[title*="formula"]:not(.ql-formula),
            .wysiwyg-component .ql-toolbar button[data-formula]:not(.ql-formula) {
                display: none !important;
            }
            
            /* Ensure our formula button is visible and properly aligned */
            .wysiwyg-component .ql-toolbar .ql-formula {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                position: relative;
                width: 36px !important;
                height: 36px !important;
                min-width: 36px !important;
                min-height: 36px !important;
                max-width: 36px !important;
                max-height: 36px !important;
                vertical-align: top !important;
                box-sizing: border-box !important;
            }
            
            /* KaTeX rendered formula styling */
            .wysiwyg-component .katex {
                font-size: 1em;
            }
            
            .wysiwyg-component .katex-display {
                margin: 0.5em 0;
                text-align: center;
            }
            
            /* Dark mode formula adjustments */
            @media (prefers-color-scheme: dark) {
                .wysiwyg-component .ql-formula {
                    background: rgba(31, 41, 55, 0.5);
                    border-color: rgba(255,255,255,0.2);
                    color: rgba(255,255,255,0.9);
                }
                
                .wysiwyg-component .ql-formula:hover {
                    background: rgba(31, 41, 55, 0.7);
                    border-color: rgba(255,255,255,0.3);
                }
                
                .wysiwyg-component .ql-color .ql-picker-options,
                .wysiwyg-component .ql-background .ql-picker-options {
                    background: rgba(31, 41, 55, 0.95);
                    border: 1px solid rgba(255,255,255,0.2);
                    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
                    border-radius: 12px;
                    padding: 12px;
                    display: grid !important;
                    grid-template-columns: repeat(7, 1fr);
                    gap: 8px;
                    min-width: 220px;
                    backdrop-filter: blur(8px);
                }
                
                /* Enhanced color picker items for better desktop UX */
                .wysiwyg-component .ql-color .ql-picker-item,
                .wysiwyg-component .ql-background .ql-picker-item {
                    width: 24px !important;
                    height: 24px !important;
                    border-radius: 50% !important;
                    border: 2px solid rgba(255, 255, 255, 0.8) !important;
                    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                    cursor: pointer !important;
                    position: relative !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    display: block !important;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2) !important;
                }
                
                .wysiwyg-component .ql-color .ql-picker-item:hover,
                .wysiwyg-component .ql-background .ql-picker-item:hover {
                    transform: scale(1.15) !important;
                    border-color: #ffffff !important;
                    box-shadow: 0 2px 12px rgba(0,0,0,0.4) !important;
                    z-index: 1 !important;
                }
                
                .wysiwyg-component .ql-color .ql-picker-item.ql-selected,
                .wysiwyg-component .ql-background .ql-picker-item.ql-selected {
                    border-color: #3b82f6 !important;
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3), 0 4px 12px rgba(59, 130, 246, 0.3) !important;
                    transform: scale(1.1) !important;
                }
                
                .wysiwyg-component .ql-picker-options .ql-picker-item:hover {
                    border: 1px solid #60a5fa;
                }
            }
            /* Fix header picker label so "Normal" / "Heading" text is not truncated */
            .wysiwyg-component .ql-toolbar .ql-header {
                width: auto !important;
                min-width: 88px !important;
                max-width: 260px !important;
                flex: 0 0 auto !important;
            }

            .wysiwyg-component .ql-toolbar .ql-header .ql-picker-label,
            .wysiwyg-component .ql-toolbar .ql-header .ql-picker-label::before {
                white-space: nowrap !important;
                overflow: visible !important;
                text-overflow: clip !important;
                min-width: 88px !important;
                max-width: 220px !important;
                padding: 6px 10px !important;
                justify-content: space-between !important;
                align-items: center !important;
            }

            @media (max-width: 640px) {
                .wysiwyg-component .ql-toolbar .ql-header,
                .wysiwyg-component .ql-toolbar .ql-header .ql-picker-label {
                    min-width: 70px !important;
                    max-width: 160px !important;
                    overflow: visible !important;
                }
            }
            /* Mobile: make header picker open as a centered popup/modal for easier selection */
            @media (max-width: 640px) {
                .wysiwyg-component .ql-toolbar .ql-header .ql-picker.ql-expanded .ql-picker-options {
                    position: fixed !important;
                    left: 50% !important;
                    top: 50% !important;
                    transform: translate(-50%, -50%) !important;
                    z-index: 12000 !important;
                    margin: 0 !important;
                    min-width: 220px !important;
                    max-width: 92vw !important;
                    width: auto !important;
                    padding: 8px !important;
                    border-radius: 12px !important;
                    box-shadow: 0 12px 40px rgba(0,0,0,0.35) !important;
                    background: white !important;
                    -webkit-backdrop-filter: blur(6px) !important;
                    backdrop-filter: blur(6px) !important;
                }

                .wysiwyg-component .ql-toolbar .ql-header .ql-picker-options .ql-picker-item {
                    padding: 12px 14px !important;
                    font-size: 15px !important;
                    border-radius: 8px !important;
                    margin: 6px 0 !important;
                    display: block !important;
                }

                /* Ensure the popup uses dark mode styles if needed */
                .dark .wysiwyg-component .ql-toolbar .ql-header .ql-picker.ql-expanded .ql-picker-options {
                    background: rgb(39 39 42) !important;
                    border-color: rgb(63 63 70) !important;
                    color: rgb(212 212 216) !important;
                }
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

        function ensureKaTeX(){
            if(typeof katex !== 'undefined') return Promise.resolve();
            return loadScriptOnce('https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js').then(function(){
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
                [{ 'color': [] }, { 'background': [] }],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': '' }, { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }],
                ['link', 'image', 'formula', 'clean']
            ];

            const quill = new Quill(editorEl, {
                theme: 'snow',
                modules: { 
                    toolbar: {
                        container: toolbarOptions,
                        handlers: {
                            image: function() {
                                const input = document.createElement('input');
                                input.setAttribute('type', 'file');
                                input.setAttribute('accept', 'image/*');
                                input.click();

                                input.onchange = () => {
                                    const file = input.files[0];
                                    if (file) {
                                        const formData = new FormData();
                                        formData.append('image', file);

                                        // Show loading indicator by inserting a temporary text
                                        const range = this.quill.getSelection(true);
                                        this.quill.insertText(range.index, '[Uploading image...]');

                                        // Get CSRF token
                                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                                        fetch('{{ route('wysiwyg.upload') }}', {
                                            method: 'POST',
                                            body: formData,
                                            headers: {
                                                'X-CSRF-TOKEN': token,
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        })
                                        .then(response => response.json())
                                        .then(result => {
                                            if (result.success) {
                                                // Replace loading text with actual uploaded image
                                                this.quill.deleteText(range.index, '[Uploading image...]'.length);
                                                
                                                // Insert the image in a way that supports alignment
                                                // First insert a newline to ensure we're on a new line
                                                if (range.index > 0) {
                                                    const prevChar = this.quill.getText(range.index - 1, 1);
                                                    if (prevChar !== '\n') {
                                                        this.quill.insertText(range.index, '\n');
                                                        range.index += 1;
                                                    }
                                                }
                                                
                                                // Insert the image
                                                this.quill.insertEmbed(range.index, 'image', result.url);
                                                
                                                // Add line break after image for better editing experience
                                                this.quill.insertText(range.index + 1, '\n');
                                                
                                                // Set selection to the line with the image for alignment
                                                this.quill.setSelection(range.index, 0);
                                            } else {
                                                // Remove loading text and show error
                                                this.quill.deleteText(range.index, '[Uploading image...]'.length);
                                                alert('Upload failed: ' + (result.message || 'Unknown error'));
                                            }
                                        })
                                        .catch(error => {
                                            // Remove loading text and show error
                                            this.quill.deleteText(range.index, '[Uploading image...]'.length);
                                            console.error('Upload error:', error);
                                            alert('Upload failed. Please try again.');
                                        });
                                    }
                                };
                            },
                            formula: function() {
                                ensureKaTeX().then(() => {
                                    const range = this.quill.getSelection();
                                    if (range) {
                                        const formula = prompt('Masukkan formula LaTeX (contoh: E = mc^2 atau \\frac{a}{b}):', 'E = mc^2');
                                        if (formula) {
                                            try {
                                                // Test if the formula is valid
                                                katex.renderToString(formula, { throwOnError: true });
                                                
                                                // Insert the formula
                                                this.quill.insertEmbed(range.index, 'formula', formula);
                                                this.quill.setSelection(range.index + 1);
                                            } catch (error) {
                                                alert('Formula tidak valid: ' + error.message);
                                            }
                                        }
                                    }
                                }).catch(error => {
                                    console.error('Failed to load KaTeX:', error);
                                    alert('Gagal memuat library matematika. Silakan muat ulang halaman.');
                                });
                            }
                        }
                    }
                }
            });
            editorEl.__quill = quill;
            
            // Color picker enhancement
            setTimeout(function() {
                const toolbar = editorEl.parentNode.querySelector('.ql-toolbar');
                if (toolbar) {
                    // Add color picker styling and tooltips
                    const colorButton = toolbar.querySelector('.ql-color .ql-picker-label');
                    const backgroundButton = toolbar.querySelector('.ql-background .ql-picker-label');
                    
                    if (colorButton) {
                        colorButton.setAttribute('title', 'Text Color');
                        colorButton.setAttribute('aria-label', 'Text Color');
                    }
                    
                    if (backgroundButton) {
                        backgroundButton.setAttribute('title', 'Background Color');
                        backgroundButton.setAttribute('aria-label', 'Background Color');
                    }
                    
                    // Enhance color picker positioning for both mobile and desktop
                    const colorPickers = toolbar.querySelectorAll('.ql-color, .ql-background');
                    colorPickers.forEach(function(picker) {
                        const options = picker.querySelector('.ql-picker-options');
                        const label = picker.querySelector('.ql-picker-label');
                        
                        if (options && label) {
                            // Enhanced color picker positioning and animation
                            label.addEventListener('click', function() {
                                setTimeout(function() {
                                    if (picker.classList.contains('ql-expanded')) {
                                        const rect = label.getBoundingClientRect();
                                        const optionsRect = options.getBoundingClientRect();
                                        const viewportWidth = window.innerWidth;
                                        const viewportHeight = window.innerHeight;
                                        
                                        // Use enhanced width for color pickers
                                        const pickerWidth = 220;
                                        const pickerHeight = 180;
                                        
                                        if (window.innerWidth <= 640) {
                                            // Mobile positioning - center above toolbar
                                            let left = rect.left + (rect.width / 2) - (pickerWidth / 2);
                                            if (left < 10) left = 10;
                                            if (left + pickerWidth > viewportWidth - 10) {
                                                left = viewportWidth - pickerWidth - 10;
                                            }
                                            
                                            let top = rect.bottom + 8;
                                            if (top + pickerHeight > viewportHeight - 20) {
                                                top = rect.top - pickerHeight - 8;
                                            }
                                            
                                            options.style.position = 'fixed';
                                            options.style.left = left + 'px';
                                            options.style.top = Math.max(10, top) + 'px';
                                            options.style.transform = 'none';
                                            options.style.width = pickerWidth + 'px';
                                            options.style.zIndex = '9999';
                                        } else {
                                            // Desktop positioning - align to right of picker button
                                            let left = rect.right - pickerWidth;
                                            if (left < 10) left = rect.left;
                                            if (left + pickerWidth > viewportWidth - 10) {
                                                left = viewportWidth - pickerWidth - 10;
                                            }
                                            
                                            let top = rect.bottom + 6;
                                            if (top + pickerHeight > viewportHeight - 20) {
                                                top = rect.top - pickerHeight - 6;
                                                options.style.transform = 'translateY(0)';
                                            }
                                            
                                            options.style.position = 'absolute';
                                            options.style.left = (left - rect.left) + 'px';
                                            options.style.top = '100%';
                                            options.style.marginTop = '6px';
                                            options.style.width = pickerWidth + 'px';
                                            options.style.zIndex = '1000';
                                        }
                                        
                                        // Add enhanced animation classes
                                        options.classList.add('enhanced-picker-animation');
                                    }
                                }, 10);
                            });
                            
                            // Handle click outside to close
                            document.addEventListener('click', function(e) {
                                if (!picker.contains(e.target) && picker.classList.contains('ql-expanded')) {
                                    picker.classList.remove('ql-expanded');
                                    options.classList.remove('enhanced-picker-animation');
                                }
                            });
                        }
                    });
                }
            }, 100);
            
            // Remove any duplicate formula buttons that might have been created
            setTimeout(function() {
                const toolbar = editorEl.parentNode.querySelector('.ql-toolbar');
                if (toolbar) {
                    // Remove any buttons that might be duplicates (fx, function, etc.)
                    const duplicateSelectors = [
                        '.ql-fx',
                        '.ql-function', 
                        'button[title*="formula"]:not(.ql-formula)',
                        'button[data-formula]:not(.ql-formula)',
                        'button:not(.ql-formula)[class*="fx"]'
                    ];
                    
                    duplicateSelectors.forEach(selector => {
                        const elements = toolbar.querySelectorAll(selector);
                        elements.forEach(el => el.remove());
                    });
                    
                    // Ensure our formula button is the only one and properly styled
                    const formulaButtons = toolbar.querySelectorAll('.ql-formula');
                    if (formulaButtons.length > 1) {
                        // Keep only the first one and remove the rest
                        for (let i = 1; i < formulaButtons.length; i++) {
                            formulaButtons[i].remove();
                        }
                    }
                }
            }, 150);

            // Responsive dropdown positioning - choose behavior at click time so resizing between
            // desktop <-> mobile doesn't leave dropdowns with stale inline styles.
            setTimeout(function() {
                const pickers = editorEl.parentNode.querySelectorAll('.ql-picker');
                pickers.forEach(function(picker) {
                    const label = picker.querySelector('.ql-picker-label');
                    const options = picker.querySelector('.ql-picker-options');

                    if (!label || !options) return;

                    // Use current viewport width/height when computing placement so changes in
                    // size (e.g. rotating device or resizing window) are handled without reload.
                    label.addEventListener('click', function(e) {
                        // allow Quill to toggle .ql-expanded first
                        setTimeout(function() {
                            if (!picker.classList.contains('ql-expanded')) return;

                            const rect = label.getBoundingClientRect();
                            const viewportWidth = window.innerWidth;
                            const viewportHeight = window.innerHeight;

                            if (viewportWidth <= 640) {
                                // Mobile: show as a fixed small popup near the label but ensure it fits
                                const pickerWidth = 160;
                                const optionsHeight = Math.min(options.scrollHeight, parseInt(viewportHeight * 0.5));

                                let left = Math.max(10, rect.left);
                                if (left + pickerWidth > viewportWidth - 20) left = viewportWidth - pickerWidth - 10;

                                let top = rect.bottom + 8;
                                if (top + optionsHeight > viewportHeight - 20) top = Math.max(20, rect.top - optionsHeight - 8);

                                options.style.position = 'fixed';
                                options.style.left = left + 'px';
                                options.style.top = top + 'px';
                                options.style.width = pickerWidth + 'px';
                                options.style.maxHeight = optionsHeight + 'px';
                                options.style.zIndex = '9999';
                                options.style.borderRadius = '8px';
                                options.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.2)';
                                options.style.opacity = '1';
                                options.style.transform = 'scale(1)';
                                options.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
                            } else {
                                // Desktop: position absolutely relative to picker and prefer showing below
                                const optionsHeight = options.scrollHeight;

                                // Reset styles that might have been applied earlier
                                options.style.position = 'absolute';
                                options.style.left = '0';
                                options.style.width = '';
                                options.style.maxHeight = '';
                                options.style.borderRadius = '';
                                options.style.boxShadow = '';

                                if (rect.bottom + optionsHeight + 20 > viewportHeight) {
                                    options.style.top = 'auto';
                                    options.style.bottom = '100%';
                                    options.style.marginTop = '0';
                                    options.style.marginBottom = '4px';
                                } else {
                                    options.style.top = '100%';
                                    options.style.bottom = 'auto';
                                    options.style.marginTop = '4px';
                                    options.style.marginBottom = '0';
                                }
                                options.style.zIndex = '1000';
                            }
                        }, 0);
                    });

                    // Ensure clicking outside always closes & resets inline styles
                    document.addEventListener('click', function(e) {
                        if (!picker.contains(e.target) && picker.classList.contains('ql-expanded')) {
                            picker.classList.remove('ql-expanded');

                            // animate close then clear styles
                            options.style.opacity = '0';
                            options.style.transform = 'scale(0.95)';
                            setTimeout(function() {
                                options.style.position = '';
                                options.style.left = '';
                                options.style.top = '';
                                options.style.bottom = '';
                                options.style.width = '';
                                options.style.maxHeight = '';
                                options.style.zIndex = '';
                                options.style.marginTop = '';
                                options.style.marginBottom = '';
                                options.style.borderRadius = '';
                                options.style.boxShadow = '';
                                options.style.opacity = '';
                                options.style.transform = '';
                                options.style.transition = '';
                            }, 150);
                        }
                    }, { passive: true });
                });
            }, 100);

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

            // Register FormulaBlot for KaTeX math formulas
            try {
                const Embed = Quill.import && Quill.import('blots/embed');
                if(Embed && !window.__quill_formulaBlot_registered){
                    class FormulaBlot extends Embed {
                        static create(value) {
                            const node = super.create();
                            if (typeof katex !== 'undefined') {
                                try {
                                    katex.render(value, node, {
                                        throwOnError: false,
                                        displayMode: false
                                    });
                                } catch (error) {
                                    node.textContent = value;
                                }
                            } else {
                                node.textContent = value;
                            }
                            node.setAttribute('data-formula', value);
                            return node;
                        }

                        static value(node) {
                            return node.getAttribute('data-formula');
                        }
                    }
                    FormulaBlot.blotName = 'formula';
                    FormulaBlot.tagName = 'span';
                    FormulaBlot.className = 'ql-formula';
                    Quill.register(FormulaBlot);
                    window.__quill_formulaBlot_registered = true;
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
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect class="ql-stroke" x="3" y="3" width="18" height="18" rx="2" ry="2"/><path class="ql-stroke" d="M3 9h18"/><path class="ql-stroke" d="M9 3v18"/></svg>';
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
                    popup.className = 'ql-table-popup hidden p-4 bg-white dark:bg-zinc-800 border dark:border-zinc-700 rounded-lg shadow-lg';
                    popup.setAttribute('data-editor', name);
                    popup.style.minWidth = '200px'; 
                    popup.style.maxWidth = '300px';
                    popup.style.position = 'absolute'; 
                    popup.style.zIndex = 9999;
                    popup.innerHTML = `
                        <div class="flex items-center space-x-2 mb-3">
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300 min-w-[40px]">Rows</label>
                            <input type="number" min="1" value="2" class="ql-table-rows bg-gray-50 dark:bg-zinc-900 dark:text-white border border-gray-200 dark:border-zinc-600 p-2 rounded w-16 text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300 min-w-[35px]">Cols</label>
                            <input type="number" min="1" value="2" class="ql-table-cols bg-gray-50 dark:bg-zinc-900 dark:text-white border border-gray-200 dark:border-zinc-600 p-2 rounded w-16 text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        </div>
                        <div class="flex items-center space-x-2 mb-4">
                            <input type="checkbox" id="ql-table-header-` + name + `" class="ql-table-header text-blue-600 focus:ring-blue-500" />
                            <label for="ql-table-header-` + name + `" class="text-xs font-medium text-gray-700 dark:text-gray-300">Header row</label>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" class="ql-table-cancel px-3 py-2 rounded border border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-gray-300 text-sm hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">Cancel</button>
                            <button type="button" class="ql-table-insert px-3 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm transition-colors">Insert</button>
                        </div>
                    `;
                    document.body.appendChild(popup);
                }

                // Video button & popup
                if(!container.querySelector('.ql-video')){
                    const vbtn = document.createElement('button'); 
                    vbtn.type='button'; 
                    vbtn.className='ql-video';
                    vbtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect class="ql-stroke" x="2" y="5" width="20" height="14" rx="2" ry="2"/><path class="ql-stroke" d="M10 9l6 3-6 3z"/></svg>';
                    const imgBtn = container.querySelector('.ql-image'); 
                    if(imgBtn && imgBtn.parentNode) imgBtn.parentNode.insertBefore(vbtn, imgBtn.nextSibling); 
                    else (container.querySelector('.ql-formats')||container).appendChild(vbtn);
                }
                
                let vpopup = document.querySelector('.ql-video-popup[data-editor="' + name + '"]');
                if(!vpopup){
                    vpopup = document.createElement('div'); 
                    vpopup.className='ql-video-popup hidden p-4 bg-white dark:bg-zinc-800 border dark:border-zinc-700 rounded-lg shadow-lg';
                    vpopup.setAttribute('data-editor', name); 
                    vpopup.style.minWidth='280px'; 
                    vpopup.style.maxWidth='400px';
                    vpopup.style.position='absolute'; 
                    vpopup.style.zIndex=9999;
                    vpopup.innerHTML = `
                        <div class="flex flex-col space-y-3">
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Video URL</label>
                            <input type="text" class="ql-video-url bg-gray-50 dark:bg-zinc-900 dark:text-white border border-gray-200 dark:border-zinc-600 p-2 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="https://www.youtube.com/watch?v=..." />
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" class="ql-video-iframe text-blue-600 focus:ring-blue-500" id="ql-video-iframe-` + name + `" />
                                <label for="ql-video-iframe-` + name + `" class="text-xs font-medium text-gray-700 dark:text-gray-300">Paste iframe HTML</label>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end space-x-2">
                            <button type="button" class="ql-video-cancel px-3 py-2 rounded border border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-gray-300 text-sm hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">Cancel</button>
                            <button type="button" class="ql-video-insert px-3 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm transition-colors">Insert</button>
                        </div>
                    `;
                    document.body.appendChild(vpopup);
                }

                // Video handlers
                const videoBtn = container.querySelector('.ql-video');
                if(videoBtn){
                    videoBtn.addEventListener('click', function(e){ 
                        e.preventDefault(); 
                        const btnRect = videoBtn.getBoundingClientRect(); 
                        
                        let left, top;
                        if (window.innerWidth <= 640) {
                            // Mobile positioning - center popup
                            left = Math.max(10, (window.innerWidth - 280) / 2);
                            top = btnRect.bottom + window.scrollY + 10;
                            
                            // If too close to bottom, show above
                            if (top + 200 > window.innerHeight + window.scrollY - 20) {
                                top = Math.max(20, btnRect.top + window.scrollY - 210);
                            }
                        } else {
                            // Desktop positioning
                            left = Math.max(8, btnRect.left + window.scrollX); 
                            top = btnRect.bottom + window.scrollY + 6;
                        }
                        
                        vpopup.style.left = left + 'px'; 
                        vpopup.style.top = top + 'px'; 
                        vpopup.classList.toggle('hidden'); 
                        
                        // Focus with delay to ensure popup is visible
                        setTimeout(() => {
                            const input = vpopup.querySelector('.ql-video-url');
                            if (input) input.focus();
                        }, 100);
                    });
                    
                    document.addEventListener('click', function(ev){ 
                        if(vpopup.classList.contains('hidden')) return; 
                        if(ev.target === videoBtn || vpopup.contains(ev.target) || container.contains(ev.target)) return; 
                        vpopup.classList.add('hidden'); 
                    });
                    
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
                if(!container.querySelector('.ql-html')){ 
                    const hbtn = document.createElement('button'); 
                    hbtn.type='button'; 
                    hbtn.className='ql-html'; 
                    hbtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path class="ql-stroke" d="M16 18l6-6-6-6"/><path class="ql-stroke" d="M8 6l-6 6 6 6"/></svg>'; 
                    (container.querySelector('.ql-formats')||container).appendChild(hbtn); 
                }
                
                let hpopup = document.querySelector('.ql-html-popup[data-editor="' + name + '"]');
                if(!hpopup){ 
                    hpopup = document.createElement('div'); 
                    hpopup.className='ql-html-popup hidden p-4 bg-white dark:bg-zinc-800 border dark:border-zinc-700 rounded-lg shadow-lg'; 
                    hpopup.setAttribute('data-editor', name); 
                    hpopup.style.minWidth='320px'; 
                    hpopup.style.maxWidth='500px';
                    hpopup.style.position='absolute'; 
                    hpopup.style.zIndex=9999; 
                    hpopup.innerHTML = `
                        <div class="flex flex-col space-y-3">
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Paste HTML / iframe</label>
                            <textarea class="ql-html-content bg-gray-50 dark:bg-zinc-900 dark:text-white border border-gray-200 dark:border-zinc-600 p-3 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-vertical" rows="6" placeholder="Paste iframe or HTML here"></textarea>
                        </div>
                        <div class="mt-4 flex justify-end space-x-2">
                            <button type="button" class="ql-html-cancel px-3 py-2 rounded border border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-gray-300 text-sm hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">Cancel</button>
                            <button type="button" class="ql-html-insert px-3 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm transition-colors">Insert</button>
                        </div>
                    `; 
                    document.body.appendChild(hpopup); 
                }
                const htmlBtn = container.querySelector('.ql-html'); 
                if(htmlBtn){ 
                    htmlBtn.addEventListener('click', function(e){ 
                        e.preventDefault(); 
                        const btnRect = htmlBtn.getBoundingClientRect(); 
                        
                        let left, top;
                        if (window.innerWidth <= 640) {
                            // Mobile positioning - center popup
                            left = Math.max(10, (window.innerWidth - 340) / 2);
                            top = btnRect.bottom + window.scrollY + 10;
                            
                            // If too close to bottom, show above
                            if (top + 250 > window.innerHeight + window.scrollY - 20) {
                                top = Math.max(20, btnRect.top + window.scrollY - 260);
                            }
                        } else {
                            // Desktop positioning
                            left = Math.max(8, btnRect.left + window.scrollX); 
                            top = btnRect.bottom + window.scrollY + 6;
                        }
                        
                        hpopup.style.left = left + 'px'; 
                        hpopup.style.top = top + 'px'; 
                        hpopup.classList.toggle('hidden'); 
                        
                        // Focus with delay to ensure popup is visible
                        setTimeout(() => {
                            const textarea = hpopup.querySelector('.ql-html-content');
                            if (textarea) textarea.focus();
                        }, 100);
                    });
                    
                    document.addEventListener('click', function(ev){ 
                        if(hpopup.classList.contains('hidden')) return; 
                        if(ev.target === htmlBtn || hpopup.contains(ev.target) || container.contains(ev.target)) return; 
                        hpopup.classList.add('hidden'); 
                    });
                    
                    hpopup.querySelector('.ql-html-insert').addEventListener('click', function(){ 
                        const html = hpopup.querySelector('.ql-html-content').value.trim(); 
                        if(!html) return; 
                        
                        try { 
                            const BlockEmbed = Quill.import && Quill.import('blots/block/embed'); 
                            if(BlockEmbed && !window.__quill_htmlBlot_registered){ 
                                class HtmlBlot extends BlockEmbed { 
                                    static create(value){ 
                                        const node = super.create(); 
                                        node.innerHTML = value; 
                                        node.setAttribute('data-html-embed', 'true'); 
                                        node.contentEditable = false; 
                                        return node; 
                                    } 
                                    static value(node){ 
                                        return node.innerHTML; 
                                    } 
                                } 
                                HtmlBlot.blotName='htmlBlot'; 
                                HtmlBlot.tagName='div'; 
                                HtmlBlot.className='ql-html-embed'; 
                                Quill.register(HtmlBlot); 
                                window.__quill_htmlBlot_registered = true; 
                            } 
                        } catch(e){}
                        
                        const range = quill.getSelection(true) || { index: quill.getLength() };
                        try { 
                            if(window.__quill_htmlBlot_registered){ 
                                quill.insertEmbed(range.index, 'htmlBlot', html, Quill.sources.USER); 
                                quill.insertText(range.index + 1, '\n', Quill.sources.SILENT); 
                                quill.setSelection(range.index + 2, Quill.sources.SILENT); 
                            } else { 
                                quill.root.insertAdjacentHTML('beforeend', html); 
                            } 
                        } catch(e){ 
                            quill.root.insertAdjacentHTML('beforeend', html); 
                        }
                        
                        hpopup.classList.add('hidden'); 
                    });
                    
                    hpopup.querySelector('.ql-html-cancel').addEventListener('click', function(){ 
                        hpopup.classList.add('hidden'); 
                    }); 
                }

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

                const tableBtn = container.querySelector('.ql-table'); 
                if(!tableBtn) return;
                
                tableBtn.addEventListener('click', function(e){ 
                    e.preventDefault(); 
                    const btnRect = tableBtn.getBoundingClientRect(); 
                    
                    let left, top;
                    if (window.innerWidth <= 640) {
                        // Mobile positioning - center popup
                        left = Math.max(10, (window.innerWidth - 220) / 2);
                        top = btnRect.bottom + window.scrollY + 10;
                        
                        // If too close to bottom, show above
                        if (top + 180 > window.innerHeight + window.scrollY - 20) {
                            top = Math.max(20, btnRect.top + window.scrollY - 190);
                        }
                    } else {
                        // Desktop positioning
                        left = Math.max(8, btnRect.left + window.scrollX); 
                        top = btnRect.bottom + window.scrollY + 6;
                    }
                    
                    popup.style.left = left + 'px'; 
                    popup.style.top = top + 'px'; 
                    popup.classList.toggle('hidden'); 
                    
                    // Focus with delay to ensure popup is visible
                    setTimeout(() => {
                        const input = popup.querySelector('.ql-table-rows');
                        if (input) input.focus();
                    }, 100);
                });
                
                document.addEventListener('click', function(ev){ 
                    if(popup.classList.contains('hidden')) return; 
                    if(ev.target === tableBtn || popup.contains(ev.target) || container.contains(ev.target)) return; 
                    popup.classList.add('hidden'); 
                });
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
            Promise.all([ensureQuill(), ensureKaTeX()]).then(function(){ 
                if(typeof Quill === 'undefined') return; 
                document.querySelectorAll('.wysiwyg-component').forEach(function(wrapper){ 
                    const editor = wrapper.querySelector('[id$="-editor"]'); 
                    if(!editor) return; 
                    const name = editor.id.replace(/-editor$/, ''); 
                    try{ 
                        initEditorFor(name); 
                    } catch(e){ 
                        console.error('WYSIWYG init error', e); 
                    } 
                }); 
            }).catch(function(err){ 
                console.error('Failed to load WYSIWYG editor scripts', err); 
            });
        }

        if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', initAll); } else { initAll(); }
        if (window.livewire) { window.livewire.on('message.processed', initAll); }
        document.addEventListener('livewire:load', initAll);
        document.addEventListener('livewire:message.processed', initAll);
        document.addEventListener('turbo:load', initAll);
    })();
</script>
@endpush
