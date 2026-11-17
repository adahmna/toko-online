(function () {
    var root = document.documentElement;
    var btn = document.getElementById('themeToggle');
    var scope = document.getElementById('main') || document.getElementById('store');

    /* ===== Toggle tema ===== */
    function setIcon(theme) {
        if (!btn) return;
        btn.textContent = theme === 'dark' ? '☀️' : '🌙';
        btn.setAttribute('title', theme === 'dark' ? 'Ganti ke mode terang' : 'Ganti ke mode gelap');
    }

    function isDark() {
        return root.getAttribute('data-theme') === 'dark';
    }

    function applyTheme(theme) {
        root.setAttribute('data-theme', theme);
        try {
            localStorage.setItem('theme', theme);
        } catch (e) {}
        setIcon(theme);
        autoFix();
    }

    /* ===== Auto-fix halaman konten (akun, wishlist, bandingkan, tentang, dll.) =====
       Elemen di dalam #store yang masih berlatar putih/terang diberi class dm-bg,
       teks gelapnya diberi dm-text, border terangnya diberi dm-border.
       Semua class dicabut lagi saat kembali ke mode terang. */
    var SKIP_TAGS = {
        IMG: 1, SVG: 1, VIDEO: 1, CANVAS: 1, IFRAME: 1, SCRIPT: 1, STYLE: 1,
        OPTION: 1, PATH: 1, USE: 1
    };
    var SKIP_INPUT_TYPES = { checkbox: 1, radio: 1, button: 1, submit: 1, reset: 1, image: 1, range: 1, color: 1 };

    function parse(c) {
        var m = c && c.match(/rgba?\(([^)]+)\)/);
        if (!m) return null;
        var p = m[1].split(/[,\s/]+/).filter(Boolean).map(parseFloat);
        return { r: p[0], g: p[1], b: p[2], a: p.length > 3 ? p[3] : 1 };
    }

    function lum(c) {
        return (0.299 * c.r + 0.587 * c.g + 0.114 * c.b) / 255;
    }

    function isGray(c) {
        return Math.max(c.r, c.g, c.b) - Math.min(c.r, c.g, c.b) < 40;
    }

    function skip(el) {
        if (SKIP_TAGS[el.tagName.toUpperCase()]) return true;
        if (el.tagName === 'INPUT' && SKIP_INPUT_TYPES[(el.type || '').toLowerCase()]) return true;
        if (el.closest('.alert, .banner, .primary-btn, .main-btn, .chatbot-window, .chatbot-fab, .notif-item')) return true;
        return false;
    }

    function effectiveBgLum(el) {
        var node = el;
        while (node && node.nodeType === 1) {
            var c = parse(getComputedStyle(node).backgroundColor);
            if (c && c.a > 0.5) return lum(c);
            node = node.parentElement;
        }
        return 0.07; // body gelap
    }

    function clearFix() {
        var marked = document.querySelectorAll('.dm-bg, .dm-text, .dm-border');
        for (var i = 0; i < marked.length; i++) {
            marked[i].classList.remove('dm-bg', 'dm-text', 'dm-border');
        }
    }

    function autoFix() {
        if (!scope) return;
        if (!isDark()) {
            clearFix();
            return;
        }

        var els = scope.querySelectorAll('*');
        var i, el, cs, c;

        // 1) latar & border terang
        for (i = 0; i < els.length; i++) {
            el = els[i];
            if (skip(el)) continue;
            cs = getComputedStyle(el);

            c = parse(cs.backgroundColor);
            if (c && c.a > 0.5 && lum(c) > 0.78 && cs.backgroundImage === 'none') {
                el.classList.add('dm-bg');
            } else if (cs.backgroundImage.indexOf('gradient') > -1 && cs.backgroundImage.indexOf('url(') === -1) {
                var cols = cs.backgroundImage.match(/rgba?\([^)]+\)/g) || [];
                var allLight = cols.length > 0;
                for (var k = 0; k < cols.length; k++) {
                    var gc = parse(cols[k]);
                    if (!gc || (gc.a > 0.5 && lum(gc) <= 0.78)) { allLight = false; break; }
                }
                if (allLight) el.classList.add('dm-bg');
            }

            var b = parse(cs.borderTopColor);
            if (b && b.a > 0.3 && lum(b) > 0.7 && parseFloat(cs.borderTopWidth) > 0) {
                el.classList.add('dm-border');
            }
        }

        // 2) teks gelap di atas latar yang sekarang gelap
        for (i = 0; i < els.length; i++) {
            el = els[i];
            if (skip(el)) continue;

            var hasText = false;
            for (var n = 0; n < el.childNodes.length; n++) {
                if (el.childNodes[n].nodeType === 3 && el.childNodes[n].nodeValue.trim() !== '') {
                    hasText = true;
                    break;
                }
            }
            if (!hasText && !el.matches('i, .fa, .fas, .far, .fab')) continue;

            c = parse(getComputedStyle(el).color);
            if (c && isGray(c) && lum(c) < 0.5 && effectiveBgLum(el) < 0.5) {
                el.classList.add('dm-text');
            }
        }
    }

    var timer = null;
    function scheduleFix() {
        clearTimeout(timer);
        timer = setTimeout(autoFix, 120);
    }

    /* ===== Init ===== */
    setIcon(isDark() ? 'dark' : 'light');

    if (btn) {
        btn.addEventListener('click', function () {
            applyTheme(isDark() ? 'light' : 'dark');
        });
    }

    autoFix();
    window.addEventListener('load', function () {
        autoFix();
        setTimeout(autoFix, 400);
    });

    if (scope && window.MutationObserver) {
        new MutationObserver(scheduleFix).observe(scope, { childList: true, subtree: true });
    }
})();