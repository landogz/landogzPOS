@php
    $base = $midoneBase ?? asset('midone-html.vercel.app');
    $logoUrl = file_exists(public_path('images/logo.png')) ? asset('images/logo.png') : $base . '/dist/images/logo.svg';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS Locked - Landogz POS</title>
    <link rel="stylesheet" href="{{ $base }}/dist/css/app.css">
    <style>
        body {
            min-height: 100vh;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-100 via-sky-50 to-emerald-50 dark:bg-slate-900">
    <div class="flex min-h-screen items-center justify-center px-4 py-8">
        <div class="w-full max-w-md rounded-[1.5rem] border border-white/40 bg-white/95 px-6 py-8 shadow-2xl backdrop-blur-xl sm:px-8 sm:py-10">
            <div class="flex flex-col items-center text-center gap-5">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 pos-lock-icon" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="10" rx="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-slate-50">POS locked</h1>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Enter the cashier password to unlock this terminal, or logout to end the session.
                    </p>
                </div>
                <form id="pos-lock-form" class="mt-2 w-full space-y-4">
                    <input type="hidden" id="lock-email" name="email">
                    <div class="cashier-chip flex items-center gap-3 rounded-xl bg-slate-50 px-3 py-2 text-left border border-slate-200">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-white text-sm font-semibold">
                            <span id="lock-user-initial">C</span>
                        </div>
                        <div class="min-w-0">
                            <p id="lock-user-label" class="text-sm font-medium text-slate-800 truncate">Cashier</p>
                            <p id="lock-branch-label" class="text-xs text-slate-500 truncate">—</p>
                        </div>
                    </div>
                    <div class="space-y-1 text-left">
                        <label for="lock-password" class="text-xs font-medium text-slate-700 dark:text-slate-200">
                            Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="lock-password"
                                autocomplete="current-password"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 pr-10 text-sm text-slate-900 placeholder-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition"
                                placeholder="Enter cashier password"
                                required
                            >
                            <button
                                type="button"
                                id="lock-toggle-password"
                                class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-slate-400 hover:text-slate-600"
                                aria-label="Show password"
                                title="Show password"
                            >
                                <svg id="lock-eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg id="lock-eye-closed" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a10.76 10.76 0 0 1-1.8 2.3"/><path d="M6.61 6.61A10.49 10.49 0 0 0 2 12s3 7 10 7a10.49 10.49 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/></svg>
                            </button>
                        </div>
                    </div>
                    <button
                        type="submit"
                        id="pos-unlock-btn"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary/90 focus:ring-2 focus:ring-primary/30 transition-colors disabled:opacity-60"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="10" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0" />
                        </svg>
                        <span>Unlock POS</span>
                    </button>
                    <button
                        type="button"
                        id="pos-lock-logout-btn"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:ring-2 focus:ring-slate-300/60 transition-colors dark:border-darkmode-500 dark:bg-darkmode-700 dark:text-slate-200 dark:hover:bg-darkmode-600"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-500" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
                <p id="lock-meta" class="mt-1 text-[11px] text-slate-400">
                    Locked for <span id="lock-duration">just now</span>
                </p>
                <p id="lock-terminal-footer" class="mt-1 text-[11px] text-slate-400">
                    <!-- Filled via JS with branch + terminal -->
                </p>
                <p class="mt-1 text-[11px] text-slate-400">
                    For security, close this window when leaving the counter.
                </p>
            </div>
        </div>
    </div>

    <script src="{{ $base }}/dist/js/vendors/dom.js"></script>
    <script src="{{ $base }}/dist/js/vendors/axios.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function () {
            var form = document.getElementById('pos-lock-form');
            var unlockBtn = document.getElementById('pos-unlock-btn');
            var logoutBtn = document.getElementById('pos-lock-logout-btn');
            var emailInput = document.getElementById('lock-email');
            var userLabel = document.getElementById('lock-user-label');
            var branchLabel = document.getElementById('lock-branch-label');
            var userInitial = document.getElementById('lock-user-initial');
            var terminalFooter = document.getElementById('lock-terminal-footer');
            var passwordInput = document.getElementById('lock-password');
            var toggleBtn = document.getElementById('lock-toggle-password');
            var eyeOpen = document.getElementById('lock-eye-open');
            var eyeClosed = document.getElementById('lock-eye-closed');

            var token = localStorage.getItem('super_admin_token');
            if (!token) {
                window.location.href = '{{ route('dashboard.login') }}';
                return;
            }

            // Autofocus password
            if (passwordInput) {
                passwordInput.focus();
            }

            // Password show/hide toggle
            if (toggleBtn && passwordInput && eyeOpen && eyeClosed) {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
                toggleBtn.setAttribute('aria-label', 'Show password');
                toggleBtn.setAttribute('title', 'Show password');
                toggleBtn.addEventListener('click', function () {
                    var isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    eyeOpen.classList.toggle('hidden', !isPassword);
                    eyeClosed.classList.toggle('hidden', isPassword);
                    toggleBtn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                    toggleBtn.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
                });
            }

            // Populate cashier + branch from current token
            axios.get('{{ url("/api/v1/auth/me") }}', {
                headers: {
                    Authorization: 'Bearer ' + token,
                    Accept: 'application/json'
                }
            }).then(function (r) {
                var d = r.data && r.data.data ? r.data.data : r.data;
                if (!d || !d.user) return;
                var user = d.user;
                var branch = d.branch || user.branch || null;
                var name = user.name || user.email || 'Cashier';
                var roleLabel = (user.role || '').replace(/_/g, ' ');
                roleLabel = roleLabel ? roleLabel.charAt(0).toUpperCase() + roleLabel.slice(1) : '';
                var branchName = branch && branch.name ? branch.name : '';

                if (userLabel) {
                    userLabel.textContent = name + (roleLabel ? ' · ' + roleLabel : '');
                }
                if (branchLabel) {
                    branchLabel.textContent = branchName ? 'Branch · ' + branchName : '';
                }
                if (userInitial && name) {
                    userInitial.textContent = (name.trim().charAt(0) || 'C').toUpperCase();
                }
                if (emailInput) {
                    emailInput.value = user.email || '';
                }

                // Fetch terminal info for footer
                axios.get('{{ url("/api/v1/pos/terminal/current") }}', {
                    headers: {
                        Authorization: 'Bearer ' + token,
                        Accept: 'application/json'
                    }
                }).then(function (tr) {
                    var t = tr.data && tr.data.data ? tr.data.data : tr.data;
                    if (!t) return;
                    var code = t.code || ('T' + t.id);
                    var name = t.name || '';
                    var branchLabelText = branchName || '';
                    var text = '';
                    if (branchLabelText) {
                        text = branchLabelText + ' · Terminal ' + code;
                    } else {
                        text = 'Terminal ' + code;
                    }
                    if (terminalFooter) {
                        terminalFooter.textContent = text;
                    }
                }).catch(function () {
                    // Ignore terminal footer errors
                });
            }).catch(function () {
                // If token invalid, force login
                localStorage.removeItem('super_admin_token');
                window.location.href = '{{ route('dashboard.login') }}';
            });

            if (form && unlockBtn) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    var email = emailInput.value || '';
                    var password = document.getElementById('lock-password').value || '';
                    if (!email || !password) return;
                    unlockBtn.disabled = true;

                    axios.post('{{ url("/api/v1/auth/login") }}', {
                        email: email,
                        password: password
                    }).then(function (res) {
                        var data = res.data && res.data.data ? res.data.data : res.data;
                        if (data && data.otp_required) {
                            // Follow same flow as main login
                            window.location.href = '{{ route('dashboard.login') }}?step=otp&email=' + encodeURIComponent(email);
                            return;
                        }
                        if (data && data.token) {
                            localStorage.setItem('super_admin_token', data.token);
                            window.location.href = '{{ route('dashboard.pos') }}';
                        } else {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({ icon: 'error', title: 'Unable to unlock POS', text: 'Unexpected response from server.' });
                            }
                        }
                    }).catch(function (err) {
                        var msg = 'Invalid password. Please try again.';
                        if (err.response && err.response.data && err.response.data.message) {
                            msg = err.response.data.message;
                        }
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'error', title: 'Unlock failed', text: msg });
                        }
                    }).finally(function () {
                        unlockBtn.disabled = false;
                    });
                });
            }

            // Show lock duration based on localStorage timestamp set from POS
            var lockDurationEl = document.getElementById('lock-duration');
            function updateLockDuration() {
                if (!lockDurationEl) return;
                var ts = localStorage.getItem('pos_locked_at');
                if (!ts) {
                    lockDurationEl.textContent = 'just now';
                    return;
                }
                var lockedAt = new Date(parseInt(ts, 10));
                if (isNaN(lockedAt.getTime())) {
                    lockDurationEl.textContent = 'just now';
                    return;
                }
                var diffMs = Date.now() - lockedAt.getTime();
                if (diffMs < 0) diffMs = 0;
                var diffSec = Math.floor(diffMs / 1000);
                var mins = Math.floor(diffSec / 60);
                var secs = diffSec % 60;
                if (mins <= 0) {
                    lockDurationEl.textContent = secs + 's';
                } else {
                    lockDurationEl.textContent = mins + ' min' + (mins > 1 ? 's' : '');
                }
            }
            updateLockDuration();
            setInterval(updateLockDuration, 1000);

            if (logoutBtn) {
                logoutBtn.addEventListener('click', function () {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'question',
                            title: 'Logout from POS?',
                            text: 'Your dashboard session will be closed.',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, logout',
                            cancelButtonText: 'Cancel',
                        }).then(function (res) {
                            if (!res.isConfirmed) return;
                            localStorage.removeItem('super_admin_token');
                            window.location.href = '{{ route('dashboard.login') }}';
                        });
                    } else {
                        localStorage.removeItem('super_admin_token');
                        window.location.href = '{{ route('dashboard.login') }}';
                    }
                });
            }
        })();
    </script>
</body>
</html>


