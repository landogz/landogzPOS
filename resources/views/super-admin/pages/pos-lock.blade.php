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
        body { min-height: 100vh; }
        @keyframes pos-lock-fade-in {
            from { opacity: 0; transform: scale(0.98); }
            to { opacity: 1; transform: scale(1); }
        }
        .pos-lock-card { animation: pos-lock-fade-in 0.25s ease-out; }
        .pos-lock-icon-pulse { animation: pos-lock-icon-pulse 2s ease-in-out infinite; }
        @keyframes pos-lock-icon-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.85; }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-900">
    {{-- Immersive: dark overlay + blur layer --}}
    <div class="fixed inset-0 bg-slate-800/90 backdrop-blur-md" aria-hidden="true"></div>
    <div class="relative flex min-h-screen items-center justify-center px-4 py-8">
        <div class="pos-lock-card w-full max-w-md rounded-2xl border border-slate-200/80 bg-white shadow-2xl sm:px-8 sm:py-10 px-6 py-8">
            <div class="flex flex-col items-center text-center">
                {{-- Micro-label --}}
                <span class="mb-4 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Terminal Secure Mode</span>

                {{-- Lock icon: soft circular badge, thin icon --}}
                <div class="pos-lock-icon-pulse mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 ring-4 ring-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="10" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>

                {{-- Typography hierarchy --}}
                <h1 class="text-2xl font-semibold text-slate-900 sm:text-3xl">POS Locked</h1>
                <p class="mt-2 max-w-[280px] text-sm font-normal text-slate-500">
                    Enter password to unlock this terminal.
                </p>

                <form id="pos-lock-form" class="mt-6 w-full space-y-4">
                    <input type="hidden" id="lock-email" name="email">
                    {{-- Cashier card: softer bg, more padding, single line branch+terminal --}}
                    <div class="flex items-center gap-4 rounded-2xl bg-slate-50 px-4 py-3.5 text-left border border-slate-200/80">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-semibold text-primary">
                            <span id="lock-user-initial">C</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p id="lock-user-label" class="truncate text-sm font-medium text-slate-800">Cashier</p>
                            <p id="lock-branch-label" class="truncate text-xs text-slate-500">—</p>
                        </div>
                    </div>

                    {{-- Password: rounded, focus glow, larger touch target, eye centered --}}
                    <div class="space-y-1.5 text-left">
                        <label for="lock-password" class="text-xs font-medium text-slate-600">Password</label>
                        <div class="relative flex items-center">
                            <input
                                type="password"
                                id="lock-password"
                                autocomplete="current-password"
                                class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-4 pr-12 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:shadow-md outline-none"
                                placeholder="Enter cashier password"
                                required
                            >
                            <button
                                type="button"
                                id="lock-toggle-password"
                                class="absolute right-0 flex h-full w-12 items-center justify-center text-slate-400 hover:text-slate-600"
                                aria-label="Show password"
                                title="Show password"
                            >
                                <svg id="lock-eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg id="lock-eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a10.76 10.76 0 0 1-1.8 2.3"/><path d="M6.61 6.61A10.49 10.49 0 0 0 2 12s3 7 10 7a10.49 10.49 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Unlock: gradient, larger, hover lift --}}
                    <button
                        type="submit"
                        id="pos-unlock-btn"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary to-indigo-600 py-3.5 text-base font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl focus:ring-4 focus:ring-primary/30 disabled:opacity-60"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 11V7a4 4 0 1 1 8 0v4"/><rect x="3" y="11" width="18" height="11" rx="2"/></svg>
                        <span>Unlock POS</span>
                    </button>

                    {{-- Logout: ghost, small icon + text, doesn't compete --}}
                    <button
                        type="button"
                        id="pos-lock-logout-btn"
                        class="inline-flex items-center justify-center gap-2 rounded-xl py-2.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100 hover:text-slate-700"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>

                {{-- Bottom: grouped muted block (Locked X ago, Terminal • Branch, security) --}}
                <div class="mt-6 space-y-0.5 text-center">
                    <p id="lock-meta" class="text-[11px] text-slate-400">Locked <span id="lock-duration">just now</span></p>
                    <p id="lock-terminal-footer" class="text-[11px] text-slate-400"></p>
                    <p class="pt-1 text-[11px] text-slate-400/90">For security, close this window when leaving the counter.</p>
                </div>
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

            if (passwordInput) passwordInput.focus();

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

            axios.get('{{ url("/api/v1/auth/me") }}', {
                headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' }
            }).then(function (r) {
                var d = r.data && r.data.data ? r.data.data : r.data;
                if (!d || !d.user) return;
                var user = d.user;
                var branch = d.branch || user.branch || null;
                var name = user.name || user.email || 'Cashier';
                var roleLabel = (user.role || '').replace(/_/g, ' ');
                roleLabel = roleLabel ? roleLabel.charAt(0).toUpperCase() + roleLabel.slice(1) : '';
                var branchName = branch && branch.name ? branch.name : '';

                if (userLabel) userLabel.textContent = name + (roleLabel ? ' · ' + roleLabel : '');
                if (branchLabel) branchLabel.textContent = branchName || '—';
                if (userInitial && name) userInitial.textContent = (name.trim().charAt(0) || 'C').toUpperCase();
                if (emailInput) emailInput.value = user.email || '';

                axios.get('{{ url("/api/v1/pos/terminal/current") }}', {
                    headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' }
                }).then(function (tr) {
                    var t = tr.data && tr.data.data ? tr.data.data : tr.data;
                    if (!t) return;
                    var code = t.code || ('T' + t.id);
                    var branchLabelText = branchName || '';
                    var cardLine = branchLabelText ? branchLabelText + ' • Terminal ' + code : 'Terminal ' + code;
                    var footerLine = 'Terminal ' + code + (branchLabelText ? ' • ' + branchLabelText : '');
                    if (branchLabel) branchLabel.textContent = cardLine;
                    if (terminalFooter) terminalFooter.textContent = footerLine;
                }).catch(function () {
                    if (branchLabel && branchName) branchLabel.textContent = branchName;
                });
            }).catch(function () {
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
                    axios.post('{{ url("/api/v1/auth/login") }}', { email: email, password: password })
                        .then(function (res) {
                            var data = res.data && res.data.data ? res.data.data : res.data;
                            if (data && data.otp_required) {
                                window.location.href = '{{ route('dashboard.login') }}?step=otp&email=' + encodeURIComponent(email);
                                return;
                            }
                            if (data && data.token) {
                                localStorage.setItem('super_admin_token', data.token);
                                window.location.href = '{{ route('dashboard.pos') }}';
                            } else {
                                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Unable to unlock POS', text: 'Unexpected response from server.' });
                            }
                        })
                        .catch(function (err) {
                            var msg = err.response && err.response.data && err.response.data.message ? err.response.data.message : 'Invalid password. Please try again.';
                            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Unlock failed', text: msg });
                        })
                        .finally(function () { unlockBtn.disabled = false; });
                });
            }

            var lockDurationEl = document.getElementById('lock-duration');
            function updateLockDuration() {
                if (!lockDurationEl) return;
                var ts = localStorage.getItem('pos_locked_at');
                if (!ts) { lockDurationEl.textContent = 'just now'; return; }
                var lockedAt = new Date(parseInt(ts, 10));
                if (isNaN(lockedAt.getTime())) { lockDurationEl.textContent = 'just now'; return; }
                var diffMs = Math.max(0, Date.now() - lockedAt.getTime());
                var diffSec = Math.floor(diffMs / 1000);
                var mins = Math.floor(diffSec / 60);
                var secs = diffSec % 60;
                lockDurationEl.textContent = mins <= 0 ? (secs + 's') : (mins + ' min' + (mins > 1 ? 's' : ''));
            }
            updateLockDuration();
            setInterval(updateLockDuration, 1000);

            if (logoutBtn) {
                logoutBtn.addEventListener('click', function () {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'question', title: 'Logout from POS?', text: 'Your dashboard session will be closed.', showCancelButton: true, confirmButtonText: 'Yes, logout', cancelButtonText: 'Cancel' })
                            .then(function (res) {
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
