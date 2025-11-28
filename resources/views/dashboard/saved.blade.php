@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-6">Saved Destinations</h1>

<div id="saved-area" class="min-h-[200px]">
    <div id="saved-loading" class="text-center text-gray-500">Memuat...</div>

    <div id="saved-empty" class="text-center text-gray-500 hidden">
        <img src="https://cdn.jsdelivr.net/gh/heroicons/heroicons@1.0.6/optimized/illustration/empty-state.svg" alt="empty" class="mx-auto mb-4 w-48 opacity-70">
        <h3 class="text-lg font-semibold mb-2">Belum ada destinasi yang disimpan</h3>
        <p class="text-sm text-gray-500 mb-4">Simpan destinasi favoritmu untuk melihatnya lagi nanti.</p>
        <div>
            <a href="/dashboard" class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded-lg mr-2">Kembali</a>
            <a href="/frontend/destinations" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg">Jelajahi Destinations</a>
        </div>
    </div>

    <div id="saved-unauth" class="text-center text-gray-600 hidden">
        <p class="mb-4">Anda perlu login untuk melihat daftar saved destinations.</p>
        <div>
            <a href="/login" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg mr-2">Login</a>
            <a href="/register" class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded-lg">Register</a>
        </div>
    </div>

    <div id="saved-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4 hidden">
        <!-- items rendered here -->
    </div>
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
    delete axios.defaults.headers.common['Authorization'];
    return false;
}

async function loadSaved(){
    document.getElementById('saved-loading').classList.remove('hidden');
    document.getElementById('saved-empty').classList.add('hidden');
    document.getElementById('saved-list').classList.add('hidden');
    document.getElementById('saved-unauth').classList.add('hidden');

    if (!ensureAuthHeader()) {
        // jangan redirect otomatis — tampilkan pesan login agar user tetap melihat page
        document.getElementById('saved-loading').classList.add('hidden');
        document.getElementById('saved-unauth').classList.remove('hidden');
        return;
    }

    try{
        const res = await axios.get('/api/saved');
        const items = Array.isArray(res.data) ? res.data : (res.data.data || []);
        document.getElementById('saved-loading').classList.add('hidden');

        if(!items || items.length===0){
            document.getElementById('saved-empty').classList.remove('hidden');
            return;
        }

        document.getElementById('saved-list').classList.remove('hidden');
        document.getElementById('saved-empty').classList.add('hidden');

        const html = items.map(s=>`
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="${s.destination?.photos?.[0]?.photo_url || 'https://via.placeholder.com/600x350'}" alt="${s.destination?.name || ''}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="font-bold text-lg text-gray-800">${s.destination?.name || '—'}</h3>
                    <p class="text-sm text-gray-500 mt-1">${s.destination?.city?.name || ''}</p>
                    <p class="text-gray-600 text-sm mt-2">${(s.destination?.description||'').slice(0,120)}${(s.destination?.description||'').length>120 ? '...' : ''}</p>
                    <div class="mt-4 flex gap-2">
                        <a href="/frontend/destinations/${s.destination?.id}" class="px-3 py-2 bg-blue-600 text-white rounded-lg">Lihat</a>
                        <button onclick="unsaveDest(${s.id})" class="px-3 py-2 bg-red-600 text-white rounded-lg">Remove</button>
                    </div>
                </div>
            </div>`).join('');
        document.getElementById('saved-list').innerHTML = html;
    }catch(err){
        document.getElementById('saved-loading').classList.add('hidden');
        if (err.response && err.response.status === 401) {
            document.getElementById('saved-unauth').classList.remove('hidden');
            return;
        }
        document.getElementById('saved-empty').classList.remove('hidden');
    }
}

async function unsaveDest(id){
    if(!confirm('Remove dari saved?')) return;
    try{
        await axios.delete('/api/saved/'+id);
        loadSaved();
    }catch(err){
        if (err.response && err.response.status === 401) {
            // token invalid
            clearToken();
            document.getElementById('saved-unauth').classList.remove('hidden');
            document.getElementById('saved-list').classList.add('hidden');
            return;
        }
        alert('Gagal remove');
    }
}

loadSaved();
</script>
@endpush
