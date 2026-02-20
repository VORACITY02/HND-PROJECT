const THEME_KEY = 'ims_theme';

function applyTheme(theme) {
  const root = document.documentElement;
  root.dataset.theme = theme;
}

function getPreferredTheme() {
  const stored = localStorage.getItem(THEME_KEY);
  if (stored === 'light' || stored === 'dark') return stored;
  return 'light';
}

export function initThemeToggle() {
  applyTheme(getPreferredTheme());

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-theme-toggle]');
    if (!btn) return;

    const current = document.documentElement.dataset.theme || 'dark';
    const next = current === 'dark' ? 'light' : 'dark';
    localStorage.setItem(THEME_KEY, next);
    applyTheme(next);

    const label = btn.querySelector('[data-theme-label]');
    if (label) label.textContent = next === 'dark' ? 'Dark' : 'Light';
  });
}
