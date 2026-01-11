@php($authPage = true)
@extends('layouts.app')

@section('content')
<div class="auth-shell text-center" data-aos="fade-up">
    <h1 class="text-2xl font-semibold text-[#1f5f1c] mb-2">Logout</h1>
    <p class="text-sm text-slate-500">Konfirmasi logout akan muncul.</p>
</div>
@endsection

@push('scripts')
<script>
(async function(){
    if(window.showLogoutModal){
        window.showLogoutModal();
    }
})();
</script>
@endpush
