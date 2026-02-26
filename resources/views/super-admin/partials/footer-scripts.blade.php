@php $base = $midoneBase ?? asset('midone-html.vercel.app'); @endphp
<script src="{{ $base }}/dist/js/vendors/dom.js"></script>
<script src="{{ $base }}/dist/js/vendors/tailwind-merge.js"></script>
<script src="{{ $base }}/dist/js/vendors/lucide.js"></script>
<script src="{{ $base }}/dist/js/vendors/tippy.js"></script>
<script src="{{ $base }}/dist/js/vendors/transition.js"></script>
<script src="{{ $base }}/dist/js/vendors/axios.js"></script>
<script src="{{ $base }}/dist/js/utils/helper.js"></script>
<script src="{{ $base }}/dist/js/vendors/simplebar.js"></script>
<script src="{{ $base }}/dist/js/vendors/dropdown.js"></script>
<script src="{{ $base }}/dist/js/vendors/popper.js"></script>
<script src="{{ $base }}/dist/js/components/base/lucide.js"></script>
<script src="{{ $base }}/dist/js/components/base/tippy.js"></script>
<script src="{{ $base }}/dist/js/components/mobile-menu.js"></script>
<script src="{{ $base }}/dist/js/themes/rubick.js"></script>
<script src="{{ $base }}/dist/js/components/themes/rubick/top-bar.js"></script>
<script src="{{ $base }}/dist/js/vendors/toastify.js"></script>
<script src="{{ $base }}/dist/js/vendors/tom-select.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Redirect to login on 401 Unauthorized (expired/invalid token)
if (typeof axios !== 'undefined') {
    axios.interceptors.response.use(
        function(res) { return res; },
        function(err) {
            if (err.response && err.response.status === 401) {
                localStorage.removeItem('super_admin_token');
                window.location.href = '{{ route("dashboard.login") }}';
                return Promise.reject(err);
            }
            return Promise.reject(err);
        }
    );
}

/** Global: Rubick-style non-sticky Toastify notification (success/error). Use this for all success/error feedback; keep Swal for confirmations only. */
window.showToastNotification = function(type, title, text) {
    var wrap = document.createElement('div');
    wrap.className = 'py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 flex items-start';
    var iconColorClass = type === 'success' ? 'text-emerald-500' : 'text-red-500';
    var iconSvg = type === 'success'
        ? '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 flex-shrink-0 ' + iconColorClass + '"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
        : '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 flex-shrink-0 ' + iconColorClass + '"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
    var safeTitle = (title || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    var safeText = (text || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    wrap.innerHTML = '<span class="flex-shrink-0">' + iconSvg + '</span><div class="ml-4 mr-4 min-w-0"><div class="font-medium">' + safeTitle + '</div><div class="mt-1 text-slate-500 dark:text-slate-400">' + safeText + '</div></div>';
    if (typeof Toastify !== 'undefined') {
        Toastify({ node: wrap, duration: 3000, newWindow: true, close: true, gravity: 'top', position: 'right', stopOnFocus: true }).showToast();
    } else if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: type === 'success' ? 'success' : 'error', title: title, text: text });
    }
};

/** Global: small pulse (backdrop flash) when clicking modal backdrop (does NOT close modal). Animates backdrop only so dialog form stays clickable. */
window.pulseModal = function(modalEl) {
    if (!modalEl) return;
    modalEl.classList.remove('modal-backdrop-pulse');
    void modalEl.offsetWidth;
    modalEl.classList.add('modal-backdrop-pulse');
    setTimeout(function () {
        modalEl.classList.remove('modal-backdrop-pulse');
    }, 260);
};

// Preloader: hide 2 seconds after DOM is ready (sidebar and layout loaded)
function hidePreloader() {
    var el = document.getElementById('super-admin-preloader');
    if (el) {
        el.classList.add('opacity-0');
        el.style.pointerEvents = 'none';
        setTimeout(function() {
            el.style.display = 'none';
        }, 300);
    }
}
function schedulePreloaderHide() {
    setTimeout(hidePreloader, 2000);
}
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', schedulePreloaderHide);
} else {
    schedulePreloaderHide();
}

document.addEventListener('DOMContentLoaded', function() {
    var logoutBtn = document.getElementById('super-admin-logout');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            localStorage.removeItem('super_admin_token');
            window.location.href = '{{ route("dashboard.login") }}';
        });
    }
    // Populate profile and filter sidebar by role/permissions when token exists (API login)
    var token = localStorage.getItem('super_admin_token');
    if (token) {
        axios.get('{{ url("/api/v1/auth/me") }}', { headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' } })
            .then(function(r) {
                var d = r.data && r.data.data ? r.data.data : r.data;
                if (!d || !d.user) return;
                var role = (d.user.role || '');
                var permissions = Array.isArray(d.permissions) ? d.permissions : [];
                // Profile
                var nameEl = document.getElementById('super-admin-profile-name');
                var subEl = document.getElementById('super-admin-profile-subtitle');
                if (nameEl) nameEl.textContent = d.user.name || 'User';
                if (subEl) {
                    var roleLabel = (d.user.role || '').replace(/_/g, ' ');
                    roleLabel = roleLabel ? roleLabel.charAt(0).toUpperCase() + roleLabel.slice(1) : '';
                    var branch = d.branch && d.branch.name ? d.branch.name : '';
                    subEl.textContent = branch ? roleLabel + ' \u2022 ' + branch : roleLabel || 'User';
                }
                var roleBadge = document.getElementById('super-admin-role-badge');
                if (roleBadge) {
                    var rbLabel = (d.user.role || '').replace(/_/g, ' ');
                    roleBadge.textContent = rbLabel ? rbLabel.charAt(0).toUpperCase() + rbLabel.slice(1) : '';
                }
                // Sidebar & mobile menu: show only items allowed for this role (pharmacist = inventory only; manager = no branches/terminals/BIR; etc.)
                document.querySelectorAll('[data-menu-permission]').forEach(function(el) {
                    var p = el.getAttribute('data-menu-permission');
                    var show = false;
                    if (p === 'companies') {
                        show = role === 'super_admin';
                    } else if (p === 'terminals') {
                        show = role === 'super_admin' || role === 'admin';
                    } else if (p === 'bir') {
                        show = role === 'super_admin';
                    } else if (permissions.indexOf('*') >= 0) {
                        show = true;
                    } else {
                        show = permissions.indexOf(p) >= 0;
                    }
                    el.style.display = show ? '' : 'none';
                });
            })
            .catch(function() {});
    }
    // Sidebar collapse toggle (persist in localStorage)
    var sideNav = document.getElementById('super-admin-side-nav');
    var collapseBtn = document.getElementById('side-nav-collapse-btn');
    if (sideNav && collapseBtn) {
        var collapsed = localStorage.getItem('super_admin_side_nav_collapsed') === 'true';
        if (collapsed) sideNav.classList.add('side-nav--collapsed');
        collapseBtn.addEventListener('click', function() {
            var nowCollapsed = sideNav.classList.toggle('side-nav--collapsed');
            localStorage.setItem('super_admin_side_nav_collapsed', nowCollapsed ? 'true' : 'false');
            var icon = collapseBtn.querySelector('[data-lucide]');
            var title = collapseBtn.querySelector('.side-menu__title');
            if (icon && title) {
                if (nowCollapsed) { icon.setAttribute('data-lucide', 'panel-right-open'); title.textContent = 'Expand'; }
                else { icon.setAttribute('data-lucide', 'panel-left-close'); title.textContent = 'Collapse'; }
                if (typeof lucide !== 'undefined' && lucide.createIcons) lucide.createIcons();
            }
        });
        if (collapsed && collapseBtn.querySelector('.side-menu__title')) collapseBtn.querySelector('.side-menu__title').textContent = 'Expand';
        if (collapsed && collapseBtn.querySelector('[data-lucide]')) collapseBtn.querySelector('[data-lucide]').setAttribute('data-lucide', 'panel-right-open');
    }
    // Notification badge: fetch dashboard summary when logged in and update badge
    var badgeEl = document.getElementById('notification-badge');
    if (token && badgeEl) {
        axios.get('{{ url("/api/v1/dashboard/summary") }}', { params: { period: 'today' }, headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' } })
            .then(function(r) {
                var d = r.data && r.data.data ? r.data.data : r.data;
                if (d) {
                    var n = (d.low_stock_count || 0) + (d.expiring_soon_count || 0);
                    badgeEl.textContent = n > 99 ? '99+' : n;
                    badgeEl.parentElement.classList.toggle('hidden', n === 0);
                }
            })
            .catch(function() {});
    }
});
</script>
@stack('scripts')
