@php $base = $midoneBase ?? asset('midone-html.vercel.app'); @endphp
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
                        <img class="w-6" src="{{ asset('images/logo.png') }}" alt="Landogz POS">
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
                <div class="my-10 flex h-screen py-5 xl:my-0 xl:h-auto xl:py-0">
                    <div class="mx-auto my-auto w-full rounded-md bg-white px-5 py-8 shadow-md dark:bg-darkmode-600 sm:w-3/4 sm:px-8 lg:w-2/4 xl:ml-20 xl:w-auto xl:bg-transparent xl:p-0 xl:shadow-none">
                        <h2 class="intro-x text-center text-2xl font-bold xl:text-left xl:text-3xl">Sign In</h2>
                        <div class="intro-x mt-2 text-center text-slate-400 xl:hidden">Sign in to Super Admin. Manage your POS in one place.</div>
                        <form id="super-admin-login-form" class="intro-x mt-8">
                            <input type="email" name="email" placeholder="Email" required
                                class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary block px-4 py-3 xl:min-w-[350px] dark:bg-darkmode-800 dark:border-transparent">
                            <input type="password" name="password" placeholder="Password" required
                                class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary block px-4 py-3 mt-4 xl:min-w-[350px] dark:bg-darkmode-800 dark:border-transparent">
                            <div class="intro-x mt-5 text-center xl:mt-8 xl:text-left">
                                <button type="submit" id="login-btn" class="transition duration-200 border shadow-sm inline-flex items-center justify-center rounded-md font-medium cursor-pointer bg-primary border-primary text-white w-full px-4 py-3 xl:w-32">Login</button>
                            </div>
                        </form>
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
        document.getElementById('super-admin-login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = document.getElementById('login-btn');
            btn.disabled = true;
            var formData = new FormData(this);
            axios.post('{{ url("/api/v1/auth/login") }}', {
                email: formData.get('email'),
                password: formData.get('password')
            }).then(function(res) {
                if (res.data && (res.data.token || res.data.data?.token)) {
                    var token = res.data.token || (res.data.data && res.data.data.token);
                    if (token) localStorage.setItem('super_admin_token', token);
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
    </script>
</body>
</html>
