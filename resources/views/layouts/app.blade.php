<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Destination Catalogue</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-900">
  <div class="fixed inset-0 bg-gradient-to-br from-blue-50/70 via-white to-cyan-50/60 pointer-events-none"></div>
  <div class="fixed inset-0 bg-grid pointer-events-none"></div>

  <div class="relative">
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

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 lg:px-6 py-10">
      <div class="bg-white/80 backdrop-blur shadow-xl shadow-blue-500/5 border border-white/80 rounded-3xl p-8">
          @yield('content')
      </div>
    </main>
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

    document.addEventListener('click', function(e){
        if(e.target && e.target.id === 'nav-logout'){
            e.preventDefault();
            (async ()=>{
                try{ await axios.post('/api/logout'); }catch(e){ /* ignore */ }
                clearToken();
                window.location.href = '/login';
            })();
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
  </script>

  @stack('scripts')
</body>
</html>
