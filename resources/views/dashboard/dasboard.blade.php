@extends('layouts.app')

@section('content')
<div class="space-y-10">
    <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-blue-900 to-sky-700 text-white shadow-xl">
        <div class="absolute inset-0 opacity-40 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.25),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(56,189,248,0.35),transparent_30%)]"></div>
        <div class="relative p-8 md:p-10 flex flex-col md:flex-row md:items-center gap-8">
            <div class="flex-1 space-y-4">
                <p class="text-sm uppercase tracking-[0.12em] text-white/70">Dashboard</p>
                <h1 class="text-3xl md:text-4xl font-semibold tracking-tight">Panel perjalananmu</h1>
                <p class="text-white/80 max-w-2xl">Pantau destinasi, review, dan simpanan favoritmu dalam satu tampilan yang ringkas.</p>
                <div class="p-4 rounded-xl bg-white/10 border border-white/20 backdrop-blur">
                    <div id="profile-summary" class="text-white">Memuat profil...</div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="/frontend/destinations" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-slate-900 font-semibold hover:-translate-y-0.5 transition transform shadow-lg shadow-slate-900/10">
                        Mulai jelajah
                    </a>
                    <a href="/frontend/saved" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-white/40 text-white hover:bg-white/10 transition">Lihat yang disimpan</a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 w-full md:w-80">
                <div class="rounded-xl bg-white/10 border border-white/20 p-4">
                    <p class="text-white/60 text-sm">Destinasi</p>
                    <p class="text-2xl font-semibold mt-2">Koleksi kurasi</p>
                    <p class="text-white/60 text-xs mt-1">Pilih yang sesuai budget & vibe-mu.</p>
                </div>
                <div class="rounded-xl bg-white/10 border border-white/20 p-4">
                    <p class="text-white/60 text-sm">Review</p>
                    <p class="text-2xl font-semibold mt-2">Punya suara</p>
                    <p class="text-white/60 text-xs mt-1">Bagikan pengalamanmu ke traveler lain.</p>
                </div>
                <div class="rounded-xl bg-white/10 border border-white/20 p-4">
                    <p class="text-white/60 text-sm">Saved</p>
                    <p class="text-2xl font-semibold mt-2">Simpan cepat</p>
                    <p class="text-white/60 text-xs mt-1">Satukan wishlist perjalanan.</p>
                </div>
                <div class="rounded-xl bg-white/10 border border-white/20 p-4">
                    <p class="text-white/60 text-sm">Profil</p>
                    <p class="text-2xl font-semibold mt-2">Kendali penuh</p>
                    <p class="text-white/60 text-xs mt-1">Kelola identitas dan keamananmu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <a href="/frontend/destinations" class="group border border-slate-200 rounded-xl p-6 bg-white/70 backdrop-blur shadow-sm hover:-translate-y-1 hover:shadow-lg transition transform">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-slate-900">Destinations</h3>
                <span class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-600 to-cyan-400 text-white flex items-center justify-center shadow-md shadow-blue-400/30">🗺️</span>
            </div>
            <p class="text-sm text-slate-600 mt-3 leading-relaxed">Kurasi inspirasi liburan dengan detail budget, lokasi, dan foto.</p>
            <div class="mt-4 inline-flex items-center gap-2 text-blue-600 font-medium">Jelajahi <span aria-hidden="true">→</span></div>
        </a>
        <a href="/reviews" class="group border border-slate-200 rounded-xl p-6 bg-white/70 backdrop-blur shadow-sm hover:-translate-y-1 hover:shadow-lg transition transform">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-slate-900">My Reviews</h3>
                <span class="h-10 w-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-400 text-white flex items-center justify-center shadow-md shadow-amber-400/30">⭐</span>
            </div>
            <p class="text-sm text-slate-600 mt-3 leading-relaxed">Simpan catatan pengalamanmu untuk membantu traveler lain.</p>
            <div class="mt-4 inline-flex items-center gap-2 text-amber-600 font-medium">Kelola review <span aria-hidden="true">→</span></div>
        </a>
        <a href="/frontend/saved" class="group border border-slate-200 rounded-xl p-6 bg-white/70 backdrop-blur shadow-sm hover:-translate-y-1 hover:shadow-lg transition transform">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-slate-900">Saved</h3>
                <span class="h-10 w-10 rounded-full bg-gradient-to-br from-pink-500 to-rose-400 text-white flex items-center justify-center shadow-md shadow-rose-400/30">❤️</span>
            </div>
            <p class="text-sm text-slate-600 mt-3 leading-relaxed">Lacak wishlist perjalananmu tanpa kehilangan inspirasi.</p>
            <div class="mt-4 inline-flex items-center gap-2 text-rose-600 font-medium">Buka daftar <span aria-hidden="true">→</span></div>
        </a>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <span class="h-10 w-10 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center">💡</span>
                <div>
                    <p class="text-sm text-slate-500 uppercase tracking-[0.08em]">Tips cepat</p>
                    <p class="text-lg font-semibold text-slate-900">Temukan spot terbaik</p>
                </div>
            </div>
            <ul class="space-y-3 text-sm text-slate-700 leading-relaxed">
                <li>• Filter destinasi lewat budget dan kota untuk shortlist yang lebih relevan.</li>
                <li>• Manfaatkan fitur saved untuk membuat rute akhir sebelum berangkat.</li>
                <li>• Tinggalkan review setelah trip untuk membangun rekomendasi komunitas.</li>
            </ul>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <span class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center">🛡️</span>
                <div>
                    <p class="text-sm text-slate-500 uppercase tracking-[0.08em]">Privasi & kontrol</p>
                    <p class="text-lg font-semibold text-slate-900">Kelola akun tanpa repot</p>
                </div>
            </div>
            <p class="text-sm text-slate-700 leading-relaxed">Perbarui nama, foto, serta kata sandi di halaman profil. Logout cepat tersedia di menu atas untuk menjaga sesi tetap aman.</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="/frontend/profile" class="px-4 py-2 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">Buka profil</a>
                <a href="/logout" class="px-4 py-2 rounded-full border border-slate-200 text-slate-700 text-sm font-semibold hover:border-blue-400 hover:text-blue-600 transition">Logout</a>
            </div>
        </div>
    </section>
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
