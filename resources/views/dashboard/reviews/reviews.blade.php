@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-6">My Reviews</h1>

<div id="reviews-list" class="space-y-4">
    <div class="text-center text-gray-500">Memuat...</div>
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
            <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-gray-800">${r.destination?.name || '—'}</h3>
                        <p class="text-sm text-yellow-500 mt-1">${'⭐'.repeat(r.rating)} ${r.rating}/5</p>
                        <p class="text-gray-600 mt-3">${r.description}</p>
                    </div>
                    <button onclick="deleteReview(${r.id})" class="ml-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">Hapus</button>
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
