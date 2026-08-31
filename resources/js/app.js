import './bootstrap';

const root = document.documentElement;
const storedTheme = localStorage.getItem('kipanya-theme');
root.dataset.theme = storedTheme || 'light';

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
        menu?.classList.toggle('hidden');
    }
});

updateThemeButtons();
