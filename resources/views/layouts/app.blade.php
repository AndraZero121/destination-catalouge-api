<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Destination Catalogue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50">

<!-- Navbar -->
<nav class="bg-white shadow-sm sticky top-0 z-40">
  <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
    <a href="/" class="text-xl font-bold text-blue-600">🏖️ Destination Catalogue</a>
    <div class="flex gap-4">
      <!-- guest-only links -->
      <a href="/login" class="guest-only text-gray-700 hover:text-blue-600 transition">Login</a>
      <a href="/register" class="guest-only text-gray-700 hover:text-blue-600 transition">Register</a>

      <!-- auth-only links: keep only Dashboard, Profile (frontend path), Logout -->
      <a href="/dashboard" class="auth-only text-gray-700 hover:text-blue-600 transition hidden">Dashboard</a>
      <a href="/frontend/profile" class="auth-only text-gray-700 hover:text-blue-600 transition hidden">Profile</a>
      <a href="#" id="nav-logout" class="auth-only text-gray-700 hover:text-red-600 transition hidden">Logout</a>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-8">
        @yield('content')
    </div>
</div>

<script>
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

  function getToken(){ return localStorage.getItem('token') || null; }
  function setToken(t){
      if(!t) return;
      localStorage.setItem('token', t);
      axios.defaults.headers.common['Authorization'] = 'Bearer ' + t;
      updateNavUI();
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
  });
</script>

@stack('scripts')
</body>
</html>
