@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Login</h1>
    
    <form id="login-form" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" type="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">Login</button>
    </form>

    <p class="text-center text-gray-600 mt-4">Belum punya akun? <a href="/register" class="text-blue-600 hover:underline">Register</a></p>

    <div id="login-msg" class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg hidden"></div>
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
