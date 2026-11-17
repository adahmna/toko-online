@auth
{{-- ===== LIVE CHAT CUSTOMER <-> ADMIN ===== --}}
<button type="button" class="lc-fab" id="lcFab" title="Chat dengan Admin" aria-label="Chat dengan Admin">
    <i class="fa fa-comments"></i>
    <span class="lc-badge" id="lcBadge">0</span>
</button>

<div class="lc-window" id="lcWindow">
    <div class="lc-header">
        <div>
            <div class="lc-title">Chat dengan Admin</div>
            <div class="lc-sub"><span class="lc-dot" id="lcDot"></span><span id="lcStatus">BSI Cyber Store</span></div>
        </div>
        <button type="button" class="lc-close" id="lcClose" aria-label="Tutup">&times;</button>
    </div>
    <div class="lc-messages" id="lcMessages">
        <div class="lc-msg them" id="lcEmpty">
            <div class="lc-bubble" id="lcWelcome"></div>
            <div class="lc-time"><span>BSI Cyber Store</span></div>
        </div>
    </div>
    <div class="lc-bar" id="lcBar">
        <div class="lc-bar-body">
            <div class="lc-bar-title" id="lcBarTitle"></div>
            <div class="lc-bar-text" id="lcBarText"></div>
        </div>
        <button type="button" class="lc-bar-close" id="lcBarClose" aria-label="Batal">&times;</button>
    </div>
    <div class="lc-input">
        <input type="text" id="lcInput" placeholder="Ketik pesan..." maxlength="1000" autocomplete="off">
        <button type="button" id="lcSend" aria-label="Kirim"><i class="fa fa-paper-plane"></i></button>
    </div>
</div>

<style>
.lc-fab {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    border: none;
    background: #F8694A;
    color: #fff;
    font-size: 22px;
    cursor: pointer;
    z-index: 9999;
    box-shadow: 0 4px 18px rgba(248, 105, 74, 0.45);
    transition: transform 0.2s;
    padding: 0;
}

.lc-fab:hover {
    transform: scale(1.1);
}

.lc-badge {
    display: none;
    position: absolute;
    top: -4px;
    right: -4px;
    min-width: 20px;
    height: 20px;
    line-height: 20px;
    padding: 0 5px;
    border-radius: 10px;
    background: #e74c3c;
    color: #fff;
    font-size: 11px;
    font-weight: bold;
    text-align: center;
    box-sizing: border-box;
}

.lc-window {
    position: fixed;
    bottom: 96px;
    right: 28px;
    width: 320px;
    height: 460px;
    display: none;
    flex-direction: column;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 16px;
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.18);
    overflow: hidden;
    z-index: 9997;
}

.lc-window.lc-open {
    display: flex;
}

.lc-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    background: #F8694A;
    color: #fff;
    flex-shrink: 0;
}

.lc-title {
    font-weight: 700;
    font-size: 14px;
}

.lc-sub {
    font-size: 11px;
    opacity: 0.9;
}

.lc-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    margin-right: 5px;
    border-radius: 50%;
    background: #d0d0d0;
    border: 1px solid rgba(255, 255, 255, 0.7);
    vertical-align: middle;
}

.lc-dot.on {
    background: #4cd964;
}

.lc-close {
    background: none;
    border: none;
    color: #fff;
    font-size: 24px;
    line-height: 1;
    cursor: pointer;
    opacity: 0.85;
    padding: 0;
}

.lc-close:hover {
    opacity: 1;
}

.lc-messages {
    flex: 1;
    overflow-y: auto;
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: #f7f9fc;
}

.lc-date {
    align-self: center;
    margin: 6px 0 2px;
    padding: 3px 12px;
    border-radius: 10px;
    background: rgba(0, 0, 0, 0.07);
    color: #5f6b7a;
    font-size: 11px;
    font-weight: 600;
}

.lc-msg {
    display: flex;
    flex-direction: column;
    max-width: 80%;
    -webkit-touch-callout: none;
}

.lc-msg.me {
    align-self: flex-end;
    align-items: flex-end;
}

.lc-msg.them {
    align-self: flex-start;
    align-items: flex-start;
}

.lc-bubble {
    padding: 8px 12px;
    border-radius: 14px;
    font-size: 13px;
    line-height: 1.5;
    word-break: break-word;
    white-space: pre-wrap;
}

.lc-msg.me .lc-bubble {
    background: #F8694A;
    color: #fff;
    border-bottom-right-radius: 4px;
}

.lc-msg.them .lc-bubble {
    background: #fff;
    color: #1e2a3a;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
}

/* pesan yang dihapus */
.lc-msg.lc-deleted .lc-bubble {
    background: #eceff3 !important;
    color: #8a94a6 !important;
    font-style: italic;
    box-shadow: none !important;
}

.lc-msg.lc-deleted .lc-tick-wrap {
    display: none;
}

/* kutipan balasan */
.lc-quote {
    display: block;
    margin-bottom: 6px;
    padding: 5px 8px;
    border-left: 3px solid;
    border-radius: 6px;
    font-size: 12px;
    line-height: 1.4;
    white-space: normal;
    cursor: pointer;
}

.lc-quote b {
    display: block;
    font-size: 11px;
}

.lc-quote span {
    display: block;
    opacity: 0.85;
    max-height: 34px;
    overflow: hidden;
}

.lc-msg.me .lc-quote {
    background: rgba(0, 0, 0, 0.14);
    border-color: rgba(255, 255, 255, 0.85);
}

.lc-msg.them .lc-quote {
    background: rgba(0, 0, 0, 0.05);
    border-color: #F8694A;
}

.lc-flash .lc-bubble {
    outline: 2px solid #F8694A;
    outline-offset: 2px;
}

.lc-time {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 2px;
    font-size: 10px;
    color: #aaa;
}

.lc-edited {
    font-style: italic;
}

/* ===== Tanda centang status pesan ===== */
.lc-tick {
    width: 16px;
    height: 11px;
    flex-shrink: 0;
}

.lc-tick path {
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.lc-tick.sent,
.lc-tick.delivered {
    color: #9aa0a6;
}

.lc-tick.read {
    color: #34b7f1;
}

/* ===== Bar balas / edit ===== */
.lc-bar {
    display: none;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: #fff4f0;
    border-top: 1px solid #fde1d8;
    font-size: 12px;
    flex-shrink: 0;
}

.lc-bar.show {
    display: flex;
}

.lc-bar-body {
    flex: 1;
    min-width: 0;
    padding-left: 8px;
    border-left: 3px solid #F8694A;
}

.lc-bar-title {
    font-weight: 700;
    color: #c4492e;
}

.lc-bar-text {
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.lc-bar-close {
    background: none;
    border: none;
    font-size: 22px;
    line-height: 1;
    color: #999;
    cursor: pointer;
    padding: 0 4px;
}

/* ===== Menu klik kanan / tekan tahan ===== */
.lc-menu {
    position: fixed;
    z-index: 10050;
    display: none;
    min-width: 180px;
    padding: 6px 0;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 6px 28px rgba(0, 0, 0, 0.22);
    font-size: 13px;
}

.lc-menu.show {
    display: block;
}

.lc-menu-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 16px;
    color: #1e2a3a;
    cursor: pointer;
}

.lc-menu-item:hover {
    background: #f3f5f9;
}

.lc-menu-item.danger {
    color: #e74c3c;
}

.lc-menu-item i {
    width: 14px;
    text-align: center;
}

.lc-menu-info {
    padding: 10px 16px;
    line-height: 1.8;
    color: #1e2a3a;
    white-space: nowrap;
}

.lc-menu-info b {
    display: block;
    margin-bottom: 2px;
}

.lc-toast {
    position: fixed;
    left: 50%;
    bottom: 40px;
    z-index: 10060;
    padding: 9px 18px;
    border-radius: 20px;
    background: rgba(30, 42, 58, 0.92);
    color: #fff;
    font-size: 13px;
    transform: translateX(-50%);
    pointer-events: none;
}

.lc-input {
    display: flex;
    gap: 8px;
    padding: 10px 12px;
    background: #fff;
    border-top: 1px solid #e8eaf0;
    flex-shrink: 0;
}

.lc-input input {
    flex: 1;
    border: 1px solid #dde3f0;
    border-radius: 20px;
    padding: 8px 14px;
    font-size: 13px;
    outline: none;
}

.lc-input input:focus {
    border-color: #F8694A;
}

.lc-input button {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #F8694A;
    color: #fff;
    cursor: pointer;
    flex-shrink: 0;
}

.lc-input button:disabled {
    background: #b0bec5;
    cursor: not-allowed;
}

@media (max-width: 767px) {
    .lc-window {
        right: 10px;
        bottom: 96px;
        width: calc(100vw - 20px);
        height: 60vh;
        z-index: 10001;
    }
}

/* Dark mode */
[data-theme="dark"] .lc-window {
    background: #1a1a1a;
    border-color: #333;
}

[data-theme="dark"] .lc-messages {
    background: #141414;
}

[data-theme="dark"] .lc-msg.them .lc-bubble {
    background: #242424;
    color: #e6e6e6;
    box-shadow: none;
}

[data-theme="dark"] .lc-msg.lc-deleted .lc-bubble {
    background: #1f1f1f !important;
    color: #777 !important;
}

[data-theme="dark"] .lc-date {
    background: rgba(255, 255, 255, 0.1);
    color: #b5b5b5;
}

[data-theme="dark"] .lc-time {
    color: #888;
}

[data-theme="dark"] .lc-bar {
    background: #2a1d19;
    border-color: #3a2a24;
}

[data-theme="dark"] .lc-bar-text {
    color: #aaa;
}

[data-theme="dark"] .lc-input {
    background: #1a1a1a;
    border-color: #333;
}

[data-theme="dark"] .lc-input input {
    background: #222;
    color: #f1f1f1;
    border-color: #3a3a3a;
}

[data-theme="dark"] .lc-menu {
    background: #242424;
}

[data-theme="dark"] .lc-menu-item,
[data-theme="dark"] .lc-menu-info {
    color: #e6e6e6;
}

[data-theme="dark"] .lc-menu-item:hover {
    background: #2f2f2f;
}
</style>

<script>
(function() {
    const fab = document.getElementById('lcFab');
    const win = document.getElementById('lcWindow');
    const closeBtn = document.getElementById('lcClose');
    const list = document.getElementById('lcMessages');
    const empty = document.getElementById('lcEmpty');
    const welcome = document.getElementById('lcWelcome');
    const dot = document.getElementById('lcDot');
    const statusEl = document.getElementById('lcStatus');
    const input = document.getElementById('lcInput');
    const sendBtn = document.getElementById('lcSend');
    const badge = document.getElementById('lcBadge');
    const bar = document.getElementById('lcBar');
    const barTitle = document.getElementById('lcBarTitle');
    const barText = document.getElementById('lcBarText');
    const barClose = document.getElementById('lcBarClose');

    const BASE = "{{ url('livechat') }}";
    const URL_MESSAGES = "{{ route('livechat.messages') }}";
    const URL_SEND = "{{ route('livechat.send') }}";
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const EDIT_LIMIT = 15 * 60 * 1000; // batas edit 15 menit

    let lastId = 0;
    let lastDateKey = '';
    let isOpen = false;
    let timer = null;
    let replyTo = null;
    let editing = null;
    const msgData = {};

    /* ===== Status admin di header ===== */
    function setAdminStatus(a) {
        if (!a) return;
        let text = 'Offline';
        if (a.online) {
            text = 'Online';
        } else if (a.seconds_ago !== null && a.seconds_ago !== undefined) {
            const m = Math.floor(a.seconds_ago / 60);
            if (m < 1) text = 'Aktif baru saja';
            else if (m < 60) text = 'Aktif ' + m + ' menit lalu';
            else if (m < 1440) text = 'Aktif ' + Math.floor(m / 60) + ' jam lalu';
            else text = 'Aktif ' + Math.floor(m / 1440) + ' hari lalu';
        }
        statusEl.textContent = text;
        dot.classList.toggle('on', !!a.online);
    }

    /* ===== Pesan sambutan (jam operasional 08.00 - 20.00 WIB) ===== */
    function jamOperasional() {
        const jam = parseInt(new Intl.DateTimeFormat('en-GB', {
            hour: '2-digit',
            hour12: false,
            timeZone: 'Asia/Jakarta'
        }).format(new Date()), 10);
        return jam >= 8 && jam < 20;
    }

    function setWelcome() {
        welcome.textContent = jamOperasional() ?
            'Halo! Terima kasih sudah menghubungi BSI Cyber Store. Jam operasional kami 08.00 - 20.00 WIB. Silakan tulis pesanmu, kami akan membalas secepatnya.' :
            'Halo! Terima kasih sudah menghubungi BSI Cyber Store. Saat ini di luar jam operasional (08.00 - 20.00 WIB). Tulis saja pesanmu, kami akan membalas di jam operasional berikutnya.';
    }

    /* ===== Pemisah tanggal ala WhatsApp ===== */
    function dateKey(iso) {
        const d = new Date(iso);
        return d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
    }

    function dateLabel(iso) {
        const d = new Date(iso);
        const today = new Date();
        const a = new Date(d.getFullYear(), d.getMonth(), d.getDate());
        const b = new Date(today.getFullYear(), today.getMonth(), today.getDate());
        const diff = Math.round((b - a) / 86400000);
        if (diff === 0) return 'Hari ini';
        if (diff === 1) return 'Kemarin';
        return d.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    function makeDateSep(iso) {
        const el = document.createElement('div');
        el.className = 'lc-date';
        el.dataset.ts = iso;
        el.textContent = dateLabel(iso);
        return el;
    }

    function relabelDates() {
        list.querySelectorAll('.lc-date').forEach(function(el) {
            el.textContent = dateLabel(el.dataset.ts);
        });
    }

    function recalcDateKey() {
        const rows = list.querySelectorAll('.lc-msg[data-id]');
        if (!rows.length) {
            lastDateKey = '';
            return;
        }
        const last = rows[rows.length - 1];
        lastDateKey = dateKey(msgData[last.dataset.id].created_at);
    }

    function fmtTime(iso) {
        return new Date(iso).toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function fmtFull(iso) {
        if (!iso) return '-';
        return new Date(iso).toLocaleString('id-ID', {
            day: 'numeric',
            month: 'short',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    /* ===== Centang status ===== */
    function tickSvg(status) {
        const one = '<path d="M1 6l4 4 8-9"/>';
        const two = '<path d="M1 6l4 4 8-9"/><path d="M7 9l1 1 8-9"/>';
        return '<svg class="lc-tick ' + status + '" viewBox="0 0 17 12">' + (status === 'sent' ? one : two) +
            '</svg>';
    }

    function setTick(id, status) {
        const el = document.querySelector('#lc-msg-' + id + ' .lc-tick-wrap');
        if (!el || el.dataset.status === status) return;
        el.dataset.status = status;
        el.innerHTML = tickSvg(status);
    }

    /* ===== Toast kecil ===== */
    function toast(text) {
        const el = document.createElement('div');
        el.className = 'lc-toast';
        el.textContent = text;
        document.body.appendChild(el);
        setTimeout(function() {
            el.remove();
        }, 1800);
    }

    async function post(url, body) {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify(body || {})
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    }

    /* ===== Render pesan ===== */
    function fillRow(row, m) {
        const mine = m.sender === 'customer';
        const bubble = row.querySelector('.lc-bubble');
        bubble.innerHTML = '';
        row.classList.toggle('lc-deleted', !!m.deleted);

        if (m.reply && !m.deleted) {
            const q = document.createElement('div');
            q.className = 'lc-quote';
            q.dataset.id = m.reply.id;
            const b = document.createElement('b');
            b.textContent = m.reply.sender === 'customer' ? 'Anda' : 'Admin';
            const s = document.createElement('span');
            s.textContent = m.reply.deleted ? 'Pesan dihapus' : m.reply.message;
            q.appendChild(b);
            q.appendChild(s);
            bubble.appendChild(q);
        }

        const t = document.createElement('span');
        t.textContent = m.deleted ? (mine ? 'Anda menghapus pesan ini' : 'Pesan ini telah dihapus') : m.message;
        bubble.appendChild(t);

        const ed = row.querySelector('.lc-edited');
        if (ed) ed.style.display = (m.edited && !m.deleted) ? '' : 'none';
    }

    function addMsg(m) {
        if (document.getElementById('lc-msg-' + m.id)) return;

        const key = dateKey(m.created_at);
        if (key !== lastDateKey) {
            list.appendChild(makeDateSep(m.created_at));
            lastDateKey = key;
        }

        const mine = m.sender === 'customer';
        msgData[m.id] = m;

        const row = document.createElement('div');
        row.id = 'lc-msg-' + m.id;
        row.dataset.id = m.id;
        row.className = 'lc-msg ' + (mine ? 'me' : 'them');

        const bubble = document.createElement('div');
        bubble.className = 'lc-bubble';

        const time = document.createElement('div');
        time.className = 'lc-time';

        const ed = document.createElement('span');
        ed.className = 'lc-edited';
        ed.textContent = 'Diedit';
        ed.style.display = 'none';
        time.appendChild(ed);

        const t = document.createElement('span');
        t.textContent = fmtTime(m.created_at);
        time.appendChild(t);

        if (mine) {
            const status = m.status || 'sent';
            const wrap = document.createElement('span');
            wrap.className = 'lc-tick-wrap';
            wrap.dataset.status = status;
            wrap.innerHTML = tickSvg(status);
            time.appendChild(wrap);
        }

        row.appendChild(bubble);
        row.appendChild(time);
        fillRow(row, m);
        list.appendChild(row);

        if (m.id > lastId) lastId = m.id;
        if (empty) empty.style.display = 'none';
    }

    // terapkan perubahan terbaru (centang, edit, hapus) ke pesan yang sudah tampil
    function applyState(id, st) {
        const m = msgData[id];
        const row = document.getElementById('lc-msg-' + id);
        if (!m || !row) return;

        if (st.deleted !== m.deleted || st.edited !== m.edited || (!st.deleted && st.message !== m.message)) {
            m.deleted = st.deleted;
            m.edited = st.edited;
            m.message = st.message;
            fillRow(row, m);
        }

        m.delivered_at = st.delivered_at;
        m.read_at = st.read_at;
        m.status = st.status;
        if (m.sender === 'customer') setTick(id, st.status);
    }

    function removeRow(id) {
        const row = document.getElementById('lc-msg-' + id);
        if (!row) return;
        const prev = row.previousElementSibling;
        const next = row.nextElementSibling;
        row.remove();
        delete msgData[id];
        if (prev && prev.classList.contains('lc-date') && (!next || next.classList.contains('lc-date'))) {
            prev.remove();
        }
        recalcDateKey();
        if (!list.querySelector('.lc-msg[data-id]')) empty.style.display = '';
    }

    /* ===== Bar balas / edit ===== */
    function showBar(title, text) {
        barTitle.textContent = title;
        barText.textContent = text;
        bar.classList.add('show');
        input.focus();
    }

    function hideBar() {
        replyTo = null;
        editing = null;
        bar.classList.remove('show');
    }

    function cancelCompose() {
        if (editing) input.value = '';
        hideBar();
    }

    function startReply(m) {
        editing = null;
        replyTo = m;
        showBar('Membalas ' + (m.sender === 'customer' ? 'pesan Anda' : 'Admin'), m.message);
    }

    function startEdit(m) {
        replyTo = null;
        editing = m;
        input.value = m.message;
        showBar('Edit pesan', m.message);
    }

    function fallbackCopy(text) {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try {
            document.execCommand('copy');
        } catch (e) {}
        ta.remove();
    }

    function copyText(text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).catch(function() {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
        toast('Pesan disalin');
    }

    async function deleteMsg(m, mode) {
        const ask = mode === 'all' ? 'Hapus pesan ini untuk semua orang?' : 'Hapus pesan ini untuk Anda?';
        if (!confirm(ask)) return;
        try {
            const data = await post(BASE + '/' + m.id + '/delete', {
                mode: mode
            });
            if (mode === 'me') {
                removeRow(m.id);
                if (replyTo && replyTo.id === m.id) hideBar();
            } else {
                applyState(m.id, data.message);
                if (replyTo && replyTo.id === m.id) hideBar();
            }
        } catch (e) {
            toast('Gagal menghapus pesan');
        }
    }

    /* ===== Menu klik kanan / tekan tahan ===== */
    const menu = document.createElement('div');
    menu.className = 'lc-menu';
    document.body.appendChild(menu);
    let openedAt = 0;

    function closeMenu() {
        menu.classList.remove('show');
    }

    function placeMenu(x, y) {
        menu.classList.add('show');
        openedAt = Date.now();
        const r = menu.getBoundingClientRect();
        menu.style.left = Math.max(8, Math.min(x, window.innerWidth - r.width - 8)) + 'px';
        menu.style.top = Math.max(8, Math.min(y, window.innerHeight - r.height - 8)) + 'px';
    }

    function menuItem(label, icon, fn, cls) {
        const el = document.createElement('div');
        el.className = 'lc-menu-item' + (cls ? ' ' + cls : '');
        const i = document.createElement('i');
        i.className = 'fa ' + icon;
        const s = document.createElement('span');
        s.textContent = label;
        el.appendChild(i);
        el.appendChild(s);
        el.addEventListener('click', function(e) {
            e.stopPropagation();
            if (Date.now() - openedAt < 350) return; // cegah klik tak sengaja setelah tekan tahan
            closeMenu();
            fn();
        });
        return el;
    }

    function showInfo(m, x, y) {
        menu.innerHTML = '';
        const box = document.createElement('div');
        box.className = 'lc-menu-info';
        const title = document.createElement('b');
        title.textContent = 'Info pesan';
        box.appendChild(title);
        [
            ['Terkirim', fmtFull(m.created_at)],
            ['Diterima', fmtFull(m.delivered_at)],
            ['Dibaca', fmtFull(m.read_at)]
        ].forEach(function(r) {
            const line = document.createElement('div');
            line.textContent = r[0] + ': ' + r[1];
            box.appendChild(line);
        });
        menu.appendChild(box);
        placeMenu(x, y);
    }

    function openMenu(id, x, y) {
        const m = msgData[id];
        if (!m) return;
        const mine = m.sender === 'customer';
        menu.innerHTML = '';

        if (!m.deleted) {
            menu.appendChild(menuItem('Balas', 'fa-reply', function() {
                startReply(m);
            }));
            menu.appendChild(menuItem('Salin', 'fa-copy', function() {
                copyText(m.message);
            }));
        }
        if (mine && !m.deleted) {
            menu.appendChild(menuItem('Info pesan', 'fa-info-circle', function() {
                showInfo(m, x, y);
            }));
            if (Date.now() - new Date(m.created_at).getTime() <= EDIT_LIMIT) {
                menu.appendChild(menuItem('Edit', 'fa-pencil', function() {
                    startEdit(m);
                }));
            }
        }
        menu.appendChild(menuItem('Hapus untuk saya', 'fa-trash', function() {
            deleteMsg(m, 'me');
        }, 'danger'));
        if (mine && !m.deleted) {
            menu.appendChild(menuItem('Hapus untuk semua', 'fa-trash', function() {
                deleteMsg(m, 'all');
            }, 'danger'));
        }
        placeMenu(x, y);
    }

    // klik kanan (desktop / Android)
    list.addEventListener('contextmenu', function(e) {
        const row = e.target.closest('.lc-msg[data-id]');
        if (!row) return;
        e.preventDefault();
        openMenu(row.dataset.id, e.clientX, e.clientY);
    });

    // tekan tahan (HP / iOS)
    let pressTimer = null;
    list.addEventListener('touchstart', function(e) {
        const row = e.target.closest('.lc-msg[data-id]');
        if (!row) return;
        const t = e.touches[0];
        clearTimeout(pressTimer);
        pressTimer = setTimeout(function() {
            openMenu(row.dataset.id, t.clientX, t.clientY);
        }, 500);
    }, {
        passive: true
    });
    ['touchmove', 'touchend', 'touchcancel'].forEach(function(ev) {
        list.addEventListener(ev, function() {
            clearTimeout(pressTimer);
        }, {
            passive: true
        });
    });

    // klik kutipan -> lompat ke pesan asli
    list.addEventListener('click', function(e) {
        const q = e.target.closest('.lc-quote');
        if (!q) return;
        const target = document.getElementById('lc-msg-' + q.dataset.id);
        if (!target) return;
        target.scrollIntoView({
            block: 'center',
            behavior: 'smooth'
        });
        target.classList.add('lc-flash');
        setTimeout(function() {
            target.classList.remove('lc-flash');
        }, 1300);
    });

    document.addEventListener('click', closeMenu);
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeMenu();
    });
    list.addEventListener('scroll', closeMenu);
    window.addEventListener('resize', closeMenu);

    /* ===== Polling ===== */
    function setBadge(n) {
        if (n > 0 && !isOpen) {
            badge.style.display = 'block';
            badge.textContent = n > 9 ? '9+' : n;
        } else {
            badge.style.display = 'none';
        }
    }

    async function poll() {
        try {
            const res = await fetch(URL_MESSAGES + '?after=' + lastId + '&open=' + (isOpen ? 1 : 0), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (res.ok) {
                const data = await res.json();
                const nearBottom = list.scrollTop + list.clientHeight >= list.scrollHeight - 50;
                data.messages.forEach(addMsg);

                if (data.states) {
                    Object.keys(data.states).forEach(function(id) {
                        applyState(id, data.states[id]);
                    });
                }

                if (data.messages.length && (nearBottom || lastId === data.messages[0].id)) {
                    list.scrollTop = list.scrollHeight;
                }
                setBadge(data.unread);
                setAdminStatus(data.admin);
                relabelDates();
            }
        } catch (e) {}
        schedule();
    }

    function schedule() {
        clearTimeout(timer);
        const delay = document.hidden ? 15000 : (isOpen ? 3000 : 10000);
        timer = setTimeout(poll, delay);
    }

    /* ===== Kirim / simpan edit ===== */
    async function send() {
        const text = input.value.trim();
        if (!text) return;

        sendBtn.disabled = true;
        try {
            if (editing) {
                const data = await post(BASE + '/' + editing.id + '/edit', {
                    message: text
                });
                applyState(editing.id, data.message);
                hideBar();
                input.value = '';
            } else {
                const data = await post(URL_SEND, {
                    message: text,
                    reply_to_id: replyTo ? replyTo.id : null
                });
                addMsg(data.message);
                hideBar();
                input.value = '';
                list.scrollTop = list.scrollHeight;
            }
        } catch (e) {
            toast(editing ? 'Gagal menyimpan (batas edit 15 menit)' : 'Pesan gagal dikirim');
        }
        sendBtn.disabled = false;
        input.focus();
    }

    fab.addEventListener('click', function() {
        isOpen = !isOpen;
        win.classList.toggle('lc-open', isOpen);
        if (isOpen) {
            setBadge(0);
            input.focus();
            poll();
        }
    });

    closeBtn.addEventListener('click', function() {
        isOpen = false;
        win.classList.remove('lc-open');
        closeMenu();
    });

    barClose.addEventListener('click', cancelCompose);
    sendBtn.addEventListener('click', send);
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            send();
        } else if (e.key === 'Escape') {
            cancelCompose();
        }
    });

    setWelcome();
    poll();
})();
</script>
@endauth