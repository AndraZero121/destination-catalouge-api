@php($appPage = true)
@extends('layouts.app')

@section('content')
<div class="app-shell" data-aos="fade-up">
    <div class="app-topbar animate__animated animate__fadeInDown">
        <a href="/dashboard" class="app-back" aria-label="Back">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6"></path>
            </svg>
        </a>
        <div class="text-sm font-semibold text-[#1f5f1c]">Saved</div>
        <a href="/frontend/destinations" class="app-pill">Explore</a>
    </div>

    <div id="saved-area">
        <div id="saved-loading" class="text-center text-slate-500 text-sm">Memuat...</div>
        <div id="saved-empty" class="text-center text-slate-500 text-sm hidden">Belum ada destinasi yang disimpan.</div>
        <div id="saved-unauth" class="text-center text-slate-600 text-sm hidden">
            <p class="mb-3">Anda perlu login untuk melihat daftar saved.</p>
            <div class="flex items-center justify-center gap-2">
                <a href="/login" class="app-btn">Login</a>
                <a href="/register" class="app-btn-outline">Register</a>
            </div>
        </div>
        <div id="saved-list" class="app-list hidden" data-aos="fade-up"></div>
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
            const cover = d.photos?.[0]?.photo_url || 'https://via.placeholder.com/300x300?text=Destination';
            return `
            <div class="app-card app-list-item">
                <div class="app-thumb">
                    <img src="${cover}" alt="${d.name || ''}" class="h-full w-full object-cover">
                </div>
                <div>
                    <div class="text-sm font-semibold text-slate-900">${d.name || '—'}</div>
                    <div class="text-xs text-slate-500 mt-1">${d.city?.name || 'Lokasi'}</div>
                    <a href="/frontend/destinations/${d.id}" class="app-pill mt-2 inline-flex">Detail</a>
                </div>
                <button onclick="unsaveDest(${s.id})" class="app-pill text-rose-600">Remove</button>
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
