@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-6">Profile</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Profile Info -->
    <div class="md:col-span-1">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-md">
            <div id="profile-photo" class="w-20 h-20 bg-white rounded-full mx-auto mb-4"></div>
            <div id="profile-area" class="text-center">Memuat...</div>
        </div>
    </div>

    <!-- Forms -->
    <div class="md:col-span-2 space-y-6">
        <!-- Update Profile -->
        <div class="bg-white border border-gray-200 p-6 rounded-lg">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Update Profile</h2>
            <form id="profile-form" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                    <input id="photo" type="file" class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">Update</button>
            </form>
            <div id="profile-msg" class="mt-4 p-3 rounded-lg hidden"></div>
        </div>

        <!-- Change Password -->
        <div class="bg-white border border-gray-200 p-6 rounded-lg">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Change Password</h2>
            <form id="password-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input id="current_password" type="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input id="new_password" type="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input id="password_confirmation" type="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
                </div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition">Change Password</button>
            </form>
            <div id="pass-msg" class="mt-4 p-3 rounded-lg hidden"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* memastikan header Authorization berdasarkan token di localStorage,
   jika tidak ada token -> redirect ke login */
function ensureAuthHeader() {
    const t = localStorage.getItem('token');
    if (t) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + t;
        return true;
    }
    return false;
}

async function loadProfile(){
    if (!ensureAuthHeader()) {
        // tidak login -> arahkan ke halaman login
        window.location.href = '/login';
        return;
    }

    try{
        const res = await axios.get('/api/profile');
        const u = res.data;
        document.getElementById('profile-area').innerHTML = `<strong>${u.name}</strong><div class="muted">${u.email}</div>`;
        document.getElementById('name').value = u.name || '';
    }catch(err){
        if (err.response && (err.response.status === 401 || err.response.data?.message === 'Unauthenticated.')) {
            clearToken();
            window.location.href = '/login';
            return;
        }
        document.getElementById('profile-area').innerText = 'Gagal memuat profil';
    }
}
loadProfile();

document.getElementById('profile-form').addEventListener('submit', async function(e){
    e.preventDefault();
    if (!ensureAuthHeader()) { window.location.href = '/login'; return; }

    const fd = new FormData();
    fd.append('name', document.getElementById('name').value);
    const file = document.getElementById('photo').files[0];
    if(file) fd.append('photo', file);
    try{
        await axios.post('/api/profile/update', fd, { headers: {'Content-Type': 'multipart/form-data'} });
        const msg = document.getElementById('profile-msg');
        msg.innerText = '✓ Profile updated';
        msg.classList.remove('hidden');
        msg.classList.add('bg-green-50', 'text-green-700');
        loadProfile();
    }catch(err){
        if (err.response && err.response.status === 401) { clearToken(); window.location.href='/login'; return; }
        const msg = document.getElementById('profile-msg');
        msg.innerText = err.response?.data?.message || 'Gagal update';
        msg.classList.remove('hidden');
        msg.classList.add('bg-red-50', 'text-red-700');
    }
});

document.getElementById('password-form').addEventListener('submit', async function(e){
    e.preventDefault();
    if (!ensureAuthHeader()) { window.location.href = '/login'; return; }
    try{
        await axios.post('/api/profile/password', {
            current_password: document.getElementById('current_password').value,
            password: document.getElementById('new_password').value,
            password_confirmation: document.getElementById('password_confirmation').value
        });
        const msg = document.getElementById('pass-msg');
        msg.innerText = '✓ Password updated';
        msg.classList.remove('hidden');
        msg.classList.add('bg-green-50', 'text-green-700');
    }catch(err){
        if (err.response && err.response.status === 401) { clearToken(); window.location.href='/login'; return; }
        const msg = document.getElementById('pass-msg');
        msg.innerText = err.response?.data?.message || 'Gagal ganti password';
        msg.classList.remove('hidden');
        msg.classList.add('bg-red-50', 'text-red-700');
    }
});
</script>
@endpush
