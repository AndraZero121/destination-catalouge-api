@extends('layouts.app')

@section('content')
<div class="text-center py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Logout</h1>
    <p id="logout-msg" class="text-gray-600 text-lg">Memproses logout...</p>
    <div class="mt-6 flex justify-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(async function(){
    try{
        await axios.post('/api/logout');
    }catch(e){ /* ignore */ }
    finally {
        clearToken();
        document.getElementById('logout-msg').innerText = 'Anda telah logout. Kembali ke halaman login...';
        setTimeout(()=>window.location.href='/login', 1200);
    }
})();
</script>
@endpush
