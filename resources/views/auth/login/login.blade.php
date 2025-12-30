@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
    <div class="space-y-4">
        <p class="text-sm uppercase text-slate-500 tracking-[0.12em]">Selamat datang</p>
        <h1 class="text-3xl font-semibold text-slate-900 tracking-tight">Masuk ke Destination Catalogue</h1>
        <p class="text-slate-600">Nikmati pengalaman jelajah destinasi yang lebih terkurasi dan simpan inspirasi liburanmu.</p>
        <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-4 text-sm text-slate-700">
            <p class="font-semibold text-slate-900 mb-1">Tips cepat</p>
            <p>Gunakan email yang sama dengan akun mobilemu agar review dan simpanan selalu sinkron.</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900 mb-4">Login</h2>
        
        <form id="login-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input id="email" type="email" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input id="password" type="password" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
            </div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 rounded-xl transition">Login</button>
        </form>

        <p class="text-center text-slate-600 mt-4">Belum punya akun? <a href="/register" class="text-blue-600 hover:underline">Register</a></p>

        <div id="login-msg" class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg hidden"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('login-form').addEventListener('submit', async function(e){
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    try {
        const res = await axios.post('/api/login', { login: email, password: password });
        setToken(res.data.access_token);
        window.location.href = '/dashboard';
    } catch (err) {
        const msg = document.getElementById('login-msg');
        msg.innerText = err.response?.data?.message || 'Login gagal';
        msg.classList.remove('hidden');
    }
});
</script>
@endpush
