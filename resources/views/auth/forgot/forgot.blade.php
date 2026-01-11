@php($authPage = true)
@extends('layouts.app')

@section('content')
<div class="auth-shell" data-aos="fade-up">
    <div class="mb-6 flex items-center gap-3 animate__animated animate__fadeInDown">
        <a href="/login" class="h-10 w-10 rounded-full border border-slate-200 bg-white flex items-center justify-center text-slate-600 shadow-sm">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6"></path>
            </svg>
        </a>
    </div>

    <h1 class="text-2xl font-semibold text-[#1f5f1c]">Forgot Password</h1>
    <p class="text-sm text-slate-500 mt-2">Please enter the email address you'd like password reset information sent to.</p>

    <div class="auth-card mt-6" data-aos="fade-up">
        <form id="forgot-form" class="space-y-4">
            <input id="forgot-email" type="email" required placeholder="Email Address" class="auth-input" />
            <button type="submit" class="auth-btn">Reset Password</button>
        </form>

        <form id="reset-form" class="space-y-4 hidden mt-6">
            <input id="reset-otp" type="text" inputmode="numeric" required placeholder="OTP Code" class="auth-input" />
            <input id="reset-password" type="password" required placeholder="New Password" class="auth-input" />
            <input id="reset-password-confirmation" type="password" required placeholder="Confirm Password" class="auth-input" />
            <button type="submit" class="auth-btn">Continue</button>
        </form>
    </div>

    <div id="forgot-msg" class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg hidden"></div>
    <div id="forgot-success" class="mt-4 p-3 bg-green-50 text-green-700 rounded-lg hidden"></div>
</div>
@endsection

@push('scripts')
<script>
const forgotForm = document.getElementById('forgot-form');
const resetForm = document.getElementById('reset-form');
const msgBox = document.getElementById('forgot-msg');
const successBox = document.getElementById('forgot-success');

forgotForm.addEventListener('submit', async function(e){
    e.preventDefault();
    msgBox.classList.add('hidden');
    successBox.classList.add('hidden');
    const email = document.getElementById('forgot-email').value;
    try{
        await axios.post('/api/password/forgot', { email });
        successBox.innerText = 'OTP telah dikirim. Silakan cek email dan lanjutkan reset password.';
        successBox.classList.remove('hidden');
        resetForm.classList.remove('hidden');
    }catch(err){
        msgBox.innerText = err.response?.data?.message || 'Permintaan reset password gagal';
        msgBox.classList.remove('hidden');
    }
});

resetForm.addEventListener('submit', async function(e){
    e.preventDefault();
    msgBox.classList.add('hidden');
    successBox.classList.add('hidden');
    const email = document.getElementById('forgot-email').value;
    const otp = document.getElementById('reset-otp').value;
    const password = document.getElementById('reset-password').value;
    const password_confirmation = document.getElementById('reset-password-confirmation').value;
    try{
        await axios.post('/api/password/reset', { email, otp, password, password_confirmation });
        successBox.innerText = 'Password berhasil diperbarui. Silakan login kembali.';
        successBox.classList.remove('hidden');
    }catch(err){
        msgBox.innerText = err.response?.data?.message || 'Reset password gagal';
        msgBox.classList.remove('hidden');
    }
});
</script>
@endpush
