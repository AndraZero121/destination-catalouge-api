@php($appPage = true)
@extends('layouts.app')

@section('content')
<div class="app-shell" data-aos="fade-up">
    <div id="province-view">
        <div class="app-topbar animate__animated animate__fadeInDown">
            <a href="/dashboard" class="app-back" aria-label="Back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6"></path>
                </svg>
            </a>
            <div class="text-sm font-semibold text-[#1f5f1c]">Provinsi</div>
            <div class="flex items-center gap-2">
                <button class="app-back" data-app-menu-toggle aria-label="Menu">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16"></path>
                        <path d="M4 12h16"></path>
                        <path d="M4 18h16"></path>
                    </svg>
                </button>
                <button id="open-add" class="app-pill hidden">+ Add</button>
            </div>
        </div>
        <div id="province-list" class="app-list" data-aos="fade-up"></div>
        <div id="province-empty" class="text-center text-slate-500 text-sm hidden">Belum ada provinsi.</div>
        <div id="province-loading" class="text-center text-slate-500 text-sm">Memuat provinsi...</div>
    </div>

    <div id="city-view" class="hidden">
        <div class="app-topbar animate__animated animate__fadeInDown">
            <button id="back-province" class="app-back" aria-label="Back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6"></path>
                </svg>
            </button>
            <div id="city-title" class="text-sm font-semibold text-[#1f5f1c]">Kota</div>
            <div class="flex items-center gap-2">
                <button class="app-back" data-app-menu-toggle aria-label="Menu">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16"></path>
                        <path d="M4 12h16"></path>
                        <path d="M4 18h16"></path>
                    </svg>
                </button>
                <span class="app-pill">Kota</span>
            </div>
        </div>
        <div id="city-list" class="app-list" data-aos="fade-up"></div>
        <div id="city-empty" class="text-center text-slate-500 text-sm hidden">Belum ada kota.</div>
        <div id="city-loading" class="text-center text-slate-500 text-sm">Memuat kota...</div>
    </div>

    <div id="dest-view" class="hidden">
        <div class="app-topbar animate__animated animate__fadeInDown">
            <button id="back-city" class="app-back" aria-label="Back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6"></path>
                </svg>
            </button>
            <div id="dest-title" class="text-sm font-semibold text-[#1f5f1c]">Kota</div>
            <div class="flex items-center gap-2">
                <button class="app-back" data-app-menu-toggle aria-label="Menu">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16"></path>
                        <path d="M4 12h16"></path>
                        <path d="M4 18h16"></path>
                    </svg>
                </button>
                <span id="dest-count" class="app-pill">0</span>
            </div>
        </div>
        <div id="dest-list" class="app-list" data-aos="fade-up"></div>
        <div id="dest-empty" class="text-center text-slate-500 text-sm hidden">Belum ada destinasi.</div>
        <div id="dest-loading" class="text-center text-slate-500 text-sm">Memuat destinasi...</div>
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
const provinceView = document.getElementById('province-view');
const cityView = document.getElementById('city-view');
const destView = document.getElementById('dest-view');
const provinceListEl = document.getElementById('province-list');
const provinceLoadingEl = document.getElementById('province-loading');
const provinceEmptyEl = document.getElementById('province-empty');
const cityListEl = document.getElementById('city-list');
const cityLoadingEl = document.getElementById('city-loading');
const cityEmptyEl = document.getElementById('city-empty');
const cityTitleEl = document.getElementById('city-title');
const destTitleEl = document.getElementById('dest-title');
const destCountEl = document.getElementById('dest-count');
const backProvinceBtn = document.getElementById('back-province');
const backCityBtn = document.getElementById('back-city');

let selectedProvince = null;
let selectedCity = null;

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
        if (openAddEmptyBtn) {
            openAddEmptyBtn.classList.remove('hidden');
        }
    } else {
        openAddBtn.classList.add('hidden');
        if (openAddEmptyBtn) {
            openAddEmptyBtn.classList.add('hidden');
        }
    }
}

openAddBtn.addEventListener('click', ()=> modal.classList.remove('hidden'));
if (openAddEmptyBtn) {
    openAddEmptyBtn.addEventListener('click', ()=> modal.classList.remove('hidden'));
}
closeAddBtn.addEventListener('click', ()=> modal.classList.add('hidden'));
cancelAddBtn.addEventListener('click', ()=> modal.classList.add('hidden'));

async function loadDestinations(){
    destLoadingEl.classList.remove('hidden');
    destEmptyEl.classList.add('hidden');
    try{
        ensureAuthHeader(); // optional
        const params = {};
        if (selectedProvince) params.province_id = selectedProvince.id;
        if (selectedCity) params.city_id = selectedCity.id;
        const res = await axios.get('/api/destinations', { params });
        const items = Array.isArray(res.data) ? res.data : (res.data.data || []);
        destLoadingEl.classList.add('hidden');

        if(!items || items.length===0){
            // tampilkan ilustrasi kosong
            destEmptyEl.classList.remove('hidden');
            destCountEl.textContent = '0';
            return;
        }

        destEmptyEl.classList.add('hidden');
        destCountEl.textContent = `${items.length}`;
        const html = items.map(d=>{
            const cover = d.photos && d.photos[0] ? d.photos[0].photo_url : 'https://via.placeholder.com/300x300?text=Destination';
            const reviews = Array.isArray(d.reviews) ? d.reviews : [];
            const avgRating = reviews.length ? (reviews.reduce((a,b)=>a+(b.rating||0),0)/reviews.length).toFixed(1) : null;
            return `
            <a href="/frontend/destinations/${d.id}" class="app-list-item">
                <div class="app-thumb">
                    <img src="${cover}" alt="${d.name}" class="h-full w-full object-cover">
                </div>
                <div>
                    <div class="text-sm font-semibold text-slate-900">${d.name}</div>
                    <div class="text-xs text-slate-500 mt-1">${d.city?.name || 'Lokasi'}</div>
                    <div class="app-rating mt-1">${avgRating ? `${avgRating} ★` : 'No rating'}</div>
                </div>
                <div class="app-arrow">›</div>
            </a>`;
        }).join('');
        destListEl.innerHTML = html;
        showAddIfAuth();
    }catch(err){
        destLoadingEl.classList.add('hidden');
        destListEl.innerHTML = '<div class="p-4 text-sm text-red-500">Gagal memuat destinasi</div>';
    }
}

function showView(target){
    provinceView.classList.toggle('hidden', target !== 'province');
    cityView.classList.toggle('hidden', target !== 'city');
    destView.classList.toggle('hidden', target !== 'dest');
}

async function loadProvinces(){
    provinceLoadingEl.classList.remove('hidden');
    provinceEmptyEl.classList.add('hidden');
    try{
        const res = await axios.get('/api/provinces');
        const items = Array.isArray(res.data) ? res.data : (res.data.data || []);
        provinceLoadingEl.classList.add('hidden');
        if(!items.length){
            provinceEmptyEl.classList.remove('hidden');
            return;
        }
        provinceListEl.innerHTML = items.map(p => `
            <button class="app-list-item" data-id="${p.id}" data-name="${p.name}">
                <div class="app-thumb"></div>
                <div>
                    <div class="text-sm font-semibold text-slate-900">${p.name}</div>
                    <div class="text-xs text-slate-500 mt-1">Pilih provinsi</div>
                </div>
                <div class="app-arrow">›</div>
            </button>
        `).join('');
    }catch(err){
        provinceLoadingEl.classList.add('hidden');
        provinceListEl.innerHTML = '<div class="p-4 text-sm text-red-500">Gagal memuat provinsi</div>';
    }
}

async function loadCities(){
    if(!selectedProvince) return;
    cityTitleEl.textContent = selectedProvince.name || 'Kota';
    cityLoadingEl.classList.remove('hidden');
    cityEmptyEl.classList.add('hidden');
    try{
        const res = await axios.get(`/api/provinces/${selectedProvince.id}/cities`);
        const items = Array.isArray(res.data) ? res.data : (res.data.data || []);
        cityLoadingEl.classList.add('hidden');
        if(!items.length){
            cityEmptyEl.classList.remove('hidden');
            return;
        }
        cityListEl.innerHTML = items.map(c => `
            <button class="app-list-item" data-id="${c.id}" data-name="${c.name}">
                <div class="app-thumb"></div>
                <div>
                    <div class="text-sm font-semibold text-slate-900">${c.name}</div>
                    <div class="text-xs text-slate-500 mt-1">${selectedProvince.name || 'Provinsi'}</div>
                </div>
                <div class="app-arrow">›</div>
            </button>
        `).join('');
    }catch(err){
        cityLoadingEl.classList.add('hidden');
        cityListEl.innerHTML = '<div class="p-4 text-sm text-red-500">Gagal memuat kota</div>';
    }
}

provinceListEl.addEventListener('click', function(e){
    const btn = e.target.closest('button[data-id]');
    if(!btn) return;
    selectedProvince = { id: btn.dataset.id, name: btn.dataset.name };
    showView('city');
    loadCities();
});

cityListEl.addEventListener('click', function(e){
    const btn = e.target.closest('button[data-id]');
    if(!btn) return;
    selectedCity = { id: btn.dataset.id, name: btn.dataset.name };
    destTitleEl.textContent = selectedCity.name || 'Kota';
    showView('dest');
    loadDestinations();
});

backProvinceBtn.addEventListener('click', function(){
    selectedProvince = null;
    selectedCity = null;
    showView('province');
});

backCityBtn.addEventListener('click', function(){
    selectedCity = null;
    showView('city');
});

showView('province');
loadProvinces();

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
