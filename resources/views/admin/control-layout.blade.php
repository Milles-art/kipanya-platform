<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>{{ $title ?? 'Kipanya Control Center' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen">
<div class="control-shell" data-control-shell>
    <aside class="control-sidebar">
        <div class="control-sidebar-inner">
            <a href="{{ route('admin.dashboard') }}" class="control-brand">
                <span class="control-brand-mark"><x-icon name="tv" size="19"/></span>
                <span class="control-brand-copy"><strong>KIPANYA</strong><small>Control Center</small></span>
            </a>
            <button type="button" class="control-collapse-button" data-control-collapse aria-label="Collapse sidebar" aria-expanded="true"><x-icon name="chevron-left" size="16"/></button>

            <nav class="control-nav" aria-label="Control Center navigation">
                <a href="{{ route('admin.dashboard') }}" class="control-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Control Center"><x-icon name="home" size="17"/><span>Control Center</span></a>
                <div class="control-nav-label">Platform</div>
                <a href="{{ route('admin.users') }}" class="control-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" title="Users &amp; Roles"><x-icon name="users" size="17"/><span>Users &amp; Roles</span></a>
                <button type="button" class="control-nav-link control-nav-disabled" disabled><x-icon name="settings" size="17"/><span>Settings</span><small>Later</small></button>
                <button type="button" class="control-nav-link control-nav-disabled" disabled><x-icon name="card" size="17"/><span>Billing &amp; Plans</span><small>Later</small></button>
                <a href="{{ route('admin.activity') }}" class="control-nav-link {{ request()->routeIs('admin.activity') ? 'active' : '' }}" title="Activity Logs"><x-icon name="activity" size="17"/><span>Activity Logs</span></a>
                <button type="button" class="control-nav-link control-nav-disabled" disabled><x-icon name="bell" size="17"/><span>Notifications</span><small>Later</small></button>
                <div class="control-nav-label control-nav-support">Support</div>
                <button type="button" class="control-nav-link control-nav-disabled" disabled><x-icon name="help" size="17"/><span>Help Center</span><small>Later</small></button>
                <button type="button" class="control-nav-link control-nav-disabled" disabled><x-icon name="chat" size="17"/><span>Contact Support</span><small>Later</small></button>
            </nav>

            <div class="control-sidebar-quick">
                <div class="control-quick-title">Quick actions</div>
                <a href="{{ route('home') }}" class="control-quick-link"><x-icon name="external" size="15"/><span>View Public Site</span><x-icon name="external" size="12"/></a>
                <button type="button" class="control-quick-link" data-theme-toggle><x-icon name="moon" size="15"/><span data-theme-label>Dark mode</span></button>
                <form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit" class="control-quick-link"><x-icon name="logout" size="15"/><span>Sign out</span></button></form>
            </div>
        </div>
    </aside>

    <main class="control-main">
        <header class="control-topbar">
            <div class="control-topbar-left">
                <button data-menu-toggle="#control-mobile" class="k-btn k-btn-light control-mobile-menu" type="button" aria-label="Open navigation"><x-icon name="menu" size="18"/></button>
                <x-app-switcher />
            </div>
            <div class="control-topbar-actions">
                <label class="control-global-search"><x-icon name="search" size="17"/><input type="search" placeholder="Search anything..." aria-label="Search anything"><kbd>⌘ K</kbd></label>
                <button class="control-icon-button control-icon-button-disabled" type="button" aria-label="Notifications" title="Notifications coming soon" disabled><x-icon name="bell" size="19"/></button>
                <div class="control-profile-wrap"><button class="control-profile" type="button" aria-label="Administrator profile" aria-expanded="false" data-profile-toggle><span class="control-avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'SA', 0, 1)) }}</span><span class="hidden md:block"><strong>{{ auth()->user()?->name ?? 'Super Admin' }}</strong><small>Administrator</small></span><x-icon name="chevron-down" size="14"/></button><div class="control-profile-menu" data-profile-menu hidden><a href="{{ route('admin.profile') }}"><x-icon name="users" size="15"/>My profile</a><a href="{{ route('admin.activity') }}"><x-icon name="activity" size="15"/>Activity logs</a><form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit"><x-icon name="logout" size="15"/>Sign out</button></form></div></div>
            </div>
        </header>

        <div id="control-mobile" class="control-mobile-nav hidden">
            <a href="{{ route('admin.dashboard') }}" class="control-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><x-icon name="home" size="17"/><span>Control Center</span></a>
            <a href="{{ route('admin.users') }}" class="control-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"><x-icon name="users" size="17"/><span>Users &amp; Roles</span></a>
            <button type="button" class="control-nav-link control-nav-disabled" disabled><x-icon name="settings" size="17"/><span>Settings</span><small>Later</small></button>
            <a href="{{ route('admin.activity') }}" class="control-nav-link {{ request()->routeIs('admin.activity') ? 'active' : '' }}"><x-icon name="activity" size="17"/><span>Activity Logs</span></a>
            <button type="button" class="control-nav-link control-nav-disabled" disabled><x-icon name="bell" size="17"/><span>Notifications</span><small>Later</small></button>
            <button type="button" class="control-nav-link control-nav-disabled" disabled><x-icon name="help" size="17"/><span>Help Center</span><small>Later</small></button>
            <a href="{{ route('home') }}" class="control-nav-link"><x-icon name="external" size="17"/><span>View Public Site</span></a>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit" class="control-nav-link w-full text-left"><x-icon name="logout" size="17"/><span>Sign out</span></button></form>
        </div>

        <div class="control-content k-shell">
            @yield('content')
        </div>
    </main>
</div>
<script>
(() => {
 const shell=document.querySelector('[data-control-shell]'); const btn=document.querySelector('[data-control-collapse]');
 const saved=localStorage.getItem('kipanya.control.sidebar');
 const setCollapsed=(v)=>{shell?.classList.toggle('control-sidebar-collapsed',v);btn?.setAttribute('aria-expanded',String(!v));btn?.setAttribute('aria-label',v?'Expand sidebar':'Collapse sidebar');localStorage.setItem('kipanya.control.sidebar',v?'collapsed':'expanded');};
 if(saved==='collapsed') setCollapsed(true); btn?.addEventListener('click',()=>setCollapsed(!shell.classList.contains('control-sidebar-collapsed')));
 const profileBtn=document.querySelector('[data-profile-toggle]'), profile=document.querySelector('[data-profile-menu]');
 profileBtn?.addEventListener('click',e=>{e.stopPropagation();const open=!profile.hidden;profile.hidden=open;profileBtn.setAttribute('aria-expanded',String(!open));});
 document.addEventListener('click',()=>{if(profile&&!profile.hidden){profile.hidden=true;profileBtn?.setAttribute('aria-expanded','false');}});
 document.addEventListener('keydown',e=>{if(e.key==='Escape'){if(profile){profile.hidden=true;profileBtn?.setAttribute('aria-expanded','false');}}});
})();
</script>
</body>
</html>
