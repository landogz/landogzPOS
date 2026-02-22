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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
                // Sidebar & mobile menu: show only items allowed for this role (pharmacist = inventory only; manager = no branches/terminals/BIR; etc.)
                document.querySelectorAll('[data-menu-permission]').forEach(function(el) {
                    var p = el.getAttribute('data-menu-permission');
                    var show = false;
                    if (p === 'terminals' || p === 'companies') {
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
});
</script>
@stack('scripts')
