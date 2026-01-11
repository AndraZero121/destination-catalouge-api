@php($appPage = true)
@extends('layouts.app')

@section('content')
<div class="app-shell" id="dest-detail" data-aos="fade-up">
    <div class="relative overflow-hidden rounded-3xl shadow-lg">
        <div id="dest-cover" class="h-72 bg-slate-200"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/25 via-black/0 to-black/30"></div>
        <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
            <a href="/frontend/destinations" class="h-10 w-10 rounded-full bg-white/80 text-slate-700 flex items-center justify-center">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="text-white text-sm font-semibold">Detail</div>
            <div class="flex items-center gap-2">
                <button id="share-btn" class="h-10 w-10 rounded-full bg-white/80 text-slate-700 flex items-center justify-center" type="button">
                    <i class="fa-solid fa-share-nodes"></i>
                </button>
                <button id="save-btn" class="h-10 w-10 rounded-full bg-white/80 text-slate-700 flex items-center justify-center" type="button">
                    <i class="fa-solid fa-bookmark"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="-mt-6 relative z-10 bg-white rounded-[28px] shadow-xl p-5 space-y-4">
        <div>
            <h1 id="dest-name" class="text-xl font-semibold text-[#1f5f1c]">Memuat...</h1>
            <div id="dest-meta" class="text-xs text-slate-500 mt-1">-</div>
            <div class="flex items-center gap-2 mt-2 text-xs text-slate-500">
                <span id="header-rating" class="text-amber-500 font-semibold">★ 0</span>
                <span id="dest-price">Rp - /orang</span>
            </div>
        </div>

        <div>
            <div class="text-xs font-semibold text-[#1f5f1c] mb-2">Gallery</div>
            <div id="dest-photos" class="grid grid-cols-4 gap-2"></div>
        </div>

        <div>
            <div class="text-xs font-semibold text-[#1f5f1c] mb-2">About</div>
            <p id="dest-desc" class="text-xs text-slate-600 leading-relaxed"></p>
        </div>

        <div>
            <div class="text-xs font-semibold text-[#1f5f1c] mb-2">Facility</div>
            <div id="dest-facilities" class="grid grid-cols-5 gap-3 text-center text-[10px] text-slate-600"></div>
        </div>

        <div>
            <div class="text-xs font-semibold text-[#1f5f1c] mb-2">Location</div>
            <div id="dest-location" class="text-xs text-slate-600"></div>
        </div>
    </div>

    <div class="mt-5 rounded-3xl overflow-hidden bg-[#0f4d1f] text-white">
        <div class="p-4 flex items-center justify-between">
            <span class="h-9 w-9 rounded-full bg-white/15 flex items-center justify-center">
                <i class="fa-solid fa-arrow-left"></i>
            </span>
            <div class="font-semibold">Reviews</div>
            <span class="h-9 w-9 rounded-full bg-white/15 flex items-center justify-center">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </span>
        </div>
    </div>

    <div class="-mt-4 app-card p-4 space-y-4">
        <div class="app-card p-4">
            <div class="flex items-center gap-4">
                <div>
                    <div id="avg-rating" class="text-3xl font-semibold text-amber-500">-</div>
                    <div class="text-amber-500 text-sm" id="avg-stars">★★★★★</div>
                    <div id="review-count" class="text-[10px] text-slate-500 mt-1">0 ratings</div>
                </div>
                <div class="flex-1 space-y-2 text-[10px] text-slate-500">
                    <div class="flex items-center gap-2"><span>5</span><div class="h-1 flex-1 bg-slate-200 rounded"><div id="bar-5" class="h-1 bg-amber-400 rounded w-0"></div></div></div>
                    <div class="flex items-center gap-2"><span>4</span><div class="h-1 flex-1 bg-slate-200 rounded"><div id="bar-4" class="h-1 bg-amber-400 rounded w-0"></div></div></div>
                    <div class="flex items-center gap-2"><span>3</span><div class="h-1 flex-1 bg-slate-200 rounded"><div id="bar-3" class="h-1 bg-amber-400 rounded w-0"></div></div></div>
                    <div class="flex items-center gap-2"><span>2</span><div class="h-1 flex-1 bg-slate-200 rounded"><div id="bar-2" class="h-1 bg-amber-400 rounded w-0"></div></div></div>
                    <div class="flex items-center gap-2"><span>1</span><div class="h-1 flex-1 bg-slate-200 rounded"><div id="bar-1" class="h-1 bg-amber-400 rounded w-0"></div></div></div>
                </div>
            </div>
        </div>

        <div id="reviews-empty" class="text-sm text-slate-500 hidden">Belum ada review. Jadilah yang pertama!</div>
        <div id="reviews-list" class="space-y-4"></div>
    </div>

    <button id="open-review" class="fixed bottom-6 right-6 h-12 w-12 rounded-full bg-[#0f4d1f] text-white shadow-lg flex items-center justify-center">
        <i class="fa-solid fa-plus"></i>
    </button>
</div>

<div id="review-modal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-card w-full max-w-sm">
        <h3 class="text-lg font-semibold text-[#1f5f1c] mb-2">User Review</h3>
        <div class="text-left text-xs text-slate-500 mb-2">Ratings</div>
        <div id="rating-stars" class="flex items-center gap-1 text-xl mb-3">
            <button type="button" data-star="1"><i class="fa-solid fa-star"></i></button>
            <button type="button" data-star="2"><i class="fa-solid fa-star"></i></button>
            <button type="button" data-star="3"><i class="fa-solid fa-star"></i></button>
            <button type="button" data-star="4"><i class="fa-solid fa-star"></i></button>
            <button type="button" data-star="5"><i class="fa-solid fa-star"></i></button>
        </div>
        <div class="text-left text-xs text-slate-500 mb-2">Description</div>
        <textarea id="description" rows="4" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm" placeholder="Tulis review kamu..."></textarea>
        <div class="mt-4 flex justify-end gap-2">
            <button id="close-review" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600">Close</button>
            <button id="submit-review" class="px-4 py-2 rounded-xl bg-[#0f4d1f] text-white">Submit</button>
        </div>
        <div id="review-msg" class="mt-3 text-sm hidden"></div>
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
const avgStarsEl = document.getElementById('avg-stars');
const ratingBars = {
    1: document.getElementById('bar-1'),
    2: document.getElementById('bar-2'),
    3: document.getElementById('bar-3'),
    4: document.getElementById('bar-4'),
    5: document.getElementById('bar-5'),
};
let selectedRating = 5;

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
        document.getElementById('dest-desc').innerText = d.description || '-';
        document.getElementById('dest-location').innerText = `${d.city?.name || ''}${d.province?.name ? ', ' + d.province.name : ''}`;
        document.getElementById('dest-price').innerText = budgetMax ? `Rp ${fmt.format(budgetMax)} /orang` : 'Rp - /orang';
        const cover = photos[0]?.photo_url || 'https://via.placeholder.com/600x400?text=Destination';
        document.getElementById('dest-cover').innerHTML = `<img src="${cover}" alt="${d.name}" class="h-full w-full object-cover">`;
        document.getElementById('dest-photos').innerHTML = photos.length
            ? photos.slice(0, 4).map(p=>`<img src="${p.photo_url}" alt="" class="w-full h-16 object-cover rounded-xl">`).join('')
            : '<div class="col-span-full text-center text-slate-500 text-xs py-4">Belum ada foto</div>';
        renderFacilities(d.facilities);
        renderReviews(d.reviews || [], d.average_rating);
        const headerBadge = document.getElementById('header-rating');
        headerBadge.textContent = d.average_rating ? `★ ${d.average_rating}` : '★ -';
    }catch(err){
        document.getElementById('dest-name').innerText = 'Gagal memuat destinasi';
    }
}
loadDest();

function renderFacilities(raw) {
    const container = document.getElementById('dest-facilities');
    if (!container) return;
    const items = (raw || '')
        .split(',')
        .map(item => item.trim())
        .filter(Boolean)
        .slice(0, 5);
    const icons = ['fa-square-parking', 'fa-toilet', 'fa-mosque', 'fa-utensils', 'fa-camera'];
    const labels = ['Parkir', 'Toilet', 'Mushola', 'Restoran', 'Foto'];
    const list = items.length ? items : labels;
    container.innerHTML = list.map((label, idx) => `
        <div class="flex flex-col items-center gap-1">
            <div class="h-9 w-9 rounded-full bg-slate-100 text-[#1f5f1c] flex items-center justify-center">
                <i class="fa-solid ${icons[idx] || 'fa-circle-info'}"></i>
            </div>
            <span>${label}</span>
        </div>
    `).join('');
}

function renderReviews(reviews, avg){
    const list = Array.isArray(reviews) ? reviews : [];
    const total = list.length;
    reviewCountEl.textContent = total ? `${total} ratings` : '0 ratings';
    avgRatingEl.textContent = avg ? `${avg}` : '0.0';
    avgStarsEl.textContent = avg ? '★'.repeat(Math.round(avg)).padEnd(5, '☆') : '☆☆☆☆☆';

    const counts = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };
    list.forEach(r => { counts[r.rating] = (counts[r.rating] || 0) + 1; });
    Object.keys(counts).forEach(key => {
        const pct = total ? Math.round((counts[key] / total) * 100) : 0;
        ratingBars[key].style.width = `${pct}%`;
    });

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
        const stars = '★'.repeat(rating || 0);
        const photoUrl = r.user?.photo_url
            ? (r.user.photo_url.startsWith('http') ? r.user.photo_url : '/storage/' + r.user.photo_url.replace(/^\/+/, ''))
            : null;
        const avatar = photoUrl
            ? `<img src="${photoUrl}" alt="${name}" class="h-10 w-10 rounded-full object-cover">`
            : `<div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-[#1f5f1c] font-semibold">${(name[0]||'T').toUpperCase()}</div>`;
        return `<div class="app-card p-4 space-y-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    ${avatar}
                    <div>
                        <p class="text-sm font-semibold text-slate-900">${name}</p>
                        <p class="text-[10px] text-slate-400">2 weeks ago</p>
                    </div>
                </div>
                <p class="text-xs text-amber-600 font-semibold">${stars}</p>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">${desc}</p>
        </div>`;
    }).join('');
}

function openReviewModal(){
    const modal = document.getElementById('review-modal');
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    selectedRating = 5;
    updateStars();
}

function closeReviewModal(){
    const modal = document.getElementById('review-modal');
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
}

function updateStars(){
    document.querySelectorAll('#rating-stars button').forEach(btn => {
        const val = parseInt(btn.dataset.star);
        btn.classList.toggle('text-amber-400', val <= selectedRating);
        btn.classList.toggle('text-slate-300', val > selectedRating);
    });
}

document.getElementById('open-review').addEventListener('click', openReviewModal);
document.getElementById('close-review').addEventListener('click', closeReviewModal);
document.getElementById('review-modal').addEventListener('click', function(e){
    if (e.target && e.target.id === 'review-modal') closeReviewModal();
});

document.querySelectorAll('#rating-stars button').forEach(btn => {
    btn.addEventListener('click', function(){
        selectedRating = parseInt(this.dataset.star);
        updateStars();
    });
});
updateStars();

document.getElementById('submit-review').addEventListener('click', async function(){
    try{
        const payload = {
            destination_id: parseInt(destId),
            rating: selectedRating,
            description: document.getElementById('description').value
        };
        await axios.post('/api/review', payload);
        const msg = document.getElementById('review-msg');
        msg.classList.remove('text-red-600');
        msg.innerText = '✓ Review terkirim';
        msg.classList.remove('hidden');
        msg.classList.add('text-green-600');
        setTimeout(() => { msg.classList.add('hidden'); closeReviewModal(); }, 1200);
        document.getElementById('description').value = '';
        loadDest();
    }catch(err){
        const msg = document.getElementById('review-msg');
        msg.classList.remove('text-green-600');
        msg.innerText = err.response?.data?.message || 'Gagal mengirim review';
        msg.classList.remove('hidden');
        msg.classList.add('text-red-600');
    }
});

document.getElementById('save-btn').addEventListener('click', async function(){
    try{
        await axios.post('/api/saved', { destination_id: parseInt(destId) });
        this.innerHTML = '<i class="fa-solid fa-bookmark"></i>';
        this.classList.add('bg-[#0f4d1f]', 'text-white');
        this.disabled = true;
    }catch(err){
        alert(err.response?.data?.message || 'Gagal menyimpan (login diperlukan)');
    }
});

document.getElementById('share-btn').addEventListener('click', async function(){
    const title = document.getElementById('dest-name').innerText || 'Destination';
    const url = window.location.href;
    if (navigator.share) {
        try {
            await navigator.share({ title, url });
            return;
        } catch (e) {
            /* ignore */
        }
    }
    try {
        await navigator.clipboard.writeText(url);
        alert('Link disalin.');
    } catch (e) {
        alert(url);
    }
});
</script>
@endpush
