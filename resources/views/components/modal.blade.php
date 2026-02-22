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
        default => 'max-w-xl',
    };
@endphp

<div
    id="{{ $id }}"
    class="modal fixed inset-0 flex items-center justify-center p-4 overflow-y-auto bg-slate-900/60 backdrop-blur-md hidden"
    tabindex="-1"
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $titleId }}"
    style="display: none; z-index: 99999; pointer-events: auto;"
    {{ $attributes->except(['id', 'title', 'titleId', 'size', 'dismissAttr', 'closeAriaLabel']) }}
>
    <div class="modal-dialog relative w-full {{ $sizeClass }} my-8">
        <div class="modal-content bg-white dark:bg-darkmode-800 rounded-2xl shadow-2xl overflow-hidden border border-slate-200/80 dark:border-darkmode-600">
            @if(isset($header))
                <div class="modal-header">
                    {{ $header }}
                </div>
            @elseif($title)
                <div class="modal-header flex items-center justify-between px-6 sm:px-8 py-5 border-b border-slate-200 dark:border-darkmode-600 bg-slate-50/50 dark:bg-darkmode-700/50">
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 tracking-tight" id="{{ $titleId }}">{{ $title }}</h2>
                    <button type="button" data-tw-dismiss="{{ $dismissAttr }}" class="flex items-center justify-center w-9 h-9 rounded-full text-slate-500 hover:text-slate-700 hover:bg-slate-200/80 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-darkmode-600 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-darkmode-800" aria-label="{{ $closeAriaLabel }}">
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
