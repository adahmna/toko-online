{{--
  Halaman admin: Live Chat Customer.
  PENTING: dua baris di bawah ini (@extends & @section) harus sama dengan view backend kamu yang lain,
  misalnya resources/views/backend/v_user/index.blade.php. Kalau layout kamu bernama lain, ganti 'backend.v_layouts.app'.
--}}
@extends('backend.v_layouts.app')
@section('content')
@php
$lcStickers = collect(glob(public_path('stickers/*.*')) ?: [])
->map(fn ($p) => basename($p))
->filter(fn ($n) => preg_match('/^[A-Za-z0-9_\-]+\.(png|webp|gif|jpe?g)$/i', $n))
->sort()->values();
@endphp

<link rel="stylesheet" href="{{ asset('css/livechat.css') }}">

<style>
.lcx-tabs {
    display: flex;
    border-bottom: 1px solid #eee;
}

.lcx-tab {
    flex: 1;
    border: 0;
    background: #fff;
    padding: 10px 4px;
    font-size: 12px;
    font-weight: 600;
    color: #888;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    white-space: nowrap;
}

.lcx-tab.on {
    color: #f4663f;
    border-bottom-color: #f4663f;
}

.lcx-count {
    display: inline-block;
    background: #eee;
    color: #666;
    font-size: 11px;
    padding: 0 7px;
    border-radius: 9px;
    margin-left: 3px;
}

.lcx-right {
    display: flex;
    align-items: center;
    gap: 2px;
    flex: none;
}

.lcx-pin {
    font-size: 12px;
    margin-right: 2px;
}

.lcx-more {
    border: 0;
    background: transparent;
    font-size: 18px;
    line-height: 1;
    padding: 2px 8px;
    border-radius: 50%;
    cursor: pointer;
    color: #888;
}

.lcx-more:hover {
    background: #e9e9e9;
    color: #333;
}

.lcx-badge {
    display: inline-block;
    background: #e74c3c;
    color: #fff;
    font-size: 10px;
    font-weight: 600;
    padding: 1px 6px;
    border-radius: 8px;
    margin-left: 6px;
    vertical-align: middle;
}

.lcx-badge.grey {
    background: #999;
}

/* baris percakapan: klik kanan / tahan lama membuka menu, jadi matikan seleksi teks & callout bawaan HP */
.lca-conv {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    user-select: none;
}

/* nama customer yang akunnya sudah dihapus */
.lcx-gone {
    color: #999 !important;
    font-style: italic;
    font-weight: 500;
}

.lcx-gone-note {
    padding: 14px;
    text-align: center;
    font-size: 13px;
    color: #888;
    background: #fafafa;
    border-top: 1px solid #eee;
}

.lcx-menu {
    position: fixed;
    z-index: 10000;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, .22);
    padding: 4px 0;
    min-width: 200px;
}

.lcx-menu button {
    display: block;
    width: 100%;
    text-align: left;
    border: 0;
    background: transparent;
    padding: 9px 14px;
    font-size: 13px;
    cursor: pointer;
    color: #2c3e50;
}

.lcx-menu button:hover {
    background: #fff3ee;
}

.lcx-menu button.danger {
    color: #e74c3c;
}

/* daftar akun diblokir */
.lcx-bl {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-bottom: 1px solid #f2f2f2;
}

.lcx-bl-info {
    flex: 1;
    min-width: 0;
}

.lcx-bl-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
}

.lcx-bl-sub {
    font-size: 12px;
    color: #888;
    margin-top: 2px;
    word-break: break-word;
}

.lcx-unblock {
    flex: none;
    border: 1px solid #f4663f;
    background: #fff;
    color: #f4663f;
    border-radius: 16px;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.lcx-unblock:hover {
    background: #f4663f;
    color: #fff;
}
</style>

<h4 style="margin:0 0 14px;color:#2c3e50;font-weight:600">Live Chat Customer</h4>

<div class="lca-wrap">
    <div class="lca-card">
        <div class="lca-head"><span>Percakapan</span><span class="lca-total" id="lcaTotal" hidden>0</span></div>
        <div class="lcx-tabs">
            <button type="button" class="lcx-tab on" data-tab="active">Aktif</button>
            <button type="button" class="lcx-tab" data-tab="archived">Arsip <span class="lcx-count"
                    id="lcxArchCount">0</span></button>
            <button type="button" class="lcx-tab" data-tab="blocked">Diblokir <span class="lcx-count"
                    id="lcxBlkCount">0</span></button>
        </div>
        <div class="lca-convs" id="lcaConvs">
            <div class="lca-empty">Memuat percakapan...</div>
        </div>
    </div>

    <div class="lca-card">
        <div class="lca-pane" id="lcaPane">
            <div class="lca-empty" style="margin:auto">Pilih percakapan di sebelah kiri untuk mulai membalas.</div>
        </div>
    </div>
</div>

<script src="{{ asset('js/livechat.js') }}"></script>
<script>
(function() {
    var CSRF = @json(csrf_token());
    var STICKERS = @json($lcStickers);
    var U = {
        conv: @json(route('backend.livechat.conversations')),
        msgs: @json(route('backend.livechat.messages', '__C__')),
        send: @json(route('backend.livechat.send', '__C__')),
        products: @json(route('backend.livechat.products')),
        edit: @json(route('backend.livechat.edit', '__ID__')),
        del: @json(route('backend.livechat.delete', '__ID__')),
        pin: @json(route('backend.livechat.pin', '__C__')),
        archive: @json(route('backend.livechat.archive', '__C__')),
        block: @json(route('backend.livechat.block', '__C__')),
        delConv: @json(route('backend.livechat.conversation.delete', '__C__'))
    };

    var PLACEHOLDER =
        '<div class="lca-empty" style="margin:auto">Pilih percakapan di sebelah kiri untuk mulai membalas.</div>';
    var DELETED_NAME = 'Customer ini sudah tidak aktif (dihapus)';

    var elConvs = document.getElementById('lcaConvs'),
        elPane = document.getElementById('lcaPane'),
        elTotal = document.getElementById('lcaTotal'),
        elArch = document.getElementById('lcxArchCount'),
        elBlk = document.getElementById('lcxBlkCount');
    var current = null,
        core = null,
        convTimer = null,
        loadSeq = 0,
        tab = 'active', // active | archived | blocked
        known = {}, // id -> {name, archived, blocked, pinned, deleted}
        menu = null;

    var esc = function(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function(c) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            })[c];
        });
    };

    function when(iso) {
        if (!iso) return '';
        var d = new Date(iso),
            n = new Date();
        if (d.toDateString() === n.toDateString()) return d.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
        return d.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short'
        });
    }

    // huruf avatar; akun terhapus pakai "?"
    function initial(nama, deleted) {
        if (deleted) return '?';
        return (nama || '?').replace('Tamu – ', '').charAt(0).toUpperCase();
    }

    /* ---------- request helper ---------- */
    function api(url, method, body) {
        return fetch(url, {
            method: method,
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(body || {})
        }).then(function(r) {
            return r.json().catch(function() {
                return {};
            }).then(function(j) {
                if (!r.ok) throw new Error(j.message || ('Gagal (' + r.status + ')'));
                return j;
            });
        });
    }

    function fail(e) {
        alert(e && e.message ? e.message : 'Terjadi kesalahan.');
    }

    /* ---------- daftar percakapan ---------- */
    function renderBlocked(list) {
        if (!list.length) {
            elConvs.innerHTML = '<div class="lca-empty">Tidak ada akun yang diblokir.</div>';
            return;
        }
        elConvs.innerHTML = list.map(function(b) {
            // nama dari server: akun terhapus sudah berisi email (atau teks bawaan kalau tidak ada)
            var nm = b.nama;
            var sub = 'Diblokir ' + when(b.blocked_at) + (b.reason ? ' · ' + esc(b.reason) : '');
            return '<div class="lcx-bl">' +
                '<div class="lca-av">' + esc(initial(b.nama, b.deleted)) + '</div>' +
                '<div class="lcx-bl-info"><div class="lcx-bl-name' + (b.deleted ? ' lcx-gone' : '') + '">' +
                esc(nm) +
                (b.is_guest ? '<span class="lcx-badge grey">Tamu</span>' : '') +
                (b.deleted ? '<span class="lcx-badge grey">Dihapus</span>' : '') + '</div>' +
                (b.email && b.email !== nm ? '<div class="lcx-bl-sub">' + esc(b.email) + '</div>' : '') +
                '<div class="lcx-bl-sub">' + sub + '</div></div>' +
                '<button type="button" class="lcx-unblock" data-unblock="' + b.customer_id +
                '" data-name="' + esc(nm) + '">Buka blokir</button></div>';
        }).join('');
    }

    function renderConvs(data) {
        elArch.textContent = data.archived_count || 0;
        elBlk.textContent = data.blocked_count || 0;
        elTotal.hidden = !data.total_unread;
        elTotal.textContent = data.total_unread;

        // sinkronkan badge di menu sidebar kalau kamu punya elemen dengan id ini
        var sb = document.getElementById('lcSidebarBadge');
        if (sb) {
            sb.hidden = !data.total_unread;
            sb.textContent = data.total_unread;
        }

        if (tab === 'blocked') {
            renderBlocked(data.blocked_accounts || []);
            return;
        }

        if (!data.conversations.length) {
            elConvs.innerHTML = '<div class="lca-empty">' +
                (tab === 'archived' ? 'Tidak ada percakapan di arsip.' : 'Belum ada percakapan.') + '</div>';
            return;
        }

        elConvs.innerHTML = data.conversations.map(function(c) {
            // nama dari server: akun terhapus sudah berisi email (atau teks bawaan kalau tidak ada)
            var nm = c.nama;
            known[c.customer_id] = {
                name: nm,
                archived: !!c.archived,
                blocked: !!c.blocked,
                pinned: !!c.pinned,
                deleted: !!c.deleted
            };
            var prefix = c.last_sender === 'admin' ? 'Anda: ' : '';
            return '<div class="lca-conv' + (current && current.id === c.customer_id ? ' active' : '') +
                (c.unread ? ' has-unread' : '') +
                '" data-id="' + c.customer_id + '" data-name="' + esc(nm) + '">' +
                '<div class="lca-av">' + esc(initial(c.nama, c.deleted)) + '</div>' +
                '<div class="lca-info"><div class="lca-top"><span class="lca-name' + (c.deleted ?
                    ' lcx-gone' : '') +
                '">' + esc(nm) +
                (c.blocked ? '<span class="lcx-badge">Diblokir</span>' : '') +
                (c.deleted ? '<span class="lcx-badge grey">Dihapus</span>' : '') +
                '</span><span class="lcx-right">' +
                (c.pinned ? '<span class="lcx-pin" title="Disematkan">📌</span>' : '') +
                '<span class="lca-time">' + when(c.last_at) + '</span>' +
                '<button type="button" class="lcx-more" data-more="' + c.customer_id +
                '" aria-label="Opsi percakapan">⋮</button></span></div>' +
                '<div class="lca-bot"><span class="lca-last">' + prefix + esc(c.last_message) +
                '</span>' +
                (c.unread ? '<span class="lca-unread">' + c.unread + '</span>' : '') +
                '</div></div></div>';
        }).join('');
    }

    function loadConvs() {
        var my = ++loadSeq;
        var qs = tab === 'archived' ? '?archived=1' : (tab === 'blocked' ? '?blocked=1' : '');
        fetch(U.conv + qs, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(function(r) {
                return r.json();
            })
            .then(function(data) {
                if (my === loadSeq) renderConvs(data);
            })
            .catch(function() {})
            .then(function() {
                if (my === loadSeq) convTimer = setTimeout(loadConvs, 3000);
            });
    }

    function refresh() {
        clearTimeout(convTimer);
        loadConvs();
    }

    /* ---------- menu opsi (⋮) ---------- */
    function closeMenu() {
        if (menu) {
            menu.remove();
            menu = null;
        }
    }

    // buka menu dari tombol ⋮ (rata kanan di bawah tombol)
    function openMenuFromBtn(btn, id) {
        var r = btn.getBoundingClientRect();
        openMenu(id, r.right, r.bottom, r.top, true);
    }

    // x,y = titik muncul menu; yAlt = posisi y alternatif kalau menu kepotong di bawah layar;
    // alignRight = menu berakhir di x (untuk tombol ⋮), kalau false menu mulai dari x (klik kanan / tahan lama)
    function openMenu(id, x, y, yAlt, alignRight) {
        closeMenu();
        var st = known[id] || {
            name: DELETED_NAME,
            archived: false,
            blocked: false,
            pinned: false,
            deleted: false
        };

        var html = '';
        if (!st.archived) {
            html += '<button type="button" data-a="pin">' + (st.pinned ? '📌 Lepas sematan' : '📌 Sematkan') +
                '</button>';
        }
        html += '<button type="button" data-a="archive">' + (st.archived ? '📤 Keluarkan dari arsip' :
            '📥 Arsipkan') + '</button>';
        // akun yang sudah dihapus tidak perlu diblokir lagi (tapi kalau sudah terlanjur diblokir, boleh dibuka)
        if (!st.deleted || st.blocked) {
            html += '<button type="button" data-a="block">' + (st.blocked ? '✅ Buka blokir' : '🚫 Blokir') +
                '</button>';
        }
        html += '<button type="button" data-a="delete" class="danger">🗑️ Hapus percakapan</button>';

        menu = document.createElement('div');
        menu.className = 'lcx-menu';
        menu.innerHTML = html;
        document.body.appendChild(menu);

        var w = menu.offsetWidth,
            h = menu.offsetHeight;
        var left = alignRight ? x - w : x;
        left = Math.max(8, Math.min(left, window.innerWidth - w - 8));
        var top = y + 4;
        if (top + h > window.innerHeight - 8) {
            top = Math.max(8, (yAlt == null ? y : yAlt) - h - 4);
        }
        menu.style.left = left + 'px';
        menu.style.top = top + 'px';

        menu.addEventListener('click', function(e) {
            var b = e.target.closest('[data-a]');
            if (!b) return;
            e.stopPropagation();
            closeMenu();
            if (b.dataset.a === 'pin') doPin(id);
            if (b.dataset.a === 'archive') doArchive(id);
            if (b.dataset.a === 'block') doBlock(id);
            if (b.dataset.a === 'delete') doDelete(id);
        });
    }

    document.addEventListener('click', closeMenu);
    window.addEventListener('resize', closeMenu);
    elConvs.addEventListener('scroll', closeMenu);

    /* ---------- aksi: sematkan / arsip / blokir / hapus ---------- */
    function doPin(id) {
        var st = known[id] || {};
        var to = !st.pinned;
        api(U.pin.replace('__C__', id), 'POST', {
                pin: to ? 1 : 0
            })
            .then(function() {
                st.pinned = to;
                refresh();
            })
            .catch(fail);
    }

    function doArchive(id) {
        var st = known[id] || {};
        var to = !st.archived;
        api(U.archive.replace('__C__', id), 'POST', {
                archive: to ? 1 : 0
            })
            .then(function() {
                st.archived = to;
                if (to) st.pinned = false;
                refresh();
            })
            .catch(fail);
    }

    function unblock(id, name) {
        if (!confirm('Buka blokir "' + name + '"? Dia bisa mengirim pesan lagi.')) return;
        api(U.block.replace('__C__', id), 'POST', {
                block: 0
            })
            .then(function() {
                if (known[id]) known[id].blocked = false;
                refresh();
                syncHeadBadge(id);
            })
            .catch(fail);
    }

    function doBlock(id) {
        var st = known[id] || {
            name: DELETED_NAME
        };

        if (st.blocked) {
            unblock(id, st.name);
            return;
        }

        var reason = prompt('Blokir "' + st.name +
            '" dari chat?\nDia tidak bisa mengirim pesan lagi.\n\nAlasan (opsional):', '');
        if (reason === null) return; // dibatalkan
        api(U.block.replace('__C__', id), 'POST', {
                block: 1,
                reason: reason
            })
            .then(function() {
                st.blocked = true;
                refresh();
                syncHeadBadge(id);
            })
            .catch(fail);
    }

    function doDelete(id) {
        var st = known[id] || {
            name: DELETED_NAME
        };
        if (!confirm('Hapus percakapan dengan "' + st.name +
                '" dari daftar?\n\nCustomer tetap bisa melihat chat-nya. Kalau dia chat lagi, percakapan muncul kembali.'
            )) return;

        api(U.delConv.replace('__C__', id), 'DELETE')
            .then(function() {
                delete known[id];
                if (current && current.id === id) closePane();
                refresh();
            })
            .catch(fail);
    }

    /* ---------- panel chat ---------- */
    function closePane() {
        if (core) {
            core.destroy();
            core = null;
        }
        current = null;
        elPane.innerHTML = PLACEHOLDER;
    }

    function syncHeadBadge(id) {
        var b = elPane.querySelector('.lcx-blocked');
        if (b && current && current.id === id) b.hidden = !(known[id] && known[id].blocked);
    }

    // akun customer sudah dihapus: sembunyikan composer, tampilkan catatan
    function lockComposer() {
        var comp = elPane.querySelector('[data-lc="composer"]');
        if (!comp || comp.dataset.off) return;
        comp.dataset.off = '1';
        comp.style.display = 'none';
        var note = document.createElement('div');
        note.className = 'lcx-gone-note';
        note.textContent = DELETED_NAME + ', jadi tidak bisa dibalas.';
        comp.parentNode.appendChild(note);
    }

    function openConv(id, name) {
        if (core) {
            core.destroy();
            core = null;
        }
        var gone = !!(known[id] && known[id].deleted);
        var shownName = name;

        current = {
            id: id,
            name: shownName
        };

        elPane.innerHTML =
            '<div class="lca-head"><span' + (gone ? ' class="lcx-gone"' : '') + '>' + esc(shownName) +
            '<span class="lcx-badge lcx-blocked" hidden>Diblokir</span>' +
            (gone ? '<span class="lcx-badge grey">Dihapus</span>' : '') + '</span>' +
            '<button type="button" class="lcx-more" data-more="' + id +
            '" aria-label="Opsi percakapan">⋮</button></div>' +
            '<div class="lc"><div data-lc="list" class="lc-list"></div><div data-lc="composer" class="lc-composer"></div></div>';
        syncHeadBadge(id);

        core = LiveChat.create({
            mode: 'admin',
            root: elPane.querySelector('.lc'),
            csrf: CSRF,
            stickers: STICKERS,
            peerName: shownName,
            interval: 2000,
            urls: {
                messages: function(after) {
                    return U.msgs.replace('__C__', id) + '?after=' + after;
                },
                send: function() {
                    return U.send.replace('__C__', id);
                },
                products: function(q) {
                    return U.products + '?q=' + encodeURIComponent(q || '');
                },
                edit: function(mid) {
                    return U.edit.replace('__ID__', mid);
                },
                del: function(mid) {
                    return U.del.replace('__ID__', mid);
                }
            },
            onPoll: function(j) {
                if (!current || current.id !== id) return;
                if (!known[id]) known[id] = {
                    name: shownName,
                    archived: false,
                    blocked: false,
                    pinned: false,
                    deleted: false
                };
                known[id].blocked = !!j.blocked;
                if (j.deleted) {
                    known[id].deleted = true;
                    lockComposer();
                }
                syncHeadBadge(id);
            },
            onSent: function() {
                refresh();
            }
        });
        core.start();
        if (gone) {
            lockComposer();
        } else {
            core.focus();
        }

        [].forEach.call(elConvs.querySelectorAll('.lca-conv'), function(el) {
            el.classList.toggle('active', +el.dataset.id === id);
        });
    }

    /* ---------- event ---------- */
    elConvs.addEventListener('click', function(e) {
        var ub = e.target.closest('[data-unblock]');
        if (ub) {
            e.stopPropagation();
            unblock(+ub.dataset.unblock, ub.dataset.name);
            return;
        }
        var more = e.target.closest('[data-more]');
        if (more) {
            e.stopPropagation();
            openMenuFromBtn(more, +more.dataset.more);
            return;
        }
        var c = e.target.closest('.lca-conv');
        if (c) openConv(+c.dataset.id, c.dataset.name);
    });

    // tombol ⋮ di header panel chat
    elPane.addEventListener('click', function(e) {
        var more = e.target.closest('[data-more]');
        if (more) {
            e.stopPropagation();
            openMenuFromBtn(more, +more.dataset.more);
        }
    });

    /* ---------- klik kanan (PC) & tahan lama (HP) pada baris percakapan ---------- */

    // klik kanan -> menu muncul tepat di kursor
    elConvs.addEventListener('contextmenu', function(e) {
        var row = e.target.closest('.lca-conv');
        if (!row) return;
        e.preventDefault();
        e.stopPropagation();
        openMenu(+row.dataset.id, e.clientX, e.clientY, e.clientY, false);
    });

    // tahan lama di layar sentuh -> menu muncul di titik sentuhan
    (function() {
        var HOLD_MS = 500, // lama tahan
            MOVE_PX = 10; // geser lebih dari ini = dianggap scroll, bukan tahan
        var timer = null,
            sx = 0,
            sy = 0,
            fired = false;

        function cancel() {
            clearTimeout(timer);
            timer = null;
        }

        elConvs.addEventListener('touchstart', function(e) {
            var row = e.target.closest('.lca-conv');
            if (!row || e.touches.length !== 1) return;
            fired = false;
            sx = e.touches[0].clientX;
            sy = e.touches[0].clientY;
            cancel();
            timer = setTimeout(function() {
                timer = null;
                fired = true;
                if (navigator.vibrate) navigator.vibrate(15);
                openMenu(+row.dataset.id, sx, sy, sy, false);
            }, HOLD_MS);
        }, {
            passive: true
        });

        elConvs.addEventListener('touchmove', function(e) {
            if (!timer) return;
            var t = e.touches[0];
            if (Math.abs(t.clientX - sx) > MOVE_PX || Math.abs(t.clientY - sy) > MOVE_PX) cancel();
        }, {
            passive: true
        });

        elConvs.addEventListener('touchend', function(e) {
            cancel();
            // setelah tahan lama, jangan lanjut ke "klik" (supaya percakapan tidak ikut terbuka & menu tidak langsung tertutup)
            if (fired) {
                e.preventDefault();
                fired = false;
            }
        }, {
            passive: false
        });

        elConvs.addEventListener('touchcancel', cancel, {
            passive: true
        });
    })();

    // tekan Esc untuk menutup menu
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeMenu();
    });

    [].forEach.call(document.querySelectorAll('.lcx-tab'), function(btn) {
        btn.addEventListener('click', function() {
            if (tab === btn.dataset.tab) return;
            tab = btn.dataset.tab;
            [].forEach.call(document.querySelectorAll('.lcx-tab'), function(b) {
                b.classList.toggle('on', b === btn);
            });
            elConvs.innerHTML = '<div class="lca-empty">Memuat percakapan...</div>';
            refresh();
        });
    });

    loadConvs();
})();
</script>
@endsection