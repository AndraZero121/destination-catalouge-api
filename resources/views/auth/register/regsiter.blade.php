@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Register</h1>
    
    <form id="register-form" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" type="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input id="phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">Register</button>
    </form>

    <p class="text-center text-gray-600 mt-4">Sudah punya akun? <a href="/login" class="text-blue-600 hover:underline">Login</a></p>

    <div id="reg-msg" class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg hidden"></div>
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
