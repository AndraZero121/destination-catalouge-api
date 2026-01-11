@php($appPage = true)
@extends('layouts.app')

@section('content')
<div class="app-shell" data-aos="fade-up">
    <div class="rounded-3xl overflow-hidden bg-[#0f4d1f] text-white">
        <div class="p-4 flex items-center justify-between">
            <a href="/dashboard" class="h-9 w-9 rounded-full bg-white/15 flex items-center justify-center">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="font-semibold">Reviews</div>
            <a href="/frontend/destinations" class="h-9 w-9 rounded-full bg-white/15 flex items-center justify-center">
                <i class="fa-solid fa-compass"></i>
            </a>
        </div>
    </div>

    <div class="-mt-4 app-card p-4 space-y-4">
        <div class="app-card p-4">
            <div class="flex items-center gap-4">
                <div>
                    <div id="avg-rating" class="text-3xl font-semibold text-amber-500">-</div>
                    <div class="text-amber-500 text-sm" id="avg-stars">★★★★★</div>
                    <div id="review-count" class="text-[10px] text-slate-500 mt-1">0 reviews</div>
                </div>
                <div class="flex-1 text-[10px] text-slate-500">
                    Kelola review yang sudah kamu tulis dan pantau performanya di sini.
                </div>
            </div>
        </div>

        <div id="reviews-list" class="space-y-4" data-aos="fade-up">
            <div class="text-center text-slate-500 text-sm">Memuat...</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function loadMyReviews(){
    try{
        const res = await axios.get('/api/reviews/my');
        const items = res.data.data || [];
        const avgEl = document.getElementById('avg-rating');
        const starsEl = document.getElementById('avg-stars');
        const countEl = document.getElementById('review-count');
        if (items.length) {
            const sum = items.reduce((acc, r) => acc + (r.rating || 0), 0);
            const avg = (sum / items.length).toFixed(1);
            avgEl.textContent = avg;
            starsEl.textContent = '★'.repeat(Math.round(avg)).padEnd(5, '☆');
            countEl.textContent = `${items.length} reviews`;
        } else {
            avgEl.textContent = '0.0';
            starsEl.textContent = '☆☆☆☆☆';
            countEl.textContent = '0 reviews';
        }
        if(items.length===0){ 
            document.getElementById('reviews-list').innerHTML = '<div class="text-center text-gray-500">Belum ada review</div>'; 
            return; 
        }
        document.getElementById('reviews-list').innerHTML = items.map(r=>`
            <div class="app-card p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="space-y-1">
                        <div class="text-sm font-semibold text-slate-900">${r.destination?.name || '—'}</div>
                        <div class="app-rating">${'★'.repeat(r.rating)} <span class="text-slate-500 font-normal">${r.rating}/5</span></div>
                        <div class="text-xs text-slate-600">${r.description || ''}</div>
                    </div>
                    <button onclick="deleteReview(${r.id})" class="app-pill text-rose-600">Hapus</button>
                </div>
            </div>`).join('');
    }catch(err){
        document.getElementById('reviews-list').innerHTML = '<div class="text-center text-red-500">Gagal memuat review</div>';
    }
}

async function deleteReview(id){
    if(!confirm('Hapus review ini?')) return;
    try{
        await axios.delete('/api/reviews/'+id);
        loadMyReviews();
    }catch(err){
        alert('Gagal hapus');
    }
}

loadMyReviews();
</script>
@endpush
