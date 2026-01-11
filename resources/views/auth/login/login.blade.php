@php($authPage = true)
@extends('layouts.app')

@section('content')
<div class="auth-shell" data-aos="fade-up">
    <div class="mb-6 flex items-center gap-3 animate__animated animate__fadeInDown">
        <a href="/" class="h-10 w-10 rounded-full border border-slate-200 bg-white flex items-center justify-center text-slate-600 shadow-sm">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6"></path>
            </svg>
        </a>
    </div>

    <h1 class="text-2xl font-semibold text-[#1f5f1c]">Welcome back to Destin.id!</h1>
    <p class="text-sm text-slate-500 mt-2">Create your Account</p>

    <div class="auth-card mt-6" data-aos="fade-up">
        <form id="login-form" class="space-y-4">
            <input id="email" type="text" required placeholder="Phone/email" class="auth-input" />
            <input id="password" type="password" required placeholder="Password" class="auth-input" />

            <div id="otp-section" class="hidden space-y-4">
                <input id="otp" type="text" inputmode="numeric" placeholder="OTP Code" class="auth-input" />
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500">
                <span></span>
                <a href="/forgot-password" class="text-[#1f5f1c] font-semibold">Forgot Password ?</a>
            </div>

            <button id="login-submit" type="submit" class="auth-btn">Sign In</button>
        </form>

        <div class="auth-divider my-6">Or sign up with</div>
        <div class="flex items-center justify-center gap-3">
            <a class="auth-icon-btn" href="/auth/google" aria-label="Google">
                <svg width="18" height="18" viewBox="0 0 24 24">
                    <path fill="#EA4335" d="M12 10.2v3.9h5.4c-.2 1.5-1.7 4.4-5.4 4.4A6.3 6.3 0 1 1 12 5.7c1.8 0 3 .8 3.7 1.5l2.5-2.4C16.5 3.2 14.5 2.2 12 2.2A9.8 9.8 0 1 0 12 21.8c5.6 0 9.3-4 9.3-9.6 0-.6-.1-1.1-.2-1.6H12z"/>
                </svg>
            </a>
            <a class="auth-icon-btn" href="/auth/facebook" aria-label="Facebook">
                <svg width="18" height="18" viewBox="0 0 24 24">
                    <path fill="#1877F2" d="M22 12.1A10 10 0 1 0 10.5 22v-7H8v-3h2.5V9.4c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.3.2 2.3.2v2.6h-1.3c-1.3 0-1.7.8-1.7 1.6V12H18l-.4 3h-2.6v7A10 10 0 0 0 22 12.1z"/>
                </svg>
            </a>
        </div>
    </div>

    <p class="text-center text-sm text-slate-500 mt-6">Don't have an account? <a href="/register" class="text-[#1f5f1c] font-semibold">Register</a></p>

    <div id="login-msg" class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg hidden"></div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('login-form').addEventListener('submit', async function(e){
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const otpField = document.getElementById('otp');
    const otpSection = document.getElementById('otp-section');
    const msg = document.getElementById('login-msg');
    const submitBtn = document.getElementById('login-submit');
    try {
        msg.classList.add('hidden');
        if(otpSection.classList.contains('hidden')){
            const res = await axios.post('/api/login', { login: email, password: password });
            if(res.data && res.data.require_otp){
                otpSection.classList.remove('hidden');
                submitBtn.textContent = 'Verify OTP';
                return;
            }
            setToken(res.data.access_token);
            window.location.href = '/dashboard';
            return;
        }
        const res = await axios.post('/api/login/verify-otp', { login: email, otp: otpField.value });
        setToken(res.data.access_token);
        window.location.href = '/dashboard';
    } catch (err) {
        msg.innerText = err.response?.data?.message || 'Login gagal';
        msg.classList.remove('hidden');
    }
});
</script>
@endpush
