@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
    <div class="space-y-4">
        <p class="text-sm uppercase text-slate-500 tracking-[0.12em]">Mulai</p>
        <h1 class="text-3xl font-semibold text-slate-900 tracking-tight">Buat akun baru</h1>
        <p class="text-slate-600">Simpan wishlist destinasi, buat review, dan kelola profil dengan tampilan yang lebih rapi.</p>
        <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-4 text-sm text-slate-700">
            <p class="font-semibold text-slate-900 mb-1">Saran</p>
            <p>Gunakan password unik dan simpan nomor ponsel aktif untuk pemulihan akun.</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900 mb-4">Register</h2>
        
        <form id="register-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                <input id="name" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input id="email" type="email" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                <input id="phone" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input id="password" type="password" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
                <input id="password_confirmation" type="password" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
            </div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 rounded-xl transition">Register</button>
        </form>

        <p class="text-center text-slate-600 mt-4">Sudah punya akun? <a href="/login" class="text-blue-600 hover:underline">Login</a></p>

        <div id="reg-msg" class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg hidden"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('register-form').addEventListener('submit', async function(e){
    e.preventDefault();
    const payload = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        password: document.getElementById('password').value,
        password_confirmation: document.getElementById('password_confirmation').value
    };
    try {
        const res = await axios.post('/api/register', payload);
        setToken(res.data.access_token);
        window.location.href = '/dashboard';
    } catch (err) {
        const msg = document.getElementById('reg-msg');
        msg.innerText = err.response?.data?.message || 'Pendaftaran gagal';
        msg.classList.remove('hidden');
    }
});
</script>
@endpush
