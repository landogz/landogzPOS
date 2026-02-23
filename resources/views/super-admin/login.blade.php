@php
    $base = $midoneBase ?? asset('midone-html.vercel.app');
    $logoUrl = file_exists(public_path('images/logo.png')) ? asset('images/logo.png') : $base . '/dist/images/logo.svg';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Super Admin - Landogz POS</title>
    <link rel="stylesheet" href="{{ $base }}/dist/css/app.css">
</head>
<body>
    <div class="p-3 sm:px-8 relative h-screen lg:overflow-hidden bg-primary xl:bg-white dark:bg-darkmode-800 xl:dark:bg-darkmode-600 before:hidden before:xl:block before:content-[''] before:w-[57%] before:-mt-[28%] before:-mb-[16%] before:-ml-[13%] before:absolute before:inset-y-0 before:left-0 before:transform before:rotate-[-4.5deg] before:bg-primary/20 before:rounded-[100%] before:dark:bg-darkmode-400 after:hidden after:xl:block after:content-[''] after:w-[57%] after:-mt-[20%] after:-mb-[13%] after:-ml-[13%] after:absolute after:inset-y-0 after:left-0 after:transform after:rotate-[-4.5deg] after:bg-primary after:rounded-[100%] after:dark:bg-darkmode-700">
        <div class="container relative z-10 sm:px-10">
            <div class="block grid-cols-2 gap-4 xl:grid">
                <div class="hidden min-h-screen flex-col xl:flex">
                    <a class="-intro-x flex items-center pt-5" href="{{ url('/') }}">
                        <img class="w-6" src="{{ $logoUrl }}" alt="Landogz POS">
                        <span class="ml-3 text-lg text-white">Landogz POS</span>
                    </a>
                    <div class="my-auto">
                        <img class="-intro-x -mt-16 w-1/2" src="{{ $base }}/dist/images/illustration.svg" alt="Landogz POS">
                        <div class="-intro-x mt-10 text-4xl font-medium leading-tight text-white">
                            Super Admin
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">
                            Sign in to manage your POS.
                        </div>
                    </div>
                </div>
                <div class="my-10 flex h-screen py-5 xl:my-0 xl:min-h-screen xl:flex-1 xl:items-center xl:justify-center xl:bg-slate-50 xl:px-8 xl:dark:bg-darkmode-800">
                    <div class="mx-auto my-auto w-full max-w-[400px] rounded-2xl border border-slate-200/80 bg-white px-6 py-10 shadow-lg dark:border-darkmode-600 dark:bg-darkmode-600 sm:px-8 xl:mx-auto xl:max-w-[420px] xl:px-12 xl:py-12">
                        <div class="intro-x text-center xl:text-left">
                            <img class="mx-auto h-16 w-auto xl:mx-0 sm:h-20" src="{{ $logoUrl }}" alt="Landogz POS">
                            <p class="mt-6 text-slate-500 dark:text-slate-400 text-sm xl:mt-8">Welcome back 👋</p>
                            <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-800 dark:text-slate-100 sm:text-3xl">Sign In</h2>
                            <p class="mt-1.5 text-slate-500 dark:text-slate-400 text-sm">Sign in to your account</p>
                        </div>
                        <div id="login-step-form">
                            <form id="super-admin-login-form" class="intro-x mt-8">
                                <div>
                                    <label for="login-email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                                    <input id="login-email" type="email" name="email" placeholder="you@example.com" required autocomplete="email"
                                        class="mt-1.5 transition duration-200 ease-in-out w-full text-sm border border-slate-200 shadow-sm rounded-lg placeholder:text-slate-400/90 focus:ring-2 focus:ring-primary focus:ring-opacity-20 focus:border-primary block px-4 py-3 dark:bg-darkmode-800 dark:border-darkmode-500 dark:placeholder:text-slate-500">
                                </div>
                                <div class="mt-5">
                                    <div class="flex items-center justify-between">
                                        <label for="login-password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
                                        <a href="#" id="login-forgot-password" class="text-sm font-medium text-primary hover:underline focus:outline-none focus:ring-2 focus:ring-primary/20 rounded">Forgot password?</a>
                                    </div>
                                    <div class="relative mt-1.5">
                                        <input id="login-password" type="password" name="password" placeholder="••••••••" required autocomplete="current-password"
                                            class="transition duration-200 ease-in-out w-full text-sm border border-slate-200 shadow-sm rounded-lg placeholder:text-slate-400/90 focus:ring-2 focus:ring-primary focus:ring-opacity-20 focus:border-primary block px-4 py-3 pr-10 dark:bg-darkmode-800 dark:border-darkmode-500 dark:placeholder:text-slate-500">
                                        <button type="button" id="login-toggle-password" class="absolute inset-y-0 right-0 flex items-center justify-center w-10 rounded-r-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-darkmode-500 dark:hover:text-slate-300 focus:outline-none focus:ring-2 focus:ring-primary/20 z-10" aria-label="Show password" title="Show password">
                                            <svg id="login-eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            <svg id="login-eye-closed" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden" aria-hidden="true"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a10.76 10.76 0 0 1-1.8 2.3"/><path d="M6.61 6.61A10.49 10.49 0 0 0 2 12s3 7 10 7a10.49 10.49 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <button type="submit" id="login-btn" class="transition duration-200 w-full inline-flex items-center justify-center gap-2 rounded-lg font-semibold cursor-pointer bg-primary border border-primary text-white px-4 py-3 shadow-sm hover:bg-primary/90 focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none disabled:opacity-70">
                                        Login
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div id="login-step-otp" class="intro-x mt-8 hidden">
                            <p id="otp-message" class="text-sm text-slate-600 dark:text-slate-400 mb-4"></p>
                            <form id="super-admin-otp-form">
                                <input type="hidden" id="otp-email" name="email" value="">
                                <div>
                                    <label for="otp-code" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Verification code</label>
                                    <input id="otp-code" type="text" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" placeholder="000000" autocomplete="one-time-code"
                                        class="mt-1.5 transition duration-200 ease-in-out w-full text-sm border border-slate-200 shadow-sm rounded-lg placeholder:text-slate-400/90 focus:ring-2 focus:ring-primary focus:ring-opacity-20 focus:border-primary block px-4 py-3 text-center tracking-[0.5em] dark:bg-darkmode-800 dark:border-darkmode-500 dark:placeholder:text-slate-500">
                                </div>
                                <div class="mt-6 flex flex-col sm:flex-row gap-2">
                                    <button type="submit" id="otp-verify-btn" class="flex-1 transition duration-200 inline-flex items-center justify-center gap-2 rounded-lg font-semibold cursor-pointer bg-primary border border-primary text-white px-4 py-3 shadow-sm hover:bg-primary/90 focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none disabled:opacity-70">
                                        Verify & sign in
                                    </button>
                                    <button type="button" id="otp-back-btn" class="px-4 py-3 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-slate-100 rounded-lg border border-slate-200 dark:border-darkmode-500 hover:bg-slate-50 dark:hover:bg-darkmode-700 transition-colors">
                                        Back
                                    </button>
                                </div>
                            </form>
                        </div>
                        <p class="intro-x mt-8 text-center text-xs text-slate-400 dark:text-slate-500">© {{ date('Y') }} Landogz POS</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ $base }}/dist/js/vendors/dom.js"></script>
    <script src="{{ $base }}/dist/js/vendors/axios.js"></script>
    <script src="{{ $base }}/dist/js/vendors/lucide.js"></script>
    <script src="{{ $base }}/dist/js/components/base/lucide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function() {
            var form = document.getElementById('super-admin-login-form');
            var btn = document.getElementById('login-btn');
            var passwordInput = document.getElementById('login-password');
            var toggleBtn = document.getElementById('login-toggle-password');
            var eyeOpen = document.getElementById('login-eye-open');
            var eyeClosed = document.getElementById('login-eye-closed');

            if (toggleBtn && passwordInput && eyeOpen && eyeClosed) {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
                toggleBtn.setAttribute('aria-label', 'Show password');
                toggleBtn.setAttribute('title', 'Show password');
                toggleBtn.addEventListener('click', function() {
                    var isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    eyeOpen.classList.toggle('hidden', !isPassword);
                    eyeClosed.classList.toggle('hidden', isPassword);
                    toggleBtn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                    toggleBtn.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
                });
            }

            document.getElementById('login-forgot-password').addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({ icon: 'info', title: 'Forgot password', text: 'Please contact your administrator to reset your password.' });
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                btn.disabled = true;
                var formData = new FormData(form);
                axios.post('{{ url("/api/v1/auth/login") }}', {
                    email: formData.get('email'),
                    password: formData.get('password')
                }).then(function(res) {
                    var data = res.data && res.data.data ? res.data.data : res.data;
                    if (data && data.otp_required) {
                        document.getElementById('login-step-form').classList.add('hidden');
                        document.getElementById('login-step-otp').classList.remove('hidden');
                        document.getElementById('otp-email').value = data.email || formData.get('email');
                        document.getElementById('otp-message').textContent = data.message || 'Enter the verification code we sent you.';
                        document.getElementById('otp-code').value = '';
                        document.getElementById('otp-code').focus();
                        btn.disabled = false;
                        return;
                    }
                    var token = res.data.token || (data && data.token);
                    if (token) {
                        localStorage.setItem('super_admin_token', token);
                        Swal.fire({ icon: 'success', title: 'Success', text: 'Login successful.' });
                        window.location.href = '{{ route("dashboard.dashboard") }}';
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.data?.message || 'Login failed.' });
                        btn.disabled = false;
                    }
                }).catch(function(err) {
                    var msg = err.response?.data?.message || err.message || 'Login failed.';
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                    btn.disabled = false;
                });
            });

            var otpForm = document.getElementById('super-admin-otp-form');
            var otpVerifyBtn = document.getElementById('otp-verify-btn');
            if (otpForm) {
                otpForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var emailEl = document.getElementById('otp-email');
                    var codeEl = document.getElementById('otp-code');
                    var code = (codeEl.value || '').trim();
                    if (code.length !== 6) {
                        Swal.fire({ icon: 'warning', title: 'Invalid code', text: 'Please enter the 6-digit verification code.' });
                        return;
                    }
                    otpVerifyBtn.disabled = true;
                    axios.post('{{ url("/api/v1/auth/verify-login-otp") }}', { email: emailEl.value, code: code })
                        .then(function(res) {
                            var data = res.data && res.data.data ? res.data.data : res.data;
                            var token = res.data.token || (data && data.token);
                            if (token) {
                                localStorage.setItem('super_admin_token', token);
                                Swal.fire({ icon: 'success', title: 'Success', text: 'Login successful.' });
                                window.location.href = '{{ route("dashboard.dashboard") }}';
                            } else {
                                Swal.fire({ icon: 'error', title: 'Error', text: res.data?.message || 'Verification failed.' });
                                otpVerifyBtn.disabled = false;
                            }
                        })
                        .catch(function(err) {
                            var msg = err.response?.data?.message || err.message || 'Verification failed.';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg });
                            otpVerifyBtn.disabled = false;
                        });
                });
            }
            document.getElementById('otp-back-btn').addEventListener('click', function() {
                document.getElementById('login-step-otp').classList.add('hidden');
                document.getElementById('login-step-form').classList.remove('hidden');
                document.getElementById('otp-code').value = '';
            });
        })();
    </script>
</body>
</html>
