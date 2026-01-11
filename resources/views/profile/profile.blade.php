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
        <div class="text-sm font-semibold text-[#1f5f1c]">Profile</div>
        <div class="w-9"></div>
    </div>

    <div id="profile-unauth" class="app-card p-4 text-center hidden" data-aos="zoom-in">
        <div class="text-sm font-semibold text-slate-900">Anda belum login</div>
        <p class="text-xs text-slate-500 mt-2">Masuk untuk melihat profil dan mengelola akun.</p>
        <div class="mt-4 flex flex-col gap-2">
            <a href="/login" class="app-btn">Login</a>
            <a href="/register" class="app-btn-outline">Register</a>
        </div>
    </div>

    <div id="profile-content">
        <div class="app-card p-5 text-center bg-[#0f4d1f] text-white" data-aos="fade-up">
            <div id="profile-photo" class="mx-auto h-20 w-20 rounded-full bg-white/20 border border-white/30 flex items-center justify-center text-2xl"></div>
            <div id="profile-area" class="mt-3 space-y-1">Memuat...</div>
            <div class="mt-4 flex justify-center gap-3 text-xs">
                <a href="/frontend/destinations" class="px-3 py-1 rounded-full bg-white/15">Explore</a>
                <a href="/frontend/saved" class="px-3 py-1 rounded-full bg-white/15">Saved</a>
                <a href="/reviews" class="px-3 py-1 rounded-full bg-white/15">Reviews</a>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mt-4" data-aos="fade-up">
            <div class="app-card p-3 text-center">
                <div class="text-xs text-slate-500">Reviews</div>
                <div id="likes-count" class="text-lg font-semibold text-slate-900">-</div>
            </div>
            <div class="app-card p-3 text-center">
                <div class="text-xs text-slate-500">Saved</div>
                <div id="bookmark-count" class="text-lg font-semibold text-slate-900">-</div>
            </div>
            <div class="app-card p-3 text-center">
                <div class="text-xs text-slate-500">Latest</div>
                <div id="latest-comment" class="text-xs text-slate-700 mt-1">Memuat...</div>
            </div>
        </div>

        <div class="app-card p-4 mt-4 space-y-3" data-aos="fade-up">
            <div class="flex items-center justify-between">
                <div class="text-sm font-semibold text-[#1f5f1c]">Aktivitas Terbaru</div>
                <a href="/reviews" class="text-xs text-[#1f5f1c] font-semibold">Lihat semua</a>
            </div>
            <div id="recent-reviews" class="space-y-3 text-xs text-slate-600">
                <div class="app-card p-3">
                    <div class="font-semibold text-slate-900">Belum ada review</div>
                    <div class="mt-1 text-slate-500">Bagikan pengalamanmu agar traveler lain terbantu.</div>
                </div>
            </div>
        </div>

        <div class="app-card p-4 mt-4 space-y-3" data-aos="fade-up">
            <div class="text-sm font-semibold text-[#1f5f1c]">Edit Profile</div>
            <form id="profile-form" enctype="multipart/form-data" class="space-y-3">
                <input id="name" class="auth-input" placeholder="Name" />
                <input id="photo" type="file" class="auth-input" />
                <button type="submit" class="app-btn">Save</button>
            </form>
            <div id="profile-msg" class="mt-2 p-3 rounded-lg hidden"></div>
        </div>

        <div class="app-card p-4 mt-4 space-y-3" data-aos="fade-up">
            <div class="text-sm font-semibold text-[#1f5f1c]">Edit Password</div>
            <form id="password-form" class="space-y-3">
                <input id="current_password" type="password" required class="auth-input" placeholder="Current Password" />
                <input id="new_password" type="password" required class="auth-input" placeholder="New Password" />
                <input id="password_confirmation" type="password" required class="auth-input" placeholder="Confirm Password" />
                <button type="submit" class="app-btn">Save</button>
            </form>
            <div id="pass-msg" class="mt-2 p-3 rounded-lg hidden"></div>
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
        document.getElementById('profile-content').classList.add('hidden');
        document.getElementById('profile-unauth').classList.remove('hidden');
        return;
    }

    try{
        const res = await axios.get('/api/profile');
        const u = res.data;
        document.getElementById('profile-content').classList.remove('hidden');
        document.getElementById('profile-unauth').classList.add('hidden');
        document.getElementById('profile-area').innerHTML = `<div class="text-base font-semibold">${u.name}</div><div class="text-xs text-white/70">${u.email}</div>`;
        document.getElementById('name').value = u.name || '';
        const avatar = document.getElementById('profile-photo');
        const photoUrl = u.photo_url ? (u.photo_url.startsWith('http') ? u.photo_url : '/storage/' + u.photo_url.replace(/^\/+/, '')) : null;
        if (photoUrl) {
            avatar.innerHTML = `<img src="${photoUrl}" alt="Profile photo" class="w-full h-full object-cover rounded-full">`;
        } else {
            avatar.innerHTML = '';
            avatar.textContent = ((u.name || '').trim()[0] || '👤').toUpperCase();
        }
        renderSummary(u);
    }catch(err){
        if (err.response && (err.response.status === 401 || err.response.data?.message === 'Unauthenticated.')) {
            clearToken();
            document.getElementById('profile-content').classList.add('hidden');
            document.getElementById('profile-unauth').classList.remove('hidden');
            return;
        }
        document.getElementById('profile-area').innerText = 'Gagal memuat profil';
    }
}
loadProfile();

function renderSummary(u){
    const latest = u.latest_review || null;
    const commentEl = document.getElementById('latest-comment');
    if(latest){
        const desc = latest.description || 'Tanpa komentar';
        const dest = latest.destination?.name || 'Destinasi';
        commentEl.innerHTML = `<p class="font-medium text-slate-900">${dest}</p><p class="text-slate-700 mt-1 text-sm">${desc}</p>`;
    } else {
        commentEl.textContent = 'Belum ada komentar.';
    }
    const likesCount = u.reviews_count ?? (u.reviews ? u.reviews.length : 0);
    document.getElementById('likes-count').textContent = likesCount;
    const bookmarkCount = u.saved_count ?? (u.saved_destinations ? u.saved_destinations.length : 0);
    document.getElementById('bookmark-count').textContent = bookmarkCount;

    const recentWrap = document.getElementById('recent-reviews');
    if (!recentWrap) return;
    if (latest) {
        const dest = latest.destination?.name || 'Destinasi';
        const desc = latest.description || 'Tanpa komentar';
        recentWrap.innerHTML = `
            <div class="app-card p-3">
                <div class="font-semibold text-slate-900">${dest}</div>
                <div class="mt-1 text-slate-500">${desc}</div>
            </div>
        `;
    }
}

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
