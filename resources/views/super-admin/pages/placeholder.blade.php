@extends('super-admin.layouts.app')

@section('title', $title ?? 'Page')
@section('breadcrumb', $breadcrumb ?? $title ?? 'Page')

@section('content')
    <div class="intro-y mt-8 flex h-10 items-center">
        <h2 class="mr-5 truncate text-lg font-medium">{{ $title ?? 'Page' }}</h2>
    </div>
    <div class="mt-5">
        <div class="box p-6">
            <p class="text-slate-600 dark:text-slate-400">
                This section is powered by the API. Use <code class="rounded bg-slate-200 px-1.5 py-0.5 text-sm dark:bg-darkmode-400">/api/v1/{{ $apiModule ?? 'dashboard' }}</code> to load data.
            </p>
            <p class="mt-3 text-sm text-slate-500 dark:text-slate-500">
                Implement DataTables, filters, and Axios calls in <code class="rounded bg-slate-200 px-1.5 py-0.5 dark:bg-darkmode-400">resources/js/modules/</code> and link from this view when ready.
            </p>
        </div>
    </div>
@endsection
