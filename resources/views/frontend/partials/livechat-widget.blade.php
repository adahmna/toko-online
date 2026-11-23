{{--
  Widget live chat customer (memakai public/js/livechat.js yang sama dengan admin).
  Bisa dipakai tamu (belum login) maupun customer (role 2).
  Pasang di layout frontend, tepat sebelum </body>:
      @include('frontend.partials.livechat-widget')
--}}
@if (!auth()->check() || (string) (auth()->user()->role ?? '') === '2')

<link rel="stylesheet" href="{{ asset('css/livechat.css') }}">

{{-- Perkecil widget chat di HP (override livechat.css; ubah angka di bawah kalau mau lebih kecil/besar) --}}
<style>
@media (max-width: 767px) {
    #lcw .lcw-panel {
        top: auto !important;
        left: auto !important;
        right: 12px !important;
        bottom: 72px !important;
        width: min(290px, calc(100vw - 24px)) !important;
        height: min(360px, 50vh) !important;
        max-width: calc(100vw - 24px) !important;
        max-height: calc(100vh - 96px) !important;
        border-radius: 14px !important;
    }

    #lcw .lcw-fab {
        width: 46px !important;
        height: 46px !important;
        right: 12px !important;
        bottom: 16px !important;
    }

    #lcw .lcw-fab svg {
        width: 22px !important;
        height: 22px !important;
    }
}
</style>

@guest
{{-- guest-final --}}
<script>
(function () {
    function terapkan() {
        var comp = document.querySelector('#lcwRoot [data-lc="composer"]');
        if (!comp) return;

        comp.querySelectorAll('input[type=file]').forEach(function (f) { f.disabled = true; });

        var els = Array.prototype.slice.call(comp.querySelectorAll('button, label, a, [role=button]'));
        var emoji = els.find(function (b) {
            var t = ((typeof b.className === 'string' ? b.className : '') + ' ' + b.id + ' ' + (b.title || '') + ' ' + (b.getAttribute('aria-label') || '')).toLowerCase();
            return /emoji|smile/.test(t) || b.textContent.indexOf('\u{1F60A}') !== -1;
        });
        if (!emoji) return;

        // semua tombol yang letaknya sebelum emoji (= klip) disembunyikan
        els.forEach(function (b) {
            if (b === emoji || b.contains(emoji) || emoji.contains(b)) return;
            if (b.compareDocumentPosition(emoji) & Node.DOCUMENT_POSITION_FOLLOWING) {
                b.style.setProperty('display', 'none', 'important');
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function () {
        terapkan();
        new MutationObserver(terapkan).observe(document.body, { childList: true, subtree: true });
    });
})();
</script>
@endguest

@guest
{{-- guest-emoji-rapih --}}
<script>
(function () {
    function rapikan() {
        var comp = document.querySelector('#lcwRoot [data-lc="composer"]');
        if (!comp) return;
        var box = comp.querySelector('textarea, input[type=text], input:not([type])');
        if (!box || !box.offsetHeight) return;

        var els = Array.prototype.slice.call(comp.querySelectorAll('button, label, [role=button]'));
        var emoji = els.find(function (b) {
            var t = ((typeof b.className === 'string' ? b.className : '') + ' ' + b.id + ' ' + (b.title || '') + ' ' + (b.getAttribute('aria-label') || '')).toLowerCase();
            return /emoji|smile/.test(t) || b.textContent.indexOf('\u{1F60A}') !== -1;
        });
        if (!emoji || !emoji.offsetHeight) return;

        emoji.style.transform = '';
        var rb = box.getBoundingClientRect();
        var re = emoji.getBoundingClientRect();
        var selisih = (rb.top + rb.height / 2) - (re.top + re.height / 2);
        if (Math.abs(selisih) > 1) emoji.style.transform = 'translateY(' + selisih.toFixed(1) + 'px)';
    }
    document.addEventListener('DOMContentLoaded', function () {
        rapikan();
        setTimeout(rapikan, 300);
        setTimeout(rapikan, 1200);
        window.addEventListener('resize', rapikan);
        document.addEventListener('input', rapikan, true);
        document.addEventListener('click', function () { setTimeout(rapikan, 50); }, true);
    });
})();
</script>
@endguest

<div id="lcw">
    <button type="button" class="lcw-fab" id="lcwFab" aria-label="Buka chat dengan admin">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path
                d="M4 4h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H9l-4 3.5V15H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm16 5h-1.5v4A3.5 3.5 0 0 1 15 16.5H10.5l.9.8a2 2 0 0 0 1.3.5H17l3.5 3v-3H20a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2z" />
        </svg>
        <span class="lcw-badge" id="lcwBadge" hidden>0</span>
    </button>

    <div class="lcw-panel" id="lcwPanel" hidden>
        <div class="lcw-head">
            <div>
                <div class="lcw-title" id="lcwTitle">Chat dengan Admin</div>
                <div class="lcw-sub" id="lcwPresence"><i class="dot"></i><span>Offline</span></div>
            </div>
            <button type="button" class="lcw-close" id="lcwClose" aria-label="Tutup chat">✕</button>
        </div>
        <div class="lc" id="lcwRoot">
            <div data-lc="list" class="lc-list"></div>
            <div data-lc="composer" class="lc-composer"></div>
        </div>
    </div>
</div>

<script src="{{ asset('js/livechat.js') }}"></script>
<script>
(function() {
    var U = {
        msgs: @json(route('livechat.messages')),
        send: @json(route('livechat.send')),
        products: @json(route('livechat.products')),
        edit: @json(route('livechat.edit', '__ID__')),
        del: @json(route('livechat.delete', '__ID__')),
        confirm: @json(route('livechat.customer.confirm-address', '__ID__')),
        cancel: @json(route('livechat.customer.cancel-order', '__ID__'))
    };

    // ===== Token tamu (disimpan di browser, dipakai server untuk mengenali tamu) =====
    function makeToken() {
        if (window.crypto && crypto.randomUUID) return crypto.randomUUID();
        return 'g' + Date.now().toString(36) + Math.random().toString(36).slice(2, 12);
    }

    var GUEST = '';
    try {
        GUEST = localStorage.getItem('lc_guest_token') || '';
        if (!GUEST) {
            GUEST = makeToken();
            localStorage.setItem('lc_guest_token', GUEST);
        }
    } catch (e) {
        GUEST = makeToken(); // localStorage diblokir: token hanya berlaku selama halaman terbuka
    }

    // tambahkan ?guest=... ke URL (aman untuk URL yang sudah punya query)
    function withGuest(url) {
        return url + (url.indexOf('?') === -1 ? '?' : '&') + 'guest=' + encodeURIComponent(GUEST);
    }

    var fab = document.getElementById('lcwFab'),
        panel = document.getElementById('lcwPanel'),
        badge = document.getElementById('lcwBadge'),
        pres = document.getElementById('lcwPresence'),
        open = false;

    function ago(s) {
        if (s == null) return 'Offline';
        if (s < 60) return 'Terakhir aktif baru saja';
        if (s < 3600) return 'Terakhir aktif ' + Math.floor(s / 60) + ' menit lalu';
        if (s < 86400) return 'Terakhir aktif ' + Math.floor(s / 3600) + ' jam lalu';
        return 'Terakhir aktif beberapa hari lalu';
    }

    var core = LiveChat.create({
        mode: 'customer',
        root: document.getElementById('lcwRoot'),
        csrf: @json(csrf_token()),
        interval: function() {
            return open ? 2000 : 6000;
        },
        urls: {
            messages: function(after) {
                return withGuest(U.msgs + '?after=' + after + '&open=' + (open ? 1 : 0));
            },
            send: function() {
                return withGuest(U.send);
            },
            @auth
products: function(q) {
                return withGuest(U.products + '?q=' + encodeURIComponent(q || ''));
            },
@endauth
            edit: function(id) {
                return withGuest(U.edit.replace('__ID__', id));
            },
            del: function(id) {
                return withGuest(U.del.replace('__ID__', id));
            },
            confirm: function(id) {
                return withGuest(U.confirm.replace('__ID__', id));
            },
            shop: @json(url('/beranda')),
            cancel: function(id) {
                return withGuest(U.cancel.replace('__ID__', id));
            }
        },
        onPoll: function(j) {
            var n = open ? 0 : (j.unread || 0);
            badge.hidden = n < 1;
            badge.textContent = n > 99 ? '99+' : n;

            if (j.admin) {
                document.getElementById('lcwTitle').textContent = j.admin.name || 'Chat dengan Admin';
                pres.classList.toggle('online', !!j.admin.online);
                pres.querySelector('span').textContent = j.admin.online ? 'Online' : ago(j.admin
                    .seconds_ago);
            }
        }
    });
    core.start();

    function toggle(show) {
        open = show;
        panel.hidden = !show;
        if (show) {
            badge.hidden = true;
            core.kick(); // langsung tandai pesan admin sebagai dibaca
            core.scrollBottom();
            core.focus();
        }
    }
    fab.addEventListener('click', function() {
        toggle(panel.hidden);
    });
    document.getElementById('lcwClose').addEventListener('click', function() {
        toggle(false);
    });
})();
</script>
@endif