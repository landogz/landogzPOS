@php
    $base = asset('midone-html.vercel.app');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found - Landogz POS</title>
    <link rel="stylesheet" href="{{ $base }}/dist/css/app.css">
</head>
<body>
    <div class="py-2 bg-gradient-to-b from-theme-1 to-theme-2 dark:from-darkmode-800 dark:to-darkmode-800 min-h-screen">
        <div class="container mx-auto px-4">
            <!-- BEGIN: Error Page (Rubick side menu style) -->
            <div class="flex flex-col items-center justify-center min-h-screen text-center error-page lg:flex-row lg:text-left">
                <div class="-intro-x lg:mr-20">
                    <img class="h-48 w-[450px] lg:h-auto max-w-full" src="{{ $base }}/dist/images/error-illustration.svg" alt="Page not found">
                </div>
                <div class="mt-10 text-white lg:mt-0">
                    <div class="font-medium intro-x text-8xl">404</div>
                    <div class="mt-5 text-xl font-medium intro-x lg:text-3xl">
                        Oops. This page has gone missing.
                    </div>
                    <div class="mt-3 text-lg intro-x text-white/90">
                        You may have mistyped the address or the page may have moved.
                    </div>
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-md font-medium cursor-pointer px-4 py-3 mt-10 text-white border-2 border-white hover:bg-white/10 transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-transparent dark:border-darkmode-400 dark:text-slate-200 intro-x">
                        Back to Home
                    </a>
                </div>
            </div>
            <!-- END: Error Page -->
        </div>
    </div>
</body>
</html>
