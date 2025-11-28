@extends('layouts.app')

@section('content')
<div id="dest-detail" class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Main content -->
    <div class="md:col-span-2">
        <h1 id="dest-name" class="text-3xl font-bold text-gray-800 mb-2">Memuat...</h1>
        <div id="dest-meta" class="text-gray-500 text-sm mb-4"></div>
        <p id="dest-desc" class="text-gray-700 mb-4"></p>

        <div id="dest-photos" class="grid grid-cols-2 gap-4 mb-6"></div>

        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="font-bold text-lg mb-2">Fasilitas</h3>
            <p id="dest-facilities" class="text-gray-700"></p>
        </div>

        <!-- Review Section -->
        <h3 class="text-xl font-bold mb-4">Add Review</h3>
        <form id="review-form" class="bg-gray-50 p-4 rounded-lg mb-6 space-y-4">
            <input type="hidden" id="destination_id" value="{{ $id }}">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                <select id="rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                    <option value="4">⭐⭐⭐⭐ Good</option>
                    <option value="3">⭐⭐⭐ Average</option>
                    <option value="2">⭐⭐ Poor</option>
                    <option value="1">⭐ Terrible</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Review</label>
                <textarea id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">Kirim Review</button>
        </form>
        <div id="review-msg" class="p-3 rounded-lg hidden"></div>
    </div>

    <!-- Sidebar -->
    <div class="md:col-span-1">
        <button id="save-btn" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition mb-4">❤️ Simpan</button>
        
        <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="font-bold mb-2">Budget</h3>
            <p id="dest-budget" class="text-blue-600 font-semibold"></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const destId = '{{ $id }}';
async function loadDest(){
    try{
        const res = await axios.get(`/api/destinations/${destId}`);
        const d = res.data;
        document.getElementById('dest-name').innerText = d.name;
        document.getElementById('dest-meta').innerText = `${d.category?.name || ''} • ${d.city?.name || ''}`;
        document.getElementById('dest-desc').innerText = d.description;
        document.getElementById('dest-facilities').innerText = d.facilities || '-';
        document.getElementById('dest-budget').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(d.budget_min)} - Rp ${new Intl.NumberFormat('id-ID').format(d.budget_max)}`;
        document.getElementById('dest-photos').innerHTML = (d.photos||[]).map(p=>`<img src="${p.photo_url}" alt="" class="w-full h-40 object-cover rounded-lg">`).join('');
    }catch(err){
        document.getElementById('dest-name').innerText = 'Gagal memuat destinasi';
    }
}
loadDest();

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
