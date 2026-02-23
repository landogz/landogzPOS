{{--
    Reusable modal: full-viewport overlay with backdrop blur, above layout.
    Usage:
        <x-modal id="my-modal" title="My Title" size="xl">
            ... body or full form ...
        </x-modal>
    With separate footer: use <x-slot:footer>...</x-slot:footer>
    With custom header: use <x-slot:header>...</x-slot:header>
    Props: id, title, title-id (aria), size (sm|md|lg|xl|2xl), dismiss-attr, close-aria-label
--}}
@props([
    'id' => 'modal',
    'title' => null,
    'titleId' => null,
    'size' => 'xl',
    'dismissAttr' => 'modal',
    'closeAriaLabel' => 'Close',
])

@php
    $titleId = $titleId ?? $id . '-title';
    $sizeClass = match ($size) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        default => 'max-w-xl',
    };
@endphp

<div
    id="{{ $id }}"
    class="modal fixed inset-0 flex items-center justify-center p-3 sm:p-5 overflow-y-auto hidden"
    tabindex="-1"
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $titleId }}"
    data-backdrop="static"
    style="display: none; z-index: 99999; pointer-events: auto;"
    {{ $attributes->except(['id', 'title', 'titleId', 'size', 'dismissAttr', 'closeAriaLabel']) }}
>
    {{-- Static backdrop: only this layer receives backdrop clicks (for pulse); dialog stays fully clickable --}}
    <div class="modal-backdrop absolute inset-0 bg-slate-900/50 backdrop-blur-md z-0 pointer-events-auto" aria-hidden="true"></div>
    <div class="modal-dialog relative z-[1] w-full {{ $sizeClass }} my-4 sm:my-8 max-h-[calc(100vh-2rem)] flex flex-col pointer-events-auto">
        <div class="modal-content bg-white dark:bg-darkmode-800 rounded-2xl shadow-xl ring-1 ring-slate-900/5 dark:ring-white/5 overflow-hidden flex flex-col max-h-[calc(100vh-2rem)]">
            @if(isset($header))
                <div class="modal-header">
                    {{ $header }}
                </div>
            @elseif($title)
                <div class="modal-header flex items-center justify-between px-6 sm:px-8 py-5 sm:py-6 border-b border-slate-200/80 dark:border-darkmode-600 bg-white dark:bg-darkmode-800">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 tracking-tight" id="{{ $titleId }}">{{ $title }}</h2>
                    <button type="button" data-tw-dismiss="{{ $dismissAttr }}" class="flex items-center justify-center w-9 h-9 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:text-slate-300 dark:hover:bg-darkmode-600 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300 dark:focus:ring-darkmode-500 focus:ring-offset-2" aria-label="{{ $closeAriaLabel }}">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            @endif

            {{-- Slot: use for body only, or wrap a form that includes its own body + footer --}}
            @if(isset($footer))
                <div class="modal-body px-6 sm:px-8 py-6">
                    {{ $slot }}
                </div>
                <div class="modal-footer flex flex-col-reverse sm:flex-row sm:justify-end gap-3 px-6 sm:px-8 py-5 border-t border-slate-200 dark:border-darkmode-600 bg-slate-50/30 dark:bg-darkmode-700/30">
                    {{ $footer }}
                </div>
            @else
                {{ $slot }}
            @endif
        </div>
    </div>
</div>
