@php($authPage = true)
@extends('layouts.app')

@section('content')
<div class="auth-shell text-center" data-aos="fade-up">
    <div class="auth-card animate__animated animate__fadeInUp">
        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-700">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6L9 17l-5-5"></path>
            </svg>
        </div>
        <h1 class="text-xl font-semibold text-[#1f5f1c]">Success!</h1>
        <p class="mt-2 text-sm text-slate-500">Logging you in...</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const token = @json($token);
    if(token){
        localStorage.setItem('token', token);
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    }
    window.location.href = '/dashboard';
})();
</script>
@endpush
