<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Destination Catalogue</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
      :root { color-scheme: light; }
      body { font-family: 'Space Grotesk', system-ui, -apple-system, sans-serif; }
      .bg-grid {
        background-image:
          radial-gradient(circle at 1px 1px, rgba(255,255,255,0.08) 1px, transparent 0),
          linear-gradient(135deg, rgba(58,135,255,0.08), rgba(111,214,255,0.08));
        background-size: 32px 32px, 100% 100%;
      }
      .auth-shell { width: 100%; max-width: 360px; }
      .auth-card {
        background: #ffffff;
        border-radius: 22px;
        padding: 28px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid #eef2f0;
      }
      .auth-input {
        width: 100%;
        padding: 12px 14px;
        border-radius: 12px;
        background: #f6f6f6;
        border: 1px solid #e5e7eb;
        font-size: 14px;
        transition: border 0.2s ease, box-shadow 0.2s ease;
      }
      .auth-input:focus {
        outline: none;
        border-color: #1f5f1c;
        box-shadow: 0 0 0 3px rgba(31, 95, 28, 0.12);
      }
      .auth-btn {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        font-weight: 600;
        color: #ffffff;
        background: #0f4d1f;
        box-shadow: 0 10px 20px rgba(15, 77, 31, 0.22);
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
      }
      .auth-btn:hover { background: #0c4019; box-shadow: 0 12px 22px rgba(15, 77, 31, 0.28); }
      .auth-btn:active { transform: translateY(1px); }
      .auth-secondary {
        width: 100%;
        padding: 10px 16px;
        border-radius: 12px;
        font-weight: 600;
        color: #0f4d1f;
        background: #e9f2ea;
      }
      .auth-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #94a3b8;
        font-size: 12px;
      }
      .auth-divider::before,
      .auth-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e2e8f0;
      }
      .auth-icon-btn {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        background: #ffffff;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
        border: 1px solid #f1f5f9;
      }
      .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 999;
        padding: 16px;
      }
      .modal-backdrop.is-open { display: flex; }
      .modal-card {
        width: 100%;
        max-width: 320px;
        background: #ffffff;
        border-radius: 22px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
      }
      .modal-actions { display: flex; gap: 12px; margin-top: 18px; }
      .modal-btn {
        flex: 1;
        padding: 10px 12px;
        border-radius: 12px;
        font-weight: 600;
      }
      .modal-btn--ghost { background: #edf2ee; color: #0f4d1f; }
      .modal-btn--primary { background: #0f4d1f; color: #ffffff; }
      .app-page {
        background: #ffffff;
        color: #0f172a;
      }
      .app-shell {
        width: 100%;
        max-width: 430px;
        margin: 0 auto;
        padding: 16px 18px 28px;
      }
      .app-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
      }
      .app-logo {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        color: #1f5f1c;
      }
      .app-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #e9f2ea;
        display: grid;
        place-items: center;
        color: #1f5f1c;
        font-weight: 700;
        overflow: hidden;
      }
      .app-search {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f4f5f3;
        border-radius: 14px;
        padding: 10px 12px;
        border: 1px solid #eef1ef;
      }
      .app-search input {
        border: none;
        outline: none;
        background: transparent;
        width: 100%;
        font-size: 13px;
        color: #0f172a;
      }
      .app-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #eef1ef;
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.06);
      }
      .app-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 18px 0 10px;
      }
      .app-section h2 {
        font-size: 14px;
        font-weight: 700;
        color: #1f5f1c;
      }
      .app-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: #eef6ef;
        color: #1f5f1c;
        font-size: 11px;
        font-weight: 600;
      }
      .app-btn {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        font-weight: 600;
        background: #0f4d1f;
        color: #ffffff;
        text-align: center;
      }
      .app-btn-outline {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        font-weight: 600;
        background: #ffffff;
        color: #0f4d1f;
        border: 1px solid #d5e4d7;
        text-align: center;
      }
      .app-list {
        display: grid;
        gap: 12px;
      }
      .app-list-item {
        display: grid;
        grid-template-columns: 64px 1fr auto;
        gap: 12px;
        align-items: center;
        padding: 10px 12px;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #eef1ef;
      }
      .app-thumb {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        background: #e9f2ea;
        overflow: hidden;
      }
      .app-rating {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: #f59e0b;
        font-weight: 600;
      }
      .app-arrow {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #0f4d1f;
        color: #ffffff;
        display: grid;
        place-items: center;
        font-size: 14px;
      }
      .app-hero {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
      }
      .app-hero-card {
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        height: 120px;
      }
      .app-hero-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      .app-hero-card span {
        position: absolute;
        bottom: 10px;
        left: 10px;
        color: #ffffff;
        font-weight: 600;
        font-size: 12px;
        text-shadow: 0 8px 18px rgba(0,0,0,0.4);
      }
      .app-back {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        border: 1px solid #eef1ef;
        display: grid;
        place-items: center;
        color: #1f5f1c;
        background: #ffffff;
      }
      .app-menu {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.35);
        display: none;
        align-items: flex-end;
        justify-content: center;
        z-index: 998;
        padding: 16px;
      }
      .app-menu.is-open { display: flex; }
      .app-menu__sheet {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border-radius: 20px 20px 14px 14px;
        padding: 16px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.15);
      }
      .app-menu__grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        text-align: center;
      }
      .app-menu__item {
        display: grid;
        gap: 6px;
        justify-items: center;
        padding: 10px 6px;
        border-radius: 12px;
        color: #0f172a;
        font-weight: 600;
        font-size: 12px;
      }
      .app-menu__item:hover { background: #f5f7f5; }
      .app-menu__icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        background: #eef6ef;
        color: #1f5f1c;
        font-size: 18px;
      }
      .app-profile-menu__item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 10px;
        border-radius: 12px;
        color: #0f172a;
        font-weight: 600;
      }
      .app-profile-menu__item:hover { background: #f5f7f5; }
      @media (min-width: 900px) {
        .app-shell {
          max-width: 900px;
          padding: 24px 24px 40px;
        }
        .app-hero {
          grid-template-columns: repeat(4, minmax(0, 1fr));
        }
        .app-list {
          grid-template-columns: repeat(2, minmax(0, 1fr));
        }
      }
      .splash {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, #f7f7f2 0%, #ffffff 55%, #e7f2e8 100%);
        overflow: hidden;
        transition: opacity 0.6s ease, visibility 0.6s ease;
        opacity: 1;
        visibility: visible;
      }
      .splash--hidden,
      .splash--hide {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
      }
      .splash__logo {
        position: relative;
        z-index: 2;
        text-align: center;
        color: #0f172a;
        opacity: 0;
        transform: translateY(8px) scale(0.96);
        animation: splash-logo 1.2s ease forwards;
        animation-delay: 0.3s;
      }
      .splash__mark {
        width: 64px;
        height: 64px;
        margin: 0 auto 12px;
        border-radius: 22px;
        background: rgba(255,255,255,0.8);
        box-shadow: 0 10px 30px rgba(30, 64, 30, 0.12);
        display: grid;
        place-items: center;
      }
      .splash__name {
        font-size: 24px;
        font-weight: 700;
        letter-spacing: 0.02em;
      }
      .splash__hills {
        position: absolute;
        inset: 0;
        z-index: 1;
      }
      .splash__hill {
        position: absolute;
        left: -15%;
        right: -15%;
        height: 52%;
        border-radius: 50% 50% 0 0;
        transform: translateY(25%);
        animation: splash-rise 1.4s ease forwards;
      }
      .splash__hill--back {
        bottom: 26%;
        background: linear-gradient(180deg, #1f5f1c 0%, #174716 100%);
        animation-delay: 0.15s;
      }
      .splash__hill--mid {
        bottom: 10%;
        background: linear-gradient(180deg, #2f7a2a 0%, #255f21 100%);
        animation-delay: 0.25s;
      }
      .splash__hill--front {
        bottom: -6%;
        height: 58%;
        background: linear-gradient(180deg, #3a8a34 0%, #2e6f2b 100%);
        animation-delay: 0.35s;
      }
      .splash__fade {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 12%, rgba(255,255,255,0.9), rgba(255,255,255,0) 52%);
        z-index: 0;
      }
      @keyframes splash-rise {
        from { transform: translateY(28%); }
        to { transform: translateY(0); }
      }
      @keyframes splash-logo {
        to { opacity: 1; transform: translateY(0) scale(1); }
      }
      @media (prefers-reduced-motion: reduce) {
        .splash__hill,
        .splash__logo {
          animation: none;
          opacity: 1;
          transform: none;
        }
      }
    </style>
@php($authPage = $authPage ?? false)
@php($appPage = $appPage ?? false)
</head>
<body class="min-h-screen {{ $authPage ? 'bg-[#f5f6f1]' : ($appPage ? 'app-page' : 'bg-slate-950') }} text-slate-900">
  <div id="splash-screen" class="splash splash--hidden" aria-hidden="true">
    <div class="splash__fade"></div>
    <div class="splash__logo">
      <div class="splash__mark" aria-hidden="true">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1f5f1c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 22s7-6.5 7-12a7 7 0 0 0-14 0c0 5.5 7 12 7 12z"></path>
          <circle cx="12" cy="10" r="2.5"></circle>
        </svg>
      </div>
      <div class="splash__name">Destin.id</div>
    </div>
    <div class="splash__hills">
      <div class="splash__hill splash__hill--back"></div>
      <div class="splash__hill splash__hill--mid"></div>
      <div class="splash__hill splash__hill--front"></div>
    </div>
  </div>
  @if(!$authPage && !$appPage)
    <div class="fixed inset-0 bg-gradient-to-br from-blue-50/70 via-white to-cyan-50/60 pointer-events-none"></div>
    <div class="fixed inset-0 bg-grid pointer-events-none"></div>
  @endif

  <div class="{{ ($authPage || $appPage) ? '' : 'relative' }}">
    @if(!$authPage && !$appPage)
      <!-- Navbar -->
      <nav class="sticky top-0 z-40 backdrop-blur-xl bg-white/75 border-b border-white/70 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 lg:px-6 py-4 flex items-center justify-between">
          <a href="/dashboard" class="flex items-center gap-3">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-400 text-white shadow-lg shadow-blue-500/20">✦</span>
            <div>
              <p class="text-sm text-slate-500">Destination</p>
              <p class="text-lg font-semibold text-slate-900 tracking-tight">Catalogue</p>
            </div>
          </a>
          <div class="flex items-center gap-3 text-sm font-medium">
            <a href="/frontend/destinations" class="hidden sm:inline-flex px-3 py-2 rounded-full border border-slate-200 text-slate-700 hover:border-blue-400 hover:text-blue-600 transition">Explore</a>
            <a href="/dashboard" class="auth-only hidden px-3 py-2 rounded-full text-slate-700 hover:text-blue-600 transition">Dashboard</a>
            <div class="auth-only hidden relative" id="nav-user-menu">
              <div class="flex items-center gap-2">
                <div id="nav-username" class="hidden sm:block text-slate-700 font-semibold max-w-[160px] truncate">Akun</div>
                <button id="nav-avatar-btn" class="flex items-center gap-2 px-2 py-1 rounded-full border border-slate-200 bg-white hover:border-blue-400 hover:shadow transition">
                  <span id="nav-avatar" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-white font-semibold uppercase">👤</span>
                  <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
              </div>
              <div id="nav-dropdown" class="absolute right-0 mt-2 w-48 rounded-xl border border-slate-200 bg-white shadow-lg shadow-slate-200/60 py-2 hidden">
                <a href="/frontend/profile" class="block px-4 py-2 text-slate-700 hover:bg-slate-100">Profile</a>
                <button id="nav-logout" class="w-full text-left px-4 py-2 text-slate-700 hover:bg-slate-100">Logout</button>
              </div>
            </div>
            <a href="/login" class="guest-only px-3 py-2 rounded-full border border-slate-200 text-slate-700 hover:border-blue-400 hover:text-blue-600 transition">Login</a>
            <a href="/register" class="guest-only px-3 py-2 rounded-full bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-400/30 hover:shadow-blue-500/40 transition">Register</a>
          </div>
        </div>
      </nav>
    @endif

    <!-- Main Content -->
    @if($authPage || $appPage)
      <main class="min-h-screen {{ $appPage ? '' : 'px-4 py-10 flex items-center justify-center' }}">
        @yield('content')
      </main>
    @else
      <main class="max-w-6xl mx-auto px-4 lg:px-6 py-10">
        <div class="bg-white/80 backdrop-blur shadow-xl shadow-blue-500/5 border border-white/80 rounded-3xl p-8">
            @yield('content')
        </div>
      </main>
    @endif
  </div>

  <div id="logout-modal" class="modal-backdrop" aria-hidden="true">
    <div class="modal-card">
      <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-700">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 22s7-6.5 7-12a7 7 0 0 0-14 0c0 5.5 7 12 7 12z"></path>
          <circle cx="12" cy="10" r="2.5"></circle>
        </svg>
      </div>
      <h3 class="text-lg font-semibold text-slate-800">Logout Confirmation</h3>
      <p class="mt-1 text-sm text-slate-500">Are you sure you want to logout?</p>
      <div class="modal-actions">
        <button id="logout-cancel" class="modal-btn modal-btn--ghost">No</button>
        <button id="logout-confirm" class="modal-btn modal-btn--primary">Yes</button>
      </div>
    </div>
  </div>

  <div id="app-menu" class="app-menu" aria-hidden="true">
    <div class="app-menu__sheet">
      <div class="text-xs uppercase tracking-[0.16em] text-slate-400 mb-2">Quick Menu</div>
      <div class="app-menu__grid">
        <a href="/dashboard" class="app-menu__item">
          <span class="app-menu__icon"><i class="fa-solid fa-house"></i></span>
          Dashboard
        </a>
        <a href="/frontend/destinations" class="app-menu__item">
          <span class="app-menu__icon"><i class="fa-solid fa-map-location-dot"></i></span>
          Destinations
        </a>
        <a href="/reviews" class="app-menu__item">
          <span class="app-menu__icon"><i class="fa-solid fa-star"></i></span>
          Reviews
        </a>
        <a href="/frontend/saved" class="app-menu__item">
          <span class="app-menu__icon"><i class="fa-solid fa-heart"></i></span>
          Saved
        </a>
      </div>
    </div>
  </div>

  <div id="app-profile-menu" class="app-menu" aria-hidden="true">
    <div class="app-menu__sheet">
      <div class="text-xs uppercase tracking-[0.16em] text-slate-400 mb-2">Account</div>
      <a href="/frontend/profile" class="app-profile-menu__item">
        <span class="app-menu__icon"><i class="fa-solid fa-user"></i></span>
        Profile
      </a>
      <button id="app-profile-logout" class="app-profile-menu__item w-full text-left">
        <span class="app-menu__icon"><i class="fa-solid fa-right-from-bracket"></i></span>
        Logout
      </button>
    </div>
  </div>

  <script>
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    // track whether navbar profile data has been loaded for current session
    let navProfileLoaded = false;

    function getToken(){ return localStorage.getItem('token') || null; }
    function setToken(t){
        if(!t) return;
        localStorage.setItem('token', t);
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + t;
        navProfileLoaded = false; // ensure we reload profile info for current session
        hideDropdown();
        updateNavUI();
        loadNavProfile();
    }
    function clearToken(){
        localStorage.removeItem('token');
        delete axios.defaults.headers.common['Authorization'];
        updateNavUI();
    }

    (function(){
        const t = getToken();
        if(t){ axios.defaults.headers.common['Authorization'] = 'Bearer ' + t; }
        updateNavUI();
    })();

    function updateNavUI(){
        const token = getToken();
        document.querySelectorAll('.auth-only').forEach(el => el.classList.toggle('hidden', !token));
        document.querySelectorAll('.guest-only').forEach(el => el.classList.toggle('hidden', !!token));
        if(token){
            loadNavProfile();
        } else {
            resetNavProfile();
            hideDropdown();
        }
    }

    async function loadNavProfile(){
        if(navProfileLoaded) return;
        try{
            const res = await axios.get('/api/profile');
            const u = res.data;
            const name = u.name || 'Akun';
            const navName = document.getElementById('nav-username');
            if(navName) navName.textContent = name;
            const navAvatar = document.getElementById('nav-avatar');
            if(navAvatar){
                const photoUrl = u.photo_url ? (u.photo_url.startsWith('http') ? u.photo_url : '/storage/' + u.photo_url.replace(/^\/+/, '')) : null;
                if(photoUrl){
                    navAvatar.innerHTML = `<img src="${photoUrl}" alt="Profile photo" class="h-full w-full object-cover rounded-full">`;
                }else{
                    navAvatar.textContent = (name.trim()[0] || '👤').toUpperCase();
                    navAvatar.innerHTML = navAvatar.textContent;
                }
            }
            navProfileLoaded = true;
        }catch(err){
            if(err.response && err.response.status === 401){
                clearToken();
                window.location.href = '/login';
            }
        }
    }

    function resetNavProfile(){
        navProfileLoaded = false;
        const navName = document.getElementById('nav-username');
        if(navName) navName.textContent = 'Akun';
        const navAvatar = document.getElementById('nav-avatar');
        if(navAvatar){
            navAvatar.innerHTML = '👤';
        }
    }

    function hideDropdown(){
        const dd = document.getElementById('nav-dropdown');
        if(dd) dd.classList.add('hidden');
    }

    function showLogoutModal(){
        const modal = document.getElementById('logout-modal');
        if(!modal) return;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
    }

    function hideLogoutModal(){
        const modal = document.getElementById('logout-modal');
        if(!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    }

    function showAppMenu(){
        const menu = document.getElementById('app-menu');
        if(!menu) return;
        menu.classList.add('is-open');
        menu.setAttribute('aria-hidden', 'false');
    }

    function hideAppMenu(){
        const menu = document.getElementById('app-menu');
        if(!menu) return;
        menu.classList.remove('is-open');
        menu.setAttribute('aria-hidden', 'true');
    }

    function showProfileMenu(){
        const menu = document.getElementById('app-profile-menu');
        if(!menu) return;
        menu.classList.add('is-open');
        menu.setAttribute('aria-hidden', 'false');
    }

    function hideProfileMenu(){
        const menu = document.getElementById('app-profile-menu');
        if(!menu) return;
        menu.classList.remove('is-open');
        menu.setAttribute('aria-hidden', 'true');
    }

    async function confirmLogout(){
        try{ await axios.post('/api/logout'); }catch(e){ /* ignore */ }
        clearToken();
        window.location.href = '/login';
    }

    document.addEventListener('click', function(e){
        if(e.target && e.target.id === 'app-menu'){
            hideAppMenu();
            return;
        }
        if(e.target && e.target.id === 'app-profile-menu'){
            hideProfileMenu();
            return;
        }
        if(e.target.closest && e.target.closest('[data-app-menu-toggle]')){
            e.preventDefault();
            showAppMenu();
            return;
        }
        if(e.target.closest && e.target.closest('[data-app-avatar]')){
            e.preventDefault();
            showProfileMenu();
            return;
        }
        if(e.target && e.target.id === 'app-profile-logout'){
            e.preventDefault();
            hideProfileMenu();
            showLogoutModal();
            return;
        }
        if(e.target && e.target.id === 'nav-logout'){
            e.preventDefault();
            hideDropdown();
            showLogoutModal();
            return;
        }
        if(e.target && e.target.id === 'logout-modal'){
            hideLogoutModal();
            return;
        }
        if(e.target && e.target.id === 'logout-cancel'){
            e.preventDefault();
            hideLogoutModal();
            return;
        }
        if(e.target && e.target.id === 'logout-confirm'){
            e.preventDefault();
            confirmLogout();
            return;
        }
        const menu = document.getElementById('nav-user-menu');
        const dropdown = document.getElementById('nav-dropdown');
        const avatarBtn = document.getElementById('nav-avatar-btn');
        if(avatarBtn && avatarBtn.contains(e.target)){
            e.preventDefault();
            dropdown?.classList.toggle('hidden');
            return;
        }
        if(dropdown && !dropdown.contains(e.target) && !avatarBtn?.contains(e.target)){
            dropdown.classList.add('hidden');
        }
    });

    window.showLogoutModal = showLogoutModal;
    window.showAppMenu = showAppMenu;
    window.showProfileMenu = showProfileMenu;
  </script>
  <script>
    (function(){
        const splash = document.getElementById('splash-screen');
        if(!splash) return;
        const key = 'splash_seen';
        const seen = localStorage.getItem(key) === '1';
        if(seen){
            return;
        }
        splash.classList.remove('splash--hidden');
        splash.setAttribute('aria-hidden', 'false');
        const hideAfterMs = 2400;
        setTimeout(function(){
            splash.classList.add('splash--hide');
            splash.setAttribute('aria-hidden', 'true');
            localStorage.setItem(key, '1');
        }, hideAfterMs);
    })();
  </script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    if (window.AOS) {
        AOS.init({ duration: 700, once: true, offset: 40 });
    }
  </script>

  @stack('scripts')
</body>
</html>
