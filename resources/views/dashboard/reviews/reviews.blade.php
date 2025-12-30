@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-start justify-between flex-wrap gap-3">
        <div>
            <p class="text-sm uppercase text-slate-500 tracking-[0.12em]">Kontribusi</p>
            <h1 class="text-3xl font-semibold text-slate-900 tracking-tight">My Reviews</h1>
            <p class="text-slate-600 mt-2">Kelola ulasan yang sudah kamu tulis dan bagikan pengalaman terbaikmu.</p>
        </div>
        <a href="/frontend/destinations" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-semibold hover:border-blue-400 hover:text-blue-600 transition">Cari destinasi lain</a>
    </div>

    <div id="reviews-list" class="space-y-4">
        <div class="text-center text-slate-500">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 text-slate-700">
                <span class="h-2 w-2 rounded-full bg-blue-500 animate-ping"></span>
                Memuat...
            </div>
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
        if(items.length===0){ 
            document.getElementById('reviews-list').innerHTML = '<div class="text-center text-gray-500">Belum ada review</div>'; 
            return; 
        }
        document.getElementById('reviews-list').innerHTML = items.map(r=>`
            <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-6 shadow-sm hover:-translate-y-1 hover:shadow-xl transition transform">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1 space-y-2">
                        <h3 class="font-semibold text-lg text-slate-900 tracking-tight">${r.destination?.name || '—'}</h3>
                        <p class="text-sm text-amber-600 font-semibold">${'⭐'.repeat(r.rating)} <span class="text-slate-500 font-normal">${r.rating}/5</span></p>
                        <p class="text-slate-700 leading-relaxed text-sm">${r.description}</p>
                    </div>
                    <button onclick="deleteReview(${r.id})" class="ml-4 px-4 py-2 rounded-xl border border-slate-200 text-slate-700 text-sm font-semibold hover:border-rose-400 hover:text-rose-600 transition">Hapus</button>
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
