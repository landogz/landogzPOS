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
    <style>
        /* OTP 6-digit boxes: layout and look (works without Tailwind) */
        #otp-inputs-wrap {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 6px;
            width: 100%;
            max-width: 100%;
        }
        @media (min-width: 640px) {
            #otp-inputs-wrap { gap: 8px; }
        }
        @media (min-width: 768px) {
            #otp-inputs-wrap { gap: 12px; }
        }
        #otp-inputs-wrap .otp-digit {
            width: 100%;
            min-width: 0;
            height: 44px;
            box-sizing: border-box;
            text-align: center;
            font-size: 1rem;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background: #fff;
            color: #1e293b;
            -webkit-appearance: none;
            appearance: none;
        }
        @media (min-width: 640px) {
            #otp-inputs-wrap .otp-digit { height: 48px; font-size: 1.125rem; }
        }
        @media (min-width: 768px) {
            #otp-inputs-wrap .otp-digit { height: 56px; }
        }
        #otp-inputs-wrap .otp-digit:focus {
            outline: none;
            border-color: var(--tw-primary, #1e3a5f);
            box-shadow: 0 0 0 2px rgba(30, 58, 95, 0.2);
        }
        .dark #otp-inputs-wrap .otp-digit {
            border-color: #475569;
            background: #334155;
            color: #f1f5f9;
        }
    </style>
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
                            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 sm:text-2xl">OTP verification</h2>
                            <p id="otp-message" class="mt-2 text-sm text-slate-600 dark:text-slate-400"></p>
                            <form id="super-admin-otp-form" class="mt-6">
                                <input type="hidden" id="otp-email" name="email" value="{{ isset($email) ? e($email) : '' }}">
                                <div id="otp-inputs-wrap">
                                    <input type="text" inputmode="numeric" pattern="[0-9]" maxlength="1" autocomplete="one-time-code" data-otp-index="0" aria-label="Digit 1" class="otp-digit">
                                    <input type="text" inputmode="numeric" pattern="[0-9]" maxlength="1" autocomplete="one-time-code" data-otp-index="1" aria-label="Digit 2" class="otp-digit">
                                    <input type="text" inputmode="numeric" pattern="[0-9]" maxlength="1" autocomplete="one-time-code" data-otp-index="2" aria-label="Digit 3" class="otp-digit">
                                    <input type="text" inputmode="numeric" pattern="[0-9]" maxlength="1" autocomplete="one-time-code" data-otp-index="3" aria-label="Digit 4" class="otp-digit">
                                    <input type="text" inputmode="numeric" pattern="[0-9]" maxlength="1" autocomplete="one-time-code" data-otp-index="4" aria-label="Digit 5" class="otp-digit">
                                    <input type="text" inputmode="numeric" pattern="[0-9]" maxlength="1" autocomplete="one-time-code" data-otp-index="5" aria-label="Digit 6" class="otp-digit">
                                </div>
                                <input type="hidden" id="otp-code" name="code" value="">
                                <div class="mt-4 flex flex-wrap items-center justify-between gap-2">
                                    <span class="text-sm text-slate-600 dark:text-slate-400">Remaining time: <span id="otp-timer" class="font-semibold text-primary">01:00s</span></span>
                                    <span id="otp-resend-btn" class="text-sm text-slate-400 dark:text-slate-500">Didn't get the code? <span class="font-medium">Resend</span></span>
                                    <button type="button" id="otp-resend-link" class="hidden text-sm text-slate-600 dark:text-slate-400 bg-transparent border-0 p-0 cursor-pointer">Didn't get the code? <span class="font-medium text-primary hover:underline">Resend</span></button>
                                </div>
                                <div class="mt-6 flex flex-col-reverse sm:flex-row gap-2 sm:gap-3">
                                    <button type="button" id="otp-back-btn" class="order-2 sm:order-1 px-4 py-3 text-sm font-semibold text-primary rounded-lg border-2 border-primary bg-white dark:bg-darkmode-800 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-darkmode-700 transition-colors focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none">
                                        Cancel
                                    </button>
                                    <button type="submit" id="otp-verify-btn" class="order-1 sm:order-2 flex-1 transition duration-200 inline-flex items-center justify-center gap-2 rounded-lg font-semibold cursor-pointer bg-primary border border-primary text-white px-4 py-3 shadow-sm hover:bg-primary/90 focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:outline-none disabled:opacity-70">
                                        Verify
                                    </button>
                                </div>
                                <p class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">Wondering how we use this code for verification? <button type="button" id="otp-know-more" class="font-medium text-primary hover:underline focus:outline-none focus:ring-2 focus:ring-primary/20 rounded">Know here</button></p>
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
            var initialStep = {!! json_encode($step ?? '') !!};
            var initialEmail = {!! json_encode($email ?? '') !!};

            var otpTimerInterval = null;
            var otpTimerSeconds = 60;

            function startOtpTimer() {
                otpTimerSeconds = 60;
                var timerEl = document.getElementById('otp-timer');
                var resendPlaceholder = document.getElementById('otp-resend-btn');
                var resendLink = document.getElementById('otp-resend-link');
                if (resendPlaceholder) resendPlaceholder.classList.remove('hidden');
                if (resendLink) resendLink.classList.add('hidden');
                function tick() {
                    var m = Math.floor(otpTimerSeconds / 60);
                    var s = otpTimerSeconds % 60;
                    if (timerEl) timerEl.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s + 's';
                    if (otpTimerSeconds <= 0) {
                        clearInterval(otpTimerInterval);
                        otpTimerInterval = null;
                        if (resendPlaceholder) resendPlaceholder.classList.add('hidden');
                        if (resendLink) resendLink.classList.remove('hidden');
                        return;
                    }
                    otpTimerSeconds--;
                }
                tick();
                if (otpTimerInterval) clearInterval(otpTimerInterval);
                otpTimerInterval = setInterval(tick, 1000);
            }

            function getOtpCodeFromInputs() {
                var digits = document.querySelectorAll('.otp-digit');
                var code = '';
                for (var i = 0; i < digits.length; i++) code += (digits[i].value || '').trim();
                return code;
            }

            function setOtpInputs(value) {
                var digits = document.querySelectorAll('.otp-digit');
                var str = String(value).replace(/\D/g, '').slice(0, 6);
                for (var i = 0; i < digits.length; i++) digits[i].value = str[i] || '';
            }

            if (initialStep === 'otp' && initialEmail) {
                document.getElementById('login-step-form').classList.add('hidden');
                var otpStep = document.getElementById('login-step-otp');
                otpStep.classList.remove('hidden');
                document.getElementById('otp-email').value = initialEmail;
                document.getElementById('otp-message').textContent = 'Please enter the OTP (One-Time Password) sent to your registered email/phone number to complete your verification.';
                startOtpTimer();
                var firstDigit = document.querySelector('.otp-digit');
                if (firstDigit) firstDigit.focus();
            }

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
                        document.getElementById('otp-message').textContent = 'Please enter the OTP (One-Time Password) sent to your registered email/phone number to complete your verification.';
                        setOtpInputs('');
                        startOtpTimer();
                        var firstDigit = document.querySelector('.otp-digit');
                        if (firstDigit) firstDigit.focus();
                        btn.disabled = false;
                        return;
                    }
                    var token = (data && data.token) || res.data.token;
                    var userRole = data && data.user && data.user.role;
                    if (token) {
                        localStorage.setItem('super_admin_token', token);
                        var redirectUrl = userRole === 'cashier'
                            ? '{{ route("dashboard.pos") }}'
                            : '{{ route("dashboard.dashboard") }}';
                        Swal.fire({ icon: 'success', title: 'Success', text: 'Login successful.' })
                            .then(function () { window.location.href = redirectUrl; });
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
                    var code = getOtpCodeFromInputs();
                    if (code.length !== 6) {
                        Swal.fire({ icon: 'warning', title: 'Invalid code', text: 'Please enter the 6-digit verification code.' });
                        return;
                    }
                    otpVerifyBtn.disabled = true;
                    axios.post('{{ url("/api/v1/auth/verify-login-otp") }}', { email: emailEl.value, code: code })
                        .then(function(res) {
                            var data = res.data && res.data.data ? res.data.data : res.data;
                            var token = (data && data.token) || res.data.token;
                            var userRole = data && data.user && data.user.role;
                            if (token) {
                                localStorage.setItem('super_admin_token', token);
                                var redirectUrl = userRole === 'cashier'
                                    ? '{{ route("dashboard.pos") }}'
                                    : '{{ route("dashboard.dashboard") }}';
                                Swal.fire({ icon: 'success', title: 'Success', text: 'Login successful.' })
                                    .then(function () { window.location.href = redirectUrl; });
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

            var otpInputs = document.querySelectorAll('.otp-digit');
            otpInputs.forEach(function(input, index) {
                function focusNext() {
                    if (index < 5) {
                        var next = otpInputs[index + 1];
                        if (next) next.focus();
                    }
                }
                function focusPrev() {
                    if (index > 0) {
                        otpInputs[index - 1].focus();
                    }
                }
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace') {
                        if (!this.value) {
                            e.preventDefault();
                            focusPrev();
                        }
                        return;
                    }
                    if (/^[0-9]$/.test(e.key)) {
                        e.preventDefault();
                        this.value = e.key;
                        focusNext();
                    }
                });
                input.addEventListener('input', function() {
                    var v = this.value.replace(/\D/g, '');
                    this.value = v ? v.slice(-1) : '';
                    if (this.value) setTimeout(focusNext, 0);
                });
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    var pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                    setOtpInputs(pasted);
                    var nextIdx = Math.min(index + pasted.length, 5);
                    otpInputs[nextIdx].focus();
                });
            });

            document.getElementById('otp-resend-link').addEventListener('click', function() {
                var emailEl = document.getElementById('otp-email');
                if (!emailEl || !emailEl.value) return;
                var link = this;
                link.style.pointerEvents = 'none';
                axios.post('{{ url("/api/v1/auth/resend-login-otp") }}', { email: emailEl.value })
                    .then(function() {
                        setOtpInputs('');
                        startOtpTimer();
                        document.querySelector('.otp-digit').focus();
                        Swal.fire({ icon: 'success', title: 'Code sent', text: 'A new verification code has been sent.' });
                    })
                    .catch(function(err) {
                        var msg = err.response?.data?.message || err.message || 'Failed to resend.';
                        Swal.fire({ icon: 'error', title: 'Error', text: msg });
                    })
                    .finally(function() { link.style.pointerEvents = ''; });
            });

            document.getElementById('otp-know-more').addEventListener('click', function() {
                Swal.fire({
                    title: 'How we use this code',
                    html: 'We send a one-time code to your email or phone. You enter it here so we can confirm it\'s really you before signing you in. We don\'t store the code after verification.',
                    icon: 'info',
                    confirmButtonColor: '#1e3a5f'
                });
            });

            document.getElementById('otp-back-btn').addEventListener('click', function() {
                if (otpTimerInterval) clearInterval(otpTimerInterval);
                otpTimerInterval = null;
                document.getElementById('login-step-otp').classList.add('hidden');
                document.getElementById('login-step-form').classList.remove('hidden');
                setOtpInputs('');
            });
        })();
    </script>
</body>
</html>
