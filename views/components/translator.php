<?php
// Google Translate language engine (no visible Google UI).
// Uses the official translate.google.com element + the `googtrans` cookie,
// modeled on /Applications/XAMPP/xamppfiles/htdocs/land-houses-dev.
// Switching a language sets the cookie and reloads; Google translates the
// whole page in the background while the banner/UI is force-hidden via CSS + JS.
?>
<style>
/* Hide every piece of Google Translate UI (top banner, tooltip, menu iframe) */
.goog-te-banner-frame,
.goog-te-banner,
#goog-gt-tt,
.goog-te-menu-frame,
.goog-te-balloon-frame,
.skiptranslate { display: none !important; }
/* Google shifts <body> down to make room for its banner; cancel that shift */
body { top: 0 !important; position: static !important; }
</style>

<div id="google_translate_element" class="notranslate" style="display:none !important;"></div>

<script>
window.LANG_MAP = { lo: 'lo', en: 'en', th: 'th', zh: 'zh-CN' };

function setGoogTransCookie(gLang) {
    var val = '/lo/' + gLang;
    var expires = new Date(Date.now() + 365 * 864e5).toUTCString();
    document.cookie = 'googtrans=' + val + ';expires=' + expires + ';path=/';
    document.cookie = 'googtrans=' + val + ';expires=' + expires + ';path=/;domain=' + location.hostname;
    var dot = location.hostname.indexOf('.') > -1 ? '.' + location.hostname : location.hostname;
    document.cookie = 'googtrans=' + val + ';expires=' + expires + ';path=/;domain=' + dot;
}

window.setLang = function (lang) {
    var gLang = window.LANG_MAP[lang] || 'lo';
    try { localStorage.setItem('site_lang', lang); } catch (e) {}
    if (lang === 'lo') {
        setGoogTransCookie('lo');
        document.cookie = 'googtrans=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
        document.cookie = 'googtrans=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;domain=' + location.hostname;
        document.documentElement.lang = 'lo';
    } else {
        setGoogTransCookie(gLang);
        document.documentElement.lang = lang;
    }
    location.reload();
};

function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'lo',
        includedLanguages: 'lo,en,th,zh-CN',
        autoDisplay: false
    }, 'google_translate_element');
}

// Safety net: keep the Google banner/UI hidden and reset the body offset it adds.
(function () {
    var kill = function () {
        if (document.body && document.body.style.top) document.body.style.top = '0px';
        var banner = document.querySelector('.goog-te-banner-frame, iframe.goog-te-banner-frame');
        if (banner) banner.style.display = 'none';
        var tt = document.getElementById('goog-gt-tt');
        if (tt) tt.style.display = 'none';
    };
    document.addEventListener('DOMContentLoaded', function () {
        kill();
        if (window.MutationObserver) {
            new MutationObserver(kill).observe(document.documentElement, { childList: true, subtree: true, attributes: true });
        }
    });
})();
</script>
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
