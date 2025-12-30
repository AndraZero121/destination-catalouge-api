@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-start justify-between flex-wrap gap-3">
        <div>
            <p class="text-sm uppercase text-slate-500 tracking-[0.12em]">Wishlist</p>
            <h1 class="text-3xl font-semibold text-slate-900 tracking-tight">Saved Destinations</h1>
            <p class="text-slate-600 mt-2">Kumpulkan destinasi favorit dan buka lagi saat siap berangkat.</p>
        </div>
        <a href="/frontend/destinations" class="px-4 py-2.5 rounded-xl bg-slate-900 text-white font-semibold shadow-md shadow-slate-900/10 hover:-translate-y-0.5 transition">Tambah inspirasi</a>
    </div>

    <div id="saved-area" class="min-h-[200px]">
        <div id="saved-loading" class="text-center text-slate-500">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 text-slate-700">
                <span class="h-2 w-2 rounded-full bg-blue-500 animate-ping"></span>
                Memuat...
            </div>
        </div>

        <div id="saved-empty" class="text-center text-slate-500 hidden">
            <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center text-2xl">🧭</div>
            <h3 class="text-lg font-semibold mb-2 text-slate-800">Belum ada destinasi yang disimpan</h3>
            <p class="text-sm text-slate-600 mb-4">Simpan destinasi favoritmu untuk melihatnya lagi nanti.</p>
            <div class="flex items-center justify-center gap-3 flex-wrap">
                <a href="/dashboard" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700">Kembali</a>
                <a href="/frontend/destinations" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-semibold hover:-translate-y-0.5 transition">Jelajahi Destinations</a>
            </div>
        </div>

        <div id="saved-unauth" class="text-center text-slate-600 hidden">
            <p class="mb-4">Anda perlu login untuk melihat daftar saved destinations.</p>
            <div class="flex items-center justify-center gap-3 flex-wrap">
                <a href="/login" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-semibold">Login</a>
                <a href="/register" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700">Register</a>
            </div>
        </div>

        <div id="saved-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4 hidden">
            <!-- items rendered here -->
        </div>
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

        const html = items.map(s=>{
            const d = s.destination || {};
            const cover = d.photos?.[0]?.photo_url || 'https://via.placeholder.com/900x600?text=Destination';
            const desc = (d.description||'').slice(0,120) + ((d.description||'').length>120 ? '...' : '');
            return `
            <div class="group rounded-2xl overflow-hidden border border-slate-200 bg-white/80 backdrop-blur shadow-sm hover:-translate-y-1 hover:shadow-xl transition transform">
                <div class="relative h-44">
                    <img src="${cover}" alt="${d.name || ''}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-slate-900/20 to-transparent"></div>
                    <div class="absolute bottom-3 left-3 flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/80 text-slate-900 backdrop-blur">${d.city?.name || 'Lokasi'}</span>
                    </div>
                </div>
                <div class="p-5 space-y-3">
                    <h3 class="font-semibold text-lg text-slate-900 tracking-tight leading-tight">${d.name || '—'}</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">${desc}</p>
                    <div class="flex items-center justify-between gap-2">
                        <a href="/frontend/destinations/${d.id}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:-translate-y-0.5 transition">Lihat</a>
                        <button onclick="unsaveDest(${s.id})" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-700 text-sm font-semibold hover:border-rose-400 hover:text-rose-600 transition">Remove</button>
                    </div>
                </div>
            </div>`;
        }).join('');
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
