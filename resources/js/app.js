import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const cookieConsent = document.querySelector('[data-cookie-consent]');
if (cookieConsent && !document.cookie.includes('site_cookie_consent=')) {
    cookieConsent.hidden = false;
    const setConsent = (value) => {
        document.cookie = `site_cookie_consent=${value}; Max-Age=31536000; Path=/; SameSite=Lax`;
        cookieConsent.hidden = true;
    };
    cookieConsent.querySelector('[data-cookie-essential]')?.addEventListener('click', () => setConsent('essential'));
    cookieConsent.querySelector('[data-cookie-allow]')?.addEventListener('click', () => setConsent('optional'));
}
