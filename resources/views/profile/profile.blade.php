@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-start justify-between flex-wrap gap-3">
        <div>
            <p class="text-sm uppercase text-slate-500 tracking-[0.12em]">Akun</p>
            <h1 class="text-3xl font-semibold text-slate-900 tracking-tight">Profile</h1>
            <p class="text-slate-600 mt-2">Perbarui identitas, foto, dan keamanan akunmu.</p>
        </div>
        <a href="/logout" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-semibold hover:border-rose-400 hover:text-rose-600 transition">Logout cepat</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Info -->
        <div class="md:col-span-1">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-blue-900 to-sky-700 text-white p-6 shadow-xl">
                <div class="absolute inset-0 opacity-40 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.25),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(56,189,248,0.35),transparent_30%)]"></div>
                <div class="relative space-y-4">
                    <div id="profile-photo" class="w-20 h-20 bg-white/20 border border-white/20 rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-black/20"></div>
                    <div id="profile-area" class="text-left space-y-1">Memuat...</div>
                    <p class="text-xs text-white/70 leading-relaxed">Kelola nama tampilan dan foto agar teman perjalananmu mudah mengenalimu.</p>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="md:col-span-2 space-y-6">
            <!-- Quick summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-4 shadow-sm">
                    <p class="text-xs uppercase text-slate-500 tracking-[0.12em]">Latest Comment</p>
                    <div id="latest-comment" class="mt-2 text-sm text-slate-700">Memuat...</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-4 shadow-sm">
                    <p class="text-xs uppercase text-slate-500 tracking-[0.12em]">Likes</p>
                    <div id="likes-count" class="mt-2 text-2xl font-semibold text-slate-900">-</div>
                    <p class="text-xs text-slate-500">Jumlah ulasan yang kamu berikan.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-4 shadow-sm">
                    <p class="text-xs uppercase text-slate-500 tracking-[0.12em]">Bookmarks</p>
                    <div id="bookmark-count" class="mt-2 text-2xl font-semibold text-slate-900">-</div>
                    <p class="text-xs text-slate-500">Destinasi yang disimpan.</p>
                </div>
            </div>

            <!-- Update Profile -->
            <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Update Profile</h2>
                <form id="profile-form" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                        <input id="name" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Photo</label>
                        <input id="photo" type="file" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl" />
                    </div>
                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 rounded-xl transition">Update</button>
                </form>
                <div id="profile-msg" class="mt-4 p-3 rounded-lg hidden"></div>
            </div>

            <!-- Change Password -->
            <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-slate-900 mb-4">Change Password</h2>
                <form id="password-form" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Current Password</label>
                        <input id="current_password" type="password" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">New Password</label>
                        <input id="new_password" type="password" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
                        <input id="password_confirmation" type="password" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl transition">Change Password</button>
                </form>
                <div id="pass-msg" class="mt-4 p-3 rounded-lg hidden"></div>
            </div>
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
        const avatar = document.getElementById('profile-photo');
        const photoUrl = u.photo_url ? (u.photo_url.startsWith('http') ? u.photo_url : '/storage/' + u.photo_url.replace(/^\/+/, '')) : null;
        if (photoUrl) {
            avatar.innerHTML = `<img src="${photoUrl}" alt="Profile photo" class="w-full h-full object-cover rounded-2xl">`;
        } else {
            avatar.innerHTML = '';
            avatar.textContent = ((u.name || '').trim()[0] || '👤').toUpperCase();
        }
        renderSummary(u);
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
