@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Destinations</h1>
    <div>
        <!-- tombol tambah hanya muncul bila user login -->
        <button id="open-add" class="hidden px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">+ Tambah Destinasi</button>
    </div>
</div>

<div id="dest-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- menampilkan loading / kosong / daftar -->
    <div id="dest-empty" class="col-span-full text-center text-gray-500 hidden">
        <img src="https://cdn.jsdelivr.net/gh/heroicons/heroicons@1.0.6/optimized/illustration/devices.svg" alt="empty" class="mx-auto mb-4 w-48 opacity-70">
        <h3 class="text-lg font-semibold mb-2">Belum ada destinasi</h3>
        <p class="text-sm text-gray-500 mb-4">Belum ada data destinasi. Jika Anda ingin, tambahkan destinasi baru.</p>
        <div>
            <a href="/dashboard" class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded-lg mr-2">Kembali ke Dashboard</a>
            <button id="open-add-empty" class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 hidden">+ Tambah Destinasi</button>
        </div>
    </div>

    <div id="dest-loading" class="col-span-full text-center text-gray-500">
        Memuat destinasi...
    </div>
</div>

<!-- Modal: Add Destination (sama seperti sebelumnya, hidden by default) -->
<div id="modal-add" class="fixed inset-0 bg-black bg-opacity-50 flex items-start justify-center py-12 hidden">
  <div class="bg-white rounded-lg w-full max-w-2xl p-6 shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">Tambah Destinasi</h2>
      <button id="close-add" class="text-gray-500 hover:text-gray-800">✕</button>
    </div>

    <div id="add-msg" class="mb-3 hidden p-3 rounded"></div>

    <form id="add-form" class="space-y-3" enctype="multipart/form-data">
      <div>
        <label class="block text-sm font-medium text-gray-700">Nama</label>
        <input name="name" required class="w-full px-3 py-2 border rounded" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded"></textarea>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium text-gray-700">Budget Min</label>
          <input name="budget_min" type="number" class="w-full px-3 py-2 border rounded" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Budget Max</label>
          <input name="budget_max" type="number" class="w-full px-3 py-2 border rounded" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Fasilitas (koma pisah)</label>
        <input name="facilities" class="w-full px-3 py-2 border rounded" />
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium text-gray-700">Latitude</label>
          <input name="latitude" class="w-full px-3 py-2 border rounded" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Longitude</label>
          <input name="longitude" class="w-full px-3 py-2 border rounded" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Photos (multiple)</label>
        <input name="photos[]" type="file" multiple accept="image/*" class="w-full" />
      </div>

      <div class="flex justify-end gap-2 mt-4">
        <button type="button" id="cancel-add" class="px-4 py-2 border rounded">Batal</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
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
        const html = items.map(d=>`
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="${d.photos && d.photos[0] ? d.photos[0].photo_url : 'https://via.placeholder.com/600x350'}" alt="${d.name}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <a href="/frontend/destinations/${d.id}" class="font-bold text-lg text-gray-800 hover:text-blue-600">${d.name}</a>
                    <p class="text-sm text-gray-500 mt-1">${d.category?.name || ''} • ${d.city?.name || ''}</p>
                    <p class="text-gray-600 text-sm mt-2">${(d.description||'').slice(0,140)}${(d.description||'').length>140 ? '...' : ''}</p>
                    <p class="text-blue-600 font-semibold mt-3">Rp ${new Intl.NumberFormat('id-ID').format(d.budget_min||0)} - Rp ${new Intl.NumberFormat('id-ID').format(d.budget_max||0)}</p>
                </div>
            </div>`).join('');
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
