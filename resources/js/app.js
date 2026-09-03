import './bootstrap';

const root = document.documentElement;
const storedTheme = localStorage.getItem('kipanya-theme');
if (storedTheme) root.dataset.theme = storedTheme;

const updateThemeButtons = () => {
    const dark = root.dataset.theme === 'dark';
    document.querySelectorAll('[data-theme-icon]').forEach((el) => {
        el.innerHTML = dark ? el.dataset.themeIconDark : el.dataset.themeIconLight;
    });
    document.querySelectorAll('[data-theme-label]').forEach((el) => {
        el.textContent = dark ? 'Light mode' : 'Dark mode';
    });
};

document.addEventListener('click', (event) => {
    const themeButton = event.target.closest('[data-theme-toggle]');
    if (themeButton) {
        root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('kipanya-theme', root.dataset.theme);
        updateThemeButtons();
        return;
    }
    const menuButton = event.target.closest('[data-menu-toggle]');
    if (menuButton) {
        const menu = document.querySelector(menuButton.dataset.menuToggle);
        if (menu) {
            const isOpen = !menu.classList.toggle('hidden');
            menuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        }
    }
});

updateThemeButtons();


document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        document.querySelectorAll('.studio-mobile-nav:not(.hidden)').forEach((menu) => menu.classList.add('hidden'));
        document.querySelectorAll('[data-menu-toggle]').forEach((button) => button.setAttribute('aria-expanded','false'));
    }
});

document.addEventListener('click', (event) => {
    const link = event.target.closest('#studio-mobile a');
    if (link) {
        const menu = document.querySelector('#studio-mobile');
        menu?.classList.add('hidden');
        document.querySelector('[data-menu-toggle="#studio-mobile"]')?.setAttribute('aria-expanded','false');
    }
});


// Unified application switcher
document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-app-switcher-toggle]');
    const switcher = event.target.closest('[data-app-switcher]');
    if (toggle && switcher) {
        const menu = switcher.querySelector('.app-switcher-menu');
        const open = toggle.getAttribute('aria-expanded') === 'true';
        document.querySelectorAll('[data-app-switcher]').forEach((item) => {
            item.querySelector('[data-app-switcher-toggle]')?.setAttribute('aria-expanded', 'false');
            const other = item.querySelector('.app-switcher-menu');
            if (other) other.hidden = true;
        });
        toggle.setAttribute('aria-expanded', open ? 'false' : 'true');
        menu.hidden = open;
        return;
    }
    document.querySelectorAll('[data-app-switcher]').forEach((item) => {
        if (!item.contains(event.target)) {
            item.querySelector('[data-app-switcher-toggle]')?.setAttribute('aria-expanded', 'false');
            const menu = item.querySelector('.app-switcher-menu');
            if (menu) menu.hidden = true;
        }
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        document.querySelectorAll('[data-app-switcher]').forEach((item) => {
            item.querySelector('[data-app-switcher-toggle]')?.setAttribute('aria-expanded', 'false');
            const menu = item.querySelector('.app-switcher-menu');
            if (menu) menu.hidden = true;
        });
    }
});

// Kipanya ecosystem home — Blade equivalent of the Lovable interactive hero.
(() => {
    const home = document.querySelector('[data-platform-home]');
    if (!home) return;

    let apps;
    try { apps = JSON.parse(home.dataset.apps); } catch { return; }
    const ids = Object.keys(apps);
    let index = 0;
    let progress = 0;
    let interacted = false;
    let resumeTimer = null;
    let liveIndex = 0;
    const slideDuration = 6000;
    const resumeDelay = 15000;
    let last = performance.now();
    let liveTimer = null;

    const bg = home.querySelector('[data-platform-bg]');
    const copy = home.querySelector('[data-platform-copy]');
    const icon = home.querySelector('[data-platform-icon]');
    const name = home.querySelector('[data-platform-name]');
    const title = home.querySelector('[data-platform-title]');
    const description = home.querySelector('[data-platform-description]');
    const live = home.querySelector('[data-platform-live]');
    const cta = home.querySelector('[data-platform-cta]');
    const cross = home.querySelector('[data-platform-cross]');
    const intro = home.querySelector('[data-platform-intro]');
    const segments = [...home.querySelectorAll('.platform-segment')];
    const navs = [...home.querySelectorAll('.platform-app-nav')];
    const docks = [...home.querySelectorAll('.platform-dock-item')];

    const iconPaths = {
        cartoons: '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 4v16M16 4v16M3 9h5M16 9h5M3 15h5M16 15h5"/>',
        wear: '<path d="M8 5.5 5 4 2.5 8l3.5 2v10h12V10l3.5-2L19 4l-3 1.5a5 5 0 0 1-8 0Z"/><path d="M8 5.5c.5 2 1.8 3 4 3s3.5-1 4-3"/>',
        books: '<path d="M5 4.5A2.5 2.5 0 0 1 7.5 2H19v18H7.5A2.5 2.5 0 0 0 5 22V4.5Z"/><path d="M5 18.5A2.5 2.5 0 0 1 7.5 16H19"/>',
        motors: '<path d="m5 17 1.5-6h11L19 17"/><path d="M7 11 8.5 7h7L17 11"/><path d="M4 17h16v3H4z"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/>',
        tv: '<rect x="3" y="5" width="18" height="13" rx="2"/><path d="m9 22 3-4 3 4M8 2l4 3 4-3"/>'
    };

    const renderLive = () => {
        const item = apps[ids[index]];
        const entry = item.live[liveIndex % item.live.length];
        live.innerHTML = `${entry.badge ? `<span class="platform-live-badge" style="background:${item.accent}">${entry.badge}</span>` : ''}<span class="platform-live-label">${entry.label}</span><span class="platform-live-text">${entry.text}</span>`;
    };

    const select = (id, manual = true) => {
        const nextIndex = ids.indexOf(id);
        if (nextIndex < 0) return;
        index = nextIndex;
        progress = 0;
        liveIndex = 0;
        const item = apps[id];
        home.style.setProperty('--app-accent', item.accent);
        bg.classList.add('is-changing');
        copy.classList.add('is-switching');
        window.setTimeout(() => {
            bg.style.backgroundImage = `url("${item.image}")`;
            icon.innerHTML = `<div class="app-icon-wrapper" style="--app-accent:${item.accent};--app-icon-size:40px"><svg width="50%" height="50%" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:${item.accent}" aria-hidden="true">${iconPaths[id]}</svg></div>`;
            icon.style.color = item.accent;
            icon.style.setProperty('--app-accent', item.accent);
            icon.setAttribute('data-icon-name', iconPaths[id]);
            name.textContent = item.name;
            title.textContent = item.tagline;
            description.textContent = item.description;
            cta.style.setProperty('--app-accent', item.accent);
            cta.style.background = item.accent;
            cta.textContent = `Enter ${item.short}`;
            const arrow = document.createElement('span'); arrow.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h13"/><path d="m13 6 6 6-6 6"/></svg>'; arrow.setAttribute('aria-hidden','true'); cta.appendChild(arrow);
            if (item.url) {
                cta.href = item.url;
                cta.removeAttribute('data-coming-soon');
            } else {
                cta.removeAttribute('href');
                cta.setAttribute('data-coming-soon','true');
            }
            if (item.cross) {
                cross.hidden = false;
                cross.innerHTML = `${item.cross.text} <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h13"/><path d="m13 6 6 6-6 6"/></svg>`;
                cross.dataset.target = item.cross.target || '';
                if (item.cross.url) {
                    cross.dataset.href = item.cross.url;
                } else {
                    delete cross.dataset.href;
                }
            } else {
                cross.hidden = true;
                delete cross.dataset.href;
            }
            renderLive();
            segments.forEach((segment, i) => { segment.classList.toggle('is-active', i === index); segment.classList.toggle('is-past', i < index); segment.style.setProperty('--progress', i === index ? '0%' : i < index ? '100%' : '0%'); });
            [...navs, ...docks].forEach(el => el.classList.toggle('is-active', el.dataset.platformSelect === id));
            copy.classList.remove('is-switching');
            bg.classList.remove('is-changing');
        }, 180);
        if (manual) {
            interacted = true;
            clearTimeout(resumeTimer);
            resumeTimer = window.setTimeout(() => { interacted = false; progress = 0; last = performance.now(); }, resumeDelay);
        }
    };

    const next = (direction = 1) => select(ids[(index + direction + ids.length) % ids.length]);

    home.addEventListener('click', (event) => {
        const selectEl = event.target.closest('[data-platform-select]');
        if (selectEl) { select(selectEl.dataset.platformSelect); return; }
        if (event.target.closest('[data-platform-next]')) { next(1); return; }
        if (event.target.closest('[data-platform-prev]')) { next(-1); return; }
        if (event.target.closest('[data-platform-menu]')) { home.querySelector('[data-platform-mobile-menu]').hidden = false; return; }
        if (event.target.closest('[data-platform-menu-close]')) { home.querySelector('[data-platform-mobile-menu]').hidden = true; return; }
        const crossEl = event.target.closest('[data-platform-cross]');
        if (crossEl?.dataset.href) { window.location.href = crossEl.dataset.href; return; }
        if (crossEl?.dataset.target) { select(crossEl.dataset.target); return; }
        const soon = event.target.closest('[data-coming-soon]');
        if (soon) { event.preventDefault(); }
    });

    document.addEventListener('keydown', (event) => {
        if (!home.isConnected) return;
        if (event.key === 'ArrowRight') next(1);
        if (event.key === 'ArrowLeft') next(-1);
        if (event.key === 'Escape') home.querySelector('[data-platform-mobile-menu]')?.setAttribute('hidden','');
    });

    window.setTimeout(() => intro?.classList.add('is-hidden'), 3000);
    select(ids[0], false);
    liveTimer = window.setInterval(() => { liveIndex = (liveIndex + 1) % apps[ids[index]].live.length; renderLive(); }, 3000);

    const tick = (now) => {
        const delta = now - last; last = now;
        if (!interacted) {
            progress = Math.min(1, progress + delta / slideDuration);
            const active = segments[index];
            if (active) active.style.setProperty('--progress', `${progress * 100}%`);
            if (progress >= 1) { next(1); }
        }
        window.requestAnimationFrame(tick);
    };
    window.requestAnimationFrame(tick);
    window.addEventListener('pagehide', () => clearInterval(liveTimer), { once: true });
})();

// Cartoon Archive card navigation: the card is clickable, while favorite controls remain independent.
document.addEventListener('click', (event) => {
    const card = event.target.closest('[data-cartoon-card]');
    if (!card || event.target.closest('a,button,form')) return;
    const href = card.dataset.href;
    if (href) window.location.href = href;
});
document.addEventListener('keydown', (event) => {
    const card = event.target.closest('[data-cartoon-card]');
    if (card && (event.key === 'Enter' || event.key === ' ')) {
        event.preventDefault();
        const href = card.dataset.href;
        if (href) window.location.href = href;
    }
});

// Cartoon mobile navigation.
document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-cartoon-menu-toggle]');
    const menu = document.querySelector('[data-cartoon-mobile-menu]');
    if (toggle && menu) {
        menu.hidden = !menu.hidden;
        toggle.setAttribute('aria-expanded', String(!menu.hidden));
    }
});
(() => {
    const nav = document.querySelector('.cartoon-nav-shell');
    if (!nav) return;
    const update = () => nav.classList.toggle('is-scrolled', window.scrollY > 16);
    update();
    window.addEventListener('scroll', update, { passive: true });
})();

// Cartoon Archive Wear rail: keep the compact controls live and carry the
// selected configuration into the full designer without creating fake state.
(() => {
    const rail = document.querySelector('[data-home-shirt-preview]')?.closest('.cartoon-wear-panel');
    if (!rail) return;
    const preview = rail.querySelector('[data-home-shirt-preview]');
    const shirt = rail.querySelector('[data-home-shirt-base]');
    const print = rail.querySelector('[data-home-shirt-print]');
    const continueLink = rail.querySelector('.cartoon-wear-continue');
    if (!preview || !shirt || !continueLink) return;

    let color = preview.dataset.color || 'black';
    let size = rail.querySelector('[data-home-size].is-selected')?.dataset.homeSize || 'M';
    let placement = preview.dataset.placement || 'front-center';
    const baseUrl = shirt.dataset.homeShirtBase;
    const destination = continueLink.href;

    const sync = () => {
        preview.dataset.color = color;
        preview.dataset.placement = placement;
        shirt.src = `${baseUrl}/${color}.png`;
        rail.querySelectorAll('[data-home-color]').forEach((button) => button.classList.toggle('is-selected', button.dataset.homeColor === color));
        rail.querySelectorAll('[data-home-size]').forEach((button) => button.classList.toggle('is-selected', button.dataset.homeSize === size));
        rail.querySelectorAll('[data-home-placement]').forEach((button) => button.classList.toggle('is-selected', button.dataset.homePlacement === placement));
        const url = new URL(destination, window.location.origin);
        url.searchParams.set('color', color);
        url.searchParams.set('size', size);
        url.searchParams.set('placement', placement);
        continueLink.href = url.toString();
    };

    rail.addEventListener('click', (event) => {
        const colorButton = event.target.closest('[data-home-color]');
        const sizeButton = event.target.closest('[data-home-size]');
        const placementButton = event.target.closest('[data-home-placement]');
        if (colorButton) color = colorButton.dataset.homeColor;
        if (sizeButton) size = sizeButton.dataset.homeSize;
        if (placementButton) placement = placementButton.dataset.homePlacement;
        if (colorButton || sizeButton || placementButton) sync();
    });

    sync();
})();
