@php($appPage = true)
@extends('layouts.app')

@section('content')
<div class="app-shell">
    <div class="app-topbar animate__animated animate__fadeInDown">
        <div class="app-logo">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1f5f1c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s7-6.5 7-12a7 7 0 0 0-14 0c0 5.5 7 12 7 12z"></path>
                <circle cx="12" cy="10" r="2.5"></circle>
            </svg>
            Destin.id
        </div>
        <button id="app-avatar-btn" class="app-avatar" data-app-avatar aria-label="Profile menu">D</button>
    </div>

    <div class="app-search" data-aos="fade-up">
        <button id="app-menu-toggle" class="app-back" data-app-menu-toggle type="button" aria-label="Menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 6h16"></path>
                <path d="M4 12h16"></path>
                <path d="M4 18h16"></path>
            </svg>
        </button>
        <input id="home-search" type="text" placeholder="Search destination" />
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1f5f1c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M21 21l-4.3-4.3"></path>
        </svg>
    </div>

    <div class="app-section" data-aos="fade-up">
        <h2>Top Picks</h2>
        <span class="app-pill">Destinasi</span>
    </div>
    <div id="home-hero" class="app-hero" data-aos="zoom-in"></div>

    <div class="app-section" data-aos="fade-up">
        <h2>List Destination</h2>
        <a href="/frontend/destinations" class="text-xs font-semibold text-[#1f5f1c]">See all</a>
    </div>
    <div id="home-list" class="app-list" data-aos="fade-up"></div>
    <div id="home-empty" class="text-center text-slate-500 text-sm hidden">Belum ada destinasi.</div>
</div>
@endsection

@push('scripts')
<script>
const heroEl = document.getElementById('home-hero');
const listEl = document.getElementById('home-list');
const emptyEl = document.getElementById('home-empty');
const searchInput = document.getElementById('home-search');
const avatarBtn = document.getElementById('app-avatar-btn');
let listItems = [];
let allItems = [];
const fallbackHero = [
    { id: 1, name: 'Pantai Klayar', photos: [{ photo_url: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80' }] },
    { id: 2, name: 'Gunung Bromo', photos: [{ photo_url: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80' }] },
    { id: 3, name: 'Goa Pindul', photos: [{ photo_url: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=800&q=80' }] },
    { id: 4, name: 'Taman Safari', photos: [{ photo_url: 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=800&q=80' }] }
];
const fallbackList = [
    { id: 1, name: 'Wisata alam Goa Pindul', city: { name: 'Yogyakarta' }, reviews: [{ rating: 5 }, { rating: 4 }], photos: [{ photo_url: 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=600&q=80' }] },
    { id: 2, name: 'Wisata alam Curug Sewu', city: { name: 'Kendal' }, reviews: [{ rating: 4 }, { rating: 4 }], photos: [{ photo_url: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=80' }] },
    { id: 3, name: 'Wisata alam Gunung Bromo', city: { name: 'Probolinggo' }, reviews: [{ rating: 5 }], photos: [{ photo_url: 'https://images.unsplash.com/photo-1482192505345-5655af888cc4?auto=format&fit=crop&w=600&q=80' }] },
    { id: 4, name: 'Wisata alam Taman Safari', city: { name: 'Bogor' }, reviews: [{ rating: 4 }, { rating: 5 }, { rating: 4 }], photos: [{ photo_url: 'https://images.unsplash.com/photo-1474511320723-9a56873867b5?auto=format&fit=crop&w=600&q=80' }] }
];

function ensureAuthHeader() {
    const t = localStorage.getItem('token');
    if (t) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + t;
        return true;
    }
    delete axios.defaults.headers.common['Authorization'];
    return false;
}

function renderAvatar(user){
    const avatar = avatarBtn;
    if(!avatar) return;
    if(user && user.photo_url){
        const photoUrl = user.photo_url.startsWith('http') ? user.photo_url : '/storage/' + user.photo_url.replace(/^\/+/, '');
        avatar.innerHTML = `<img src="${photoUrl}" alt="Profile" class="h-full w-full object-cover">`;
        return;
    }
    const name = user?.name || 'Destin';
    avatar.textContent = (name.trim()[0] || 'D').toUpperCase();
}

async function loadProfile(){
    if(!ensureAuthHeader()){
        renderAvatar(null);
        return;
    }
    try{
        const res = await axios.get('/api/profile');
        renderAvatar(res.data);
    }catch(err){
        renderAvatar(null);
    }
}

function renderHero(items){
    if(!items.length){
        heroEl.innerHTML = '<div class="text-sm text-slate-500">Belum ada data slider.</div>';
        return;
    }
    heroEl.innerHTML = items.map(item => {
        const cover = item.photos?.[0]?.photo_url || 'https://via.placeholder.com/400x300?text=Destination';
        return `
            <a href="/frontend/destinations/${item.id}" class="app-hero-card">
                <img src="${cover}" alt="${item.name || ''}">
                <span>${item.name || ''}</span>
            </a>
        `;
    }).join('');
}

function renderList(items){
    listItems = items;
    if(!items.length){
        listEl.innerHTML = '';
        emptyEl.classList.remove('hidden');
        return;
    }
    emptyEl.classList.add('hidden');
    listEl.innerHTML = items.map(item => {
        const cover = item.photos?.[0]?.photo_url || 'https://via.placeholder.com/300x300?text=Destination';
        const reviews = Array.isArray(item.reviews) ? item.reviews : [];
        const avg = reviews.length ? (reviews.reduce((a,b)=>a+(b.rating||0),0)/reviews.length).toFixed(1) : null;
        return `
            <a href="/frontend/destinations/${item.id}" class="app-list-item">
                <div class="app-thumb">
                    <img src="${cover}" alt="${item.name || ''}" class="h-full w-full object-cover">
                </div>
                <div>
                    <div class="text-sm font-semibold text-slate-900">${item.name || ''}</div>
                    <div class="text-xs text-slate-500 mt-1">${item.city?.name || 'Lokasi'} </div>
                    <div class="app-rating mt-1">${avg ? `${avg} ★` : 'No rating'}</div>
                </div>
                <div class="app-arrow">›</div>
            </a>
        `;
    }).join('');
}

async function loadHero(){
    try{
        const res = await axios.get('/api/destinations/slider');
        const items = Array.isArray(res.data) ? res.data : [];
        renderHero(items.length ? items : fallbackHero);
    }catch(err){
        try{
            const res = await axios.get('/api/destinations');
            const items = Array.isArray(res.data) ? res.data : (res.data.data || []);
            renderHero(items.length ? items.slice(0, 4) : fallbackHero);
        }catch(e){
            renderHero(fallbackHero);
        }
    }
}

async function loadList(){
    try{
        const res = await axios.get('/api/destinations');
        const items = Array.isArray(res.data) ? res.data : (res.data.data || []);
        if (!items.length) {
            allItems = fallbackList;
        } else {
            allItems = items.slice(0, 8);
        }
        renderList(allItems);
    }catch(err){
        allItems = fallbackList;
        renderList(allItems);
    }
}

searchInput.addEventListener('input', function(){
    const q = this.value.toLowerCase();
    const filtered = allItems.filter(item => (item.name || '').toLowerCase().includes(q));
    renderList(filtered);
});

loadProfile();
loadHero();
loadList();
</script>
@endpush
