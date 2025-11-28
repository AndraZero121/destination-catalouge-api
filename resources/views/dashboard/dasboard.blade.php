@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>

<div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6">
    <div id="profile-summary" class="text-gray-700">Memuat profil...</div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <a href="/frontend/destinations" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
        <h3 class="text-xl font-bold">🗺️ Destinations</h3>
        <p class="text-sm mt-2">Jelajahi destinasi wisata</p>
    </a>
    <a href="/reviews" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
        <h3 class="text-xl font-bold">⭐ My Reviews</h3>
        <p class="text-sm mt-2">Review destinasi favoritmu</p>
    </a>
    <a href="/frontend/saved" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
        <h3 class="text-xl font-bold">❤️ Saved</h3>
        <p class="text-sm mt-2">Destinasi yang disimpan</p>
    </a>
</div>
@endsection

@push('scripts')
<script>
function ensureAuthHeader() {
    const t = localStorage.getItem('token');
    if (t) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + t;
        return true;
    }
    return false;
}

async function loadProfileSummary(){
    if (!ensureAuthHeader()) {
        document.getElementById('profile-summary').innerText = 'Tidak terautentikasi. Silakan login.';
        return;
    }

    try{
        const res = await axios.get('/api/profile');
        const u = res.data;
        document.getElementById('profile-summary').innerHTML = `<strong>${u.name}</strong><p class="text-sm text-gray-600 mt-1">${u.email}</p>`;
    }catch(err){
        if (err.response && err.response.status === 401) { clearToken(); window.location.href = '/login'; return; }
        document.getElementById('profile-summary').innerText = 'Tidak terautentikasi. Silakan login.';
    }
}
loadProfileSummary();
</script>
@endpush
