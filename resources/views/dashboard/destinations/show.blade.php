@extends('layouts.app')

@section('content')
<div class="space-y-8" id="dest-detail">
    <header class="space-y-3">
        <p class="text-sm uppercase text-slate-500 tracking-[0.12em]">Destination</p>
        <div class="flex flex-wrap items-center gap-3">
            <h1 id="dest-name" class="text-3xl md:text-4xl font-semibold text-slate-900 tracking-tight">Memuat...</h1>
            <span id="header-rating" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 text-sm font-semibold border border-amber-100">⭐ 0</span>
        </div>
        <div id="dest-meta" class="text-slate-500 text-sm">-</div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main content -->
        <div class="md:col-span-2 space-y-6">
            <div class="rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-sm">
                <div id="dest-photos" class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-3 bg-slate-900/5 p-2 md:p-3 rounded-b-2xl md:rounded-none md:rounded-b-2xl"></div>
                <div class="p-6 space-y-4">
                    <p id="dest-desc" class="text-slate-700 leading-relaxed"></p>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <h3 class="font-semibold text-slate-900 mb-2">Fasilitas</h3>
                        <p id="dest-facilities" class="text-slate-700 text-sm leading-relaxed"></p>
                    </div>
                </div>
            </div>

            <!-- Review Section -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs uppercase text-slate-500 tracking-[0.16em]">Review</p>
                        <h3 class="text-xl font-semibold text-slate-900">Bagikan pengalamanmu</h3>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500">Rata-rata</p>
                        <p id="avg-rating" class="text-lg font-semibold text-amber-600">-</p>
                    </div>
                </div>
                <form id="review-form" class="space-y-4">
                    <input type="hidden" id="destination_id" value="{{ $id }}">
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Rating</label>
                        <select id="rating" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                            <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                            <option value="4">⭐⭐⭐⭐ Good</option>
                            <option value="3">⭐⭐⭐ Average</option>
                            <option value="2">⭐⭐ Poor</option>
                            <option value="1">⭐ Terrible</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Review</label>
                        <textarea id="description" rows="4" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500" placeholder="Bagikan highlight perjalananmu di sini"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 rounded-xl transition">Kirim Review</button>
                </form>
                <div id="review-msg" class="p-3 rounded-lg hidden"></div>

                <div class="pt-4 border-t border-slate-100 space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-900">Ulasan traveler</p>
                        <span id="review-count" class="text-xs text-slate-500"></span>
                    </div>
                    <div id="reviews-empty" class="text-sm text-slate-500 hidden">Belum ada review. Jadilah yang pertama!</div>
                    <div id="reviews-list" class="space-y-3"></div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="md:col-span-1 space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                <button id="save-btn" class="w-full bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold py-3 rounded-xl transition">❤️ Simpan destinasi</button>
            </div>
            
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5 space-y-3">
                <h3 class="font-semibold text-slate-900">Budget</h3>
                <p id="dest-budget" class="text-blue-700 font-semibold"></p>
                <p class="text-xs text-slate-500 leading-relaxed">Gunakan rentang ini sebagai panduan awal untuk merencanakan itinerary.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const destId = '{{ $id }}';
const reviewListEl = document.getElementById('reviews-list');
const reviewEmptyEl = document.getElementById('reviews-empty');
const reviewCountEl = document.getElementById('review-count');
const avgRatingEl = document.getElementById('avg-rating');

async function loadDest(){
    try{
        const res = await axios.get(`/api/destinations/${destId}`);
        const d = res.data;
        const photos = d.photos || [];
        const fmt = new Intl.NumberFormat('id-ID');
        const budgetMin = d.budget_min ?? 0;
        const budgetMax = d.budget_max ?? 0;
        document.getElementById('dest-name').innerText = d.name;
        document.getElementById('dest-meta').innerText = `${d.category?.name || ''} • ${d.city?.name || ''}`;
        document.getElementById('dest-desc').innerText = d.description;
        document.getElementById('dest-facilities').innerText = d.facilities || '-';
        document.getElementById('dest-budget').innerText = `Rp ${fmt.format(budgetMin)} - Rp ${fmt.format(budgetMax)}`;
        document.getElementById('dest-photos').innerHTML = photos.length
            ? photos.map(p=>`<img src="${p.photo_url}" alt="" class="w-full h-52 object-cover rounded-xl shadow-sm">`).join('')
            : '<div class="col-span-full text-center text-slate-500 py-10 bg-white rounded-xl border border-dashed border-slate-200">Belum ada foto</div>';
        renderReviews(d.reviews || [], d.average_rating);
        const headerBadge = document.getElementById('header-rating');
        headerBadge.textContent = d.average_rating ? `⭐ ${d.average_rating}` : '⭐ -';
    }catch(err){
        document.getElementById('dest-name').innerText = 'Gagal memuat destinasi';
    }
}
loadDest();

function renderReviews(reviews, avg){
    const list = Array.isArray(reviews) ? reviews : [];
    reviewCountEl.textContent = list.length ? `${list.length} review` : '';
    avgRatingEl.textContent = avg ? `${avg}/5` : '-';

    if(!list.length){
        reviewEmptyEl.classList.remove('hidden');
        reviewListEl.innerHTML = '';
        return;
    }
    reviewEmptyEl.classList.add('hidden');
    reviewListEl.innerHTML = list.map(r => {
        const name = r.user?.name || 'Traveler';
        const rating = r.rating || 0;
        const desc = r.description || '';
        const stars = '⭐'.repeat(rating || 0) || '–';
        return `<div class="rounded-xl border border-slate-100 bg-slate-50/60 p-4 flex gap-4">
            <div class="h-10 w-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-semibold">${(name[0]||'T').toUpperCase()}</div>
            <div class="flex-1 space-y-1">
                <div class="flex items-center justify-between">
                    <p class="font-semibold text-slate-900">${name}</p>
                    <p class="text-sm text-amber-600 font-semibold">${stars} <span class="text-slate-500 font-normal">${rating}/5</span></p>
                </div>
                <p class="text-sm text-slate-700 leading-relaxed">${desc}</p>
            </div>
        </div>`;
    }).join('');
}

document.getElementById('review-form').addEventListener('submit', async function(e){
    e.preventDefault();
    try{
        const payload = {
            destination_id: parseInt(destId),
            rating: parseInt(document.getElementById('rating').value),
            description: document.getElementById('description').value
        };
        await axios.post('/api/review', payload);
        const msg = document.getElementById('review-msg');
        msg.innerText = '✓ Review terkirim';
        msg.classList.remove('hidden');
        msg.classList.add('bg-green-50', 'text-green-700');
        document.getElementById('description').value = '';
        setTimeout(() => msg.classList.add('hidden'), 3000);
        loadDest();
    }catch(err){
        const msg = document.getElementById('review-msg');
        msg.innerText = err.response?.data?.message || 'Gagal mengirim review';
        msg.classList.remove('hidden');
        msg.classList.add('bg-red-50', 'text-red-700');
    }
});

document.getElementById('save-btn').addEventListener('click', async function(){
    try{
        await axios.post('/api/saved', { destination_id: parseInt(destId) });
        this.innerText = '✓ Tersimpan';
        this.disabled = true;
    }catch(err){
        alert(err.response?.data?.message || 'Gagal menyimpan (login diperlukan)');
    }
});
</script>
@endpush
