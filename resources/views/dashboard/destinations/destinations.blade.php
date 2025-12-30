@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-sm uppercase text-slate-500 tracking-[0.12em]">Katalog</p>
            <h1 class="text-3xl font-semibold text-slate-900 tracking-tight">Destinations</h1>
            <p class="text-slate-600 mt-2">Jelajahi lokasi menarik dengan detail budget, kategori, dan foto yang tertata.</p>
        </div>
        <div class="flex gap-3">
            <!-- tombol tambah hanya muncul bila user login -->
            <button id="open-add" class="hidden px-4 py-2.5 rounded-xl bg-slate-900 text-white font-semibold shadow-lg shadow-slate-900/10 hover:-translate-y-0.5 transition">+ Tambah Destinasi</button>
            <a href="/dashboard" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-semibold hover:border-blue-400 hover:text-blue-600 transition">Kembali</a>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white/70 backdrop-blur p-4 md:p-6 shadow-sm">
        <div id="dest-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- menampilkan loading / kosong / daftar -->
            <div id="dest-empty" class="col-span-full text-center text-slate-500 hidden">
                <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center text-2xl">📭</div>
                <h3 class="text-lg font-semibold mb-2 text-slate-800">Belum ada destinasi</h3>
                <p class="text-sm text-slate-600 mb-4">Tambah destinasi baru untuk mulai mengisi katalog perjalananmu.</p>
                <div class="flex items-center justify-center gap-3 flex-wrap">
                    <a href="/dashboard" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700">Kembali ke Dashboard</a>
                    <button id="open-add-empty" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-semibold hover:-translate-y-0.5 transition hidden">+ Tambah Destinasi</button>
                </div>
            </div>

            <div id="dest-loading" class="col-span-full text-center text-slate-500">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 text-slate-700">
                    <span class="h-2 w-2 rounded-full bg-blue-500 animate-ping"></span>
                    Memuat destinasi...
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add Destination (sama seperti sebelumnya, hidden by default) -->
<div id="modal-add" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-start justify-center py-12 px-4 hidden z-50">
  <div class="bg-white rounded-2xl w-full max-w-2xl p-6 shadow-2xl border border-slate-100">
    <div class="flex justify-between items-center mb-4">
      <div>
        <p class="text-xs uppercase text-slate-500 tracking-[0.16em]">Tambah</p>
        <h2 class="text-2xl font-semibold text-slate-900">Destinasi baru</h2>
      </div>
      <button id="close-add" class="text-slate-500 hover:text-slate-900 text-xl">✕</button>
    </div>

    <div id="add-msg" class="mb-3 hidden p-3 rounded"></div>

    <form id="add-form" class="space-y-3" enctype="multipart/form-data">
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
        <input name="name" required class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
        <textarea name="description" rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"></textarea>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Budget Min</label>
          <input name="budget_min" type="number" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Budget Max</label>
          <input name="budget_max" type="number" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Fasilitas (koma pisah)</label>
        <input name="facilities" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Latitude</label>
          <input name="latitude" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Longitude</label>
          <input name="longitude" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Photos (multiple)</label>
        <input name="photos[]" type="file" multiple accept="image/*" class="w-full" />
      </div>

      <div class="flex justify-end gap-2 mt-4">
        <button type="button" id="cancel-add" class="px-4 py-2 border border-slate-200 rounded-xl">Batal</button>
        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl font-semibold hover:-translate-y-0.5 transition">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
const modal = document.getElementById('modal-add');
const openAddBtn = document.getElementById('open-add');
const openAddEmptyBtn = document.getElementById('open-add-empty');
const closeAddBtn = document.getElementById('close-add');
const cancelAddBtn = document.getElementById('cancel-add');
const destListEl = document.getElementById('dest-list');
const destLoadingEl = document.getElementById('dest-loading');
const destEmptyEl = document.getElementById('dest-empty');

function ensureAuthHeader() {
    const t = localStorage.getItem('token');
    if (t) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + t;
        return true;
    }
    return false;
}

function showAddIfAuth() {
    if (ensureAuthHeader()) {
        openAddBtn.classList.remove('hidden');
        openAddEmptyBtn.classList.remove('hidden');
    } else {
        openAddBtn.classList.add('hidden');
        openAddEmptyBtn.classList.add('hidden');
    }
}

openAddBtn.addEventListener('click', ()=> modal.classList.remove('hidden'));
openAddEmptyBtn.addEventListener('click', ()=> modal.classList.remove('hidden'));
closeAddBtn.addEventListener('click', ()=> modal.classList.add('hidden'));
cancelAddBtn.addEventListener('click', ()=> modal.classList.add('hidden'));

async function loadDestinations(){
    destLoadingEl.classList.remove('hidden');
    destEmptyEl.classList.add('hidden');
    try{
        ensureAuthHeader(); // optional
        const res = await axios.get('/api/destinations');
        const items = Array.isArray(res.data) ? res.data : (res.data.data || []);
        destLoadingEl.classList.add('hidden');

        if(!items || items.length===0){
            // tampilkan ilustrasi kosong
            destEmptyEl.classList.remove('hidden');
            return;
        }

        destEmptyEl.classList.add('hidden');
        const html = items.map(d=>{
            const cover = d.photos && d.photos[0] ? d.photos[0].photo_url : 'https://via.placeholder.com/900x600?text=Destination';
            const desc = (d.description||'').slice(0,140) + ((d.description||'').length>140 ? '...' : '');
            const budget = `Rp ${new Intl.NumberFormat('id-ID').format(d.budget_min||0)} • Rp ${new Intl.NumberFormat('id-ID').format(d.budget_max||0)}`;
            const reviews = Array.isArray(d.reviews) ? d.reviews : [];
            const avgRating = reviews.length ? (reviews.reduce((a,b)=>a+(b.rating||0),0)/reviews.length).toFixed(1) : null;
            return `
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-sm hover:-translate-y-1 hover:shadow-xl transition transform">
                <div class="relative h-48">
                    <img src="${cover}" alt="${d.name}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/25 to-transparent"></div>
                    <div class="absolute bottom-3 left-3 flex flex-wrap gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/80 text-slate-900 backdrop-blur">${d.category?.name || 'Tanpa kategori'}</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-black/30 text-white backdrop-blur">${d.city?.name || 'Lokasi'}</span>
                        ${avgRating ? `<span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100 backdrop-blur">⭐ ${avgRating}</span>` : ''}
                    </div>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <a href="/frontend/destinations/${d.id}" class="font-semibold text-lg text-slate-900 tracking-tight leading-tight hover:text-blue-700 transition">${d.name}</a>
                        <span class="text-xs uppercase tracking-[0.18em] text-slate-400">ID ${d.id}</span>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">${desc}</p>
                    <div class="flex items-center justify-between text-sm text-slate-700">
                        <p class="font-semibold text-blue-700">${budget}</p>
                        <a href="/frontend/destinations/${d.id}" class="inline-flex items-center gap-2 text-slate-700 font-semibold group-hover:text-blue-700 transition">Detail <span aria-hidden="true">→</span></a>
                    </div>
                </div>
            </div>`;
        }).join('');
        destListEl.innerHTML = html;
        showAddIfAuth();
    }catch(err){
        destLoadingEl.classList.add('hidden');
        destListEl.innerHTML = '<div class="col-span-full text-center text-red-500">Gagal memuat destinasi</div>';
    }
}
loadDestinations();

// Submit add form (sama seperti sebelumnya)
document.getElementById('add-form').addEventListener('submit', async function(e){
    e.preventDefault();
    if (!ensureAuthHeader()) { window.location.href = '/login'; return; }

    const fd = new FormData(this);
    const msgEl = document.getElementById('add-msg');
    msgEl.classList.add('hidden'); msgEl.innerText = '';

    try{
        const res = await axios.post('/api/destinations', fd, { headers: {'Content-Type': 'multipart/form-data'} });
        msgEl.innerText = res.data.message || 'Berhasil ditambahkan';
        msgEl.className = 'mb-3 p-3 rounded bg-green-50 text-green-700';
        msgEl.classList.remove('hidden');
        setTimeout(()=>{ modal.classList.add('hidden'); loadDestinations(); }, 900);
    }catch(err){
        const res = err.response;
        if (res && res.status === 401) { clearToken(); window.location.href = '/login'; return; }
        let text = 'Gagal menyimpan';
        if (res && res.data) {
            if (res.status === 422 && res.data.errors) {
                text = Object.values(res.data.errors).flat().join('; ');
            } else if (res.data.message) {
                text = res.data.message;
            } else if (res.data.error) {
                text = res.data.error;
            }
        }
        msgEl.innerText = text;
        msgEl.className = 'mb-3 p-3 rounded bg-red-50 text-red-700';
        msgEl.classList.remove('hidden');
    }
});
</script>
@endpush
