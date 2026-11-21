/*!
 * LiveChat core (customer + admin)
 * Dipakai oleh widget customer dan halaman admin.
 *
 * LiveChat.create({
 *   mode: 'customer' | 'admin',
 *   root: Element,                 // .lc  (berisi [data-lc=list] & [data-lc=composer])
 *   csrf: '...',
 *   peerName: 'Nama lawan bicara', // dipakai untuk label kutipan balasan
 *   interval: 2500 | () => number, // jeda polling (ms)
 *   urls: { messages(after), send(), edit(id), del(id), products(q) },  // products opsional
 *   onPoll(json), onSent(message), isOpen()
 * })
 *
 * Lampiran: Dokumen, Foto, Kamera, Audio + Voice Note (tombol mikrofon seperti WhatsApp).
 * Kirim produk: tombol tas belanja (muncul jika urls.products diisi).
 * Stiker tidak lagi dikirim, tetapi pesan stiker lama tetap tampil.
 */
(function (w) {
  'use strict';

  // emoji dikelompokkan per kategori (tab di bagian atas panel)
  const EMOJI_CATS = [
    { icon: '😀', name: 'Wajah', list: ['😀','😃','😄','😁','😆','😅','😂','🤣','😊','😇','🙂','🙃','😉','😌','😍','🥰','😘','😗','😙','😚','😋','😛','😜','🤪','😝','🤑','🤗','🤭','🤫','🤔','🤐','🤨','😐','😑','😶','😏','😒','🙄','😬','😮‍💨','🤥','😔','😪','🤤','😴','😷','🤒','🤕','🤢','🤮','🥵','🥶','🥴','😵','🤯','🤠','🥳','😎','🤓','🧐','😕','😟','🙁','☹️','😮','😯','😲','😳','🥺','😦','😧','😨','😰','😥','😢','😭','😱','😖','😣','😞','😓','😩','😫','🥱','😤','😡','😠','🤬','😈','👿','💀','🤡','👻','👽','🤖'] },
    { icon: '👍', name: 'Tangan', list: ['👍','👎','👌','🤌','🤏','✌️','🤞','🤟','🤘','🤙','👈','👉','👆','👇','☝️','✋','🤚','🖐️','🖖','👋','🤝','👏','🙌','👐','🤲','🙏','✍️','💪','🦾','🫶','🫡','🫰','🫵','🖕','✊','👊','🤛','🤜','💅','🤳'] },
    { icon: '❤️', name: 'Hati', list: ['❤️','🧡','💛','💚','💙','💜','🖤','🤍','🤎','💔','❣️','💕','💞','💓','💗','💖','💘','💝','💟','♥️','😻','💋','💌','🌹','🥀','💐','🌸','🌷','🌻','🌼','✨','⭐','🌟','💫','🔥','💯','💢','💥','💦','💤'] },
    { icon: '🛒', name: 'Belanja', list: ['🛒','🛍️','🏪','🏬','📦','📮','🚚','🚛','🛵','🏍️','✈️','🚢','💳','💰','💵','💸','🪙','🧾','🏷️','🎁','🎀','🛎️','📱','💻','🖥️','⌨️','🖱️','🖨️','📷','🎧','🎮','⌚','🔋','🔌','💡','📚','📖','✏️','🖊️','📝','📒','📎','📌','✂️','🗂️','👕','👖','👗','🧥','👔','👟','👠','👜','🎒','🕶️','👓','🧢','💍','💄','🧴','🧼'] },
    { icon: '🍔', name: 'Makanan', list: ['🍔','🍟','🍕','🌭','🥪','🌮','🌯','🥗','🍝','🍜','🍲','🍛','🍣','🍱','🍤','🍙','🍚','🍘','🥟','🍢','🍡','🍧','🍨','🍦','🥧','🧁','🍰','🎂','🍮','🍭','🍬','🍫','🍿','🍩','🍪','☕','🍵','🧋','🥤','🍺','🍻','🥛','🧃','🍎','🍌','🍉','🍇','🍓','🍊','🍍','🥭','🥑','🍅','🥕','🌽','🥔','🍳','🥞','🍞','🧀'] },
    { icon: '🐱', name: 'Hewan', list: ['🐶','🐱','🐭','🐹','🐰','🦊','🐻','🐼','🐨','🐯','🦁','🐮','🐷','🐸','🐵','🙈','🙉','🙊','🐔','🐧','🐦','🐤','🦆','🦅','🦉','🦋','🐛','🐝','🐞','🐢','🐍','🐙','🦀','🐠','🐬','🐳','🦈','🐘','🦒','🐎','🌳','🌴','🌵','🍀','🌈','☀️','🌙','⛅','🌧️','⚡','❄️','🌊'] },
    { icon: '🎉', name: 'Simbol', list: ['🎉','🎊','🎈','🏆','🥇','🥈','🥉','🏅','🎯','🎵','🎶','✅','❌','⭕','❗','❓','‼️','⚠️','🚫','🔔','🔕','⏰','⏳','⌛','📅','📍','🔒','🔓','🔑','♻️','➕','➖','✔️','☑️','➡️','⬅️','⬆️','⬇️','🔄','🆗','🆕','🆒','🔝','💬','💭','📢','👀','🙋','🤷','🤦','🙇','💃','🕺','🙆','🙅'] }
  ];
  const MAX_MB = 10;
  const EDIT_MIN = 15;
  const MAX_REC_SEC = 180; // batas rekaman voice note (3 menit)

  const SVG_MIC  = '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 14a3 3 0 0 0 3-3V5a3 3 0 0 0-6 0v6a3 3 0 0 0 3 3zm5-3a5 5 0 0 1-10 0H5a7 7 0 0 0 6 6.92V21h2v-3.08A7 7 0 0 0 19 11h-2z"/></svg>';
  const SVG_SEND = '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>';

  /* ---------- util ---------- */
  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const fmtSize = b => !b ? '' : b < 1024 ? b + ' B' : b < 1048576 ? (b / 1024).toFixed(1) + ' KB' : (b / 1048576).toFixed(1) + ' MB';
  const mmss = s => { s = Math.max(0, Math.round(s || 0)); return Math.floor(s / 60) + ':' + String(s % 60).padStart(2, '0'); };
  const rupiah = n => 'Rp ' + Number(n || 0).toLocaleString('id-ID');
  const timeStr = iso => new Date(iso).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
  const dayKey = d => { d = new Date(d); return d.getFullYear() + '-' + d.getMonth() + '-' + d.getDate(); };
  function dayLabel(iso) {
    const now = new Date(), y = new Date();
    y.setDate(now.getDate() - 1);
    if (dayKey(iso) === dayKey(now)) return 'Hari ini';
    if (dayKey(iso) === dayKey(y)) return 'Kemarin';
    return new Date(iso).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
  }

  function tick(status) {
    const one = '<path d="M1.5 6l3.2 3.2L11.5 2"/>';
    const two = one + '<path d="M6.5 8.7l.5.5L14.5 2"/>';
    return '<svg class="lc-tick ' + status + '" viewBox="0 0 16 11" width="16" height="11" fill="none" stroke="currentColor" ' +
           'stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">' + (status === 'sent' ? one : two) + '</svg>';
  }

  // gaya tambahan (kartu produk, pemilih produk, tab emoji) - disuntik sekali
  function injectStyle() {
    if (document.getElementById('lc-x-style')) return;
    const s = document.createElement('style');
    s.id = 'lc-x-style';
    s.textContent =
      '.lc-pcard{display:flex;gap:10px;align-items:center;background:#fff;color:#2c3e50;border-radius:10px;padding:8px;min-width:210px;max-width:260px;text-decoration:none!important;box-shadow:0 1px 3px rgba(0,0,0,.12)}' +
      '.lc-pcard:hover{background:#fffaf8}' +
      '.lc-pcard-img{width:64px;height:64px;border-radius:8px;object-fit:cover;background:#f1f1f1;flex:none;display:flex;align-items:center;justify-content:center;font-size:26px}' +
      '.lc-pcard-tx{min-width:0;flex:1}' +
      '.lc-pcard-tx b{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-size:13px;line-height:1.3;font-weight:600;color:#2c3e50}' +
      '.lc-pcard-pr{color:#e0452b;font-weight:700;font-size:14px;margin-top:3px}' +
      '.lc-pcard-go{color:#f4663f;font-size:11px;margin-top:2px}' +
      '.lc-pcard.is-missing{opacity:.7;font-size:12px;padding:12px}' +
      '.lc-x-tabs{display:flex;gap:2px;overflow-x:auto;border-bottom:1px solid #eee;padding:4px 6px;position:sticky;top:0;background:#fff;z-index:1}' +
      '.lc-x-tabs button{border:0;background:transparent;font-size:18px;padding:4px 8px;border-radius:8px;cursor:pointer;flex:none}' +
      '.lc-x-tabs button.on{background:#ffe9e1}' +
      '.lc-x-scroll{max-height:190px;overflow-y:auto}' +
      '.lc-x-prod{display:flex;flex-direction:column;max-height:290px}' +
      '.lc-x-q{margin:8px;padding:8px 12px;border:1px solid #ddd;border-radius:18px;font-size:13px;outline:none;width:calc(100% - 16px);box-sizing:border-box}' +
      '.lc-x-q:focus{border-color:#f4663f}' +
      '.lc-x-plist{overflow-y:auto;padding:0 8px 8px}' +
      '.lc-x-item{display:flex;gap:10px;align-items:center;padding:8px;border-radius:10px;cursor:pointer}' +
      '.lc-x-item:hover{background:#fff3ee}' +
      '.lc-x-item img,.lc-x-item .ph{width:48px;height:48px;border-radius:8px;object-fit:cover;background:#f1f1f1;flex:none;display:flex;align-items:center;justify-content:center}' +
      '.lc-x-item .tx{flex:1;min-width:0}' +
      '.lc-x-item .tx b{display:block;font-size:13px;font-weight:600;color:#2c3e50;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}' +
      '.lc-x-item .tx span{font-size:13px;color:#e0452b;font-weight:700}' +
      '.lc-x-item button{border:0;background:#f4663f;color:#fff;border-radius:14px;padding:5px 12px;font-size:12px;cursor:pointer;flex:none}' +
      '.lc-x-empty{padding:18px;text-align:center;color:#999;font-size:13px}' +
      '/* lc-wa-toggle */' +
      '[data-lc=composer] .lc-send{display:none!important}' +
      '[data-lc=composer].has-content .lc-send{display:inline-flex!important;align-items:center;justify-content:center}' +
      '[data-lc=composer].has-content .lc-mic{display:none!important}' +
      '.lc-bot-note{display:none!important}' +
      '.lc-who-note{font-weight:600}' +
      '.lc-who-note.is-admin{color:#2e7d32}' +
      '.lc-who-note.is-bot{color:#888}';
    document.head.appendChild(s);
  }

  function previewOf(m) {
    if (m.deleted) return '🚫 Pesan dihapus';
    const cap = (m.message || '').trim();
    switch (m.type || 'text') {
      case 'image':   return '📷 ' + (cap || 'Foto');
      case 'sticker': return '🏷️ Stiker';
      case 'voice':   return '🎤 Pesan suara' + (m.attachment && m.attachment.duration ? ' (' + mmss(m.attachment.duration) + ')' : '');
      case 'audio':   return '🎵 ' + ((m.attachment && m.attachment.name) || 'Audio');
      case 'file':    return '📎 ' + ((m.attachment && m.attachment.name) || 'File');
      case 'order_confirm': return 'Konfirmasi pesanan';
      case 'order_summary': return 'Ringkasan pesanan';
      case 'bot_faq': return 'Bantuan';
      case 'order_cancel': return 'Pembatalan pesanan';
      case 'product': return '🛍️ ' + ((m.product && m.product.name) || 'Produk');
      default:        return cap.length > 80 ? cap.slice(0, 80) + '…' : cap;
    }
  }

  function productHtml(p) {
    if (!p || p.missing) {
      return '<div class="lc-pcard is-missing">🛍️ Produk ini sudah tidak tersedia</div>';
    }
    return '<a class="lc-pcard" href="' + esc(p.url) + '" target="_blank" rel="noopener">' +
             (p.image ? '<img class="lc-pcard-img" src="' + esc(p.image) + '" alt="" loading="lazy">' : '<span class="lc-pcard-img">🛍️</span>') +
             '<span class="lc-pcard-tx"><b>' + esc(p.name) + '</b>' +
               '<div class="lc-pcard-pr">' + rupiah(p.price) + '</div>' +
               '<div class="lc-pcard-go">Lihat produk ›</div></span>' +
           '</a>';
  }

  let SHOP_URL = '#';

  const FAQ_ITEMS = [
    'Kapan pesanan saya akan dikirim?',
    'Di mana pesanan saya?',
    'Bisakah saya mengubah alamat pesanan?',
    'Bisakah saya membatalkan pesanan saya?',
    'Bagaimana cara mengembalikan produk?'
  ];
  const CHIPS = ['Ubah alamat', 'Minta pengembalian dana', 'Lacak pesanan'];
  // FAQ untuk customer yang belum punya pesanan (harus sama persis dengan GENERAL_FAQ_KEYS di controller)
  const FAQ_NEW = [
    'Bagaimana cara memesan produk?',
    'Berapa lama pengiriman barang?',
    'Ekspedisi apa saja yang tersedia?',
    'Metode pembayaran apa saja?',
    'Apakah produk masih tersedia?',
    'Bagaimana jika barang rusak atau tidak sesuai?'
  ];
  let HAS_ORDER = false;

  function osumHtml(m) {
    const o = m.order;
    if (!o || o.missing) return '';
    return '<div class="lc-osum">' +
      '<div class="lc-osum-top"><b>BSI Cyber Store</b><span class="lc-osum-st">' + esc(o.status || '') + '</span></div>' +
      '<div class="lc-order-row">' +
        '<span class="lc-order-thumb"' + (o.image ? ' style="background-image:url(\'' + esc(o.image) + '\')"' : '') + '></span>' +
        '<div><div class="lc-order-name">' + esc(o.product) + '</div>' +
        '<div class="lc-order-sub">' + o.items + ' item &middot; ' + rupiah(o.total) + '</div></div></div>' +
      '<div class="lc-osum-foot">Silakan pilih produk lain <a href="' + esc(SHOP_URL) + '">di sini</a>.</div></div>';
  }

  function cancelAction(o, mode) {
    if (mode !== 'customer') return '';
    if (o.phase === 'unpaid') return '<button type="button" class="lc-cancel-now">Batalkan pesanan</button>';
    if (o.phase === 'processing') return '<button type="button" class="lc-cancel-btn" data-code="' + esc(o.code) + '">Ajukan pembatalan</button>';
    return '<div class="lc-order-wait">' + ((o.phase === 'cancelled' || o.phase === 'expired') ? 'Pesanan sudah dibatalkan' : 'Pesanan tidak bisa dibatalkan') + '</div>';
  }

  function cancelHtml(m, mode) {
    const o = m.order;
    if (!o || o.missing) return '';
    return '<div class="lc-osum">' +
      '<div class="lc-osum-top"><b>Batalkan pesanan?</b></div>' +
      '<div class="lc-order-row">' +
        '<span class="lc-order-thumb"' + (o.image ? ' style="background-image:url(\'' + esc(o.image) + '\')"' : '') + '></span>' +
        '<div><div class="lc-order-name">' + esc(o.product) + '</div>' +
        '<div class="lc-order-sub">' + o.items + ' item &middot; ' + rupiah(o.total) + '</div></div></div>' +
      '<div class="lc-osum-foot">' + (o.phase === 'unpaid' ? 'Pesanan belum dibayar, jadi pembatalan langsung diproses.' : 'Pembatalan diajukan ke admin dan diproses setelah status pesanan dicek.') + '</div>' +
      cancelAction(o, mode) +
      '</div><div class="lc-bot-note">Dikirim oleh chatbot</div>';
  }

  function faqHtml(m) {
    return '<div class="lc-faq"><div class="lc-faq-title">' + esc(m.message || 'Ada yang bisa kami bantu hari ini?') + '</div>' +
      (HAS_ORDER ? FAQ_ITEMS : FAQ_NEW).map(q => '<button type="button" class="lc-faq-item" data-q="' + esc(q) + '"><span>' + esc(q) + '</span><span>&rsaquo;</span></button>').join('') +
      '</div><div class="lc-bot-note">Dikirim oleh chatbot</div>';
  }

  function orderHtml(m, mode) {
    const o = m.order;
    if (!o || o.missing) {
      return '<div class="lc-order"><div class="lc-order-head">Konfirmasi pesanan</div><div class="lc-order-sub">Data pesanan tidak tersedia</div></div>';
    }
    let foot;
    if (m.address_confirmed) foot = '<div class="lc-order-done">&#10004; Alamat sudah dikonfirmasi</div>';
    else if (mode === 'customer') foot = '<button type="button" class="lc-order-btn">Konfirmasi alamat</button>';
    else foot = '<div class="lc-order-wait">Menunggu konfirmasi alamat</div>';
    return '<div class="lc-order">' +
      '<div class="lc-order-head">Konfirmasi pesanan</div>' +
      '<div class="lc-order-row">' +
        '<span class="lc-order-thumb"' + (o.image ? ' style="background-image:url(\'' + esc(o.image) + '\')"' : '') + '></span>' +
        '<div><div class="lc-order-name">' + esc(o.product) + '</div>' +
        '<div class="lc-order-sub">' + o.items + ' item &middot; ' + rupiah(o.total) + '</div></div></div>' +
      '<div class="lc-order-meta">' +
        '<div><small>ID Pesanan</small><span>' + esc(o.code) + '</span></div>' +
        '<div><small>Telepon</small><span>' + esc(o.phone || '-') + '</span></div>' +
        '<div><small>Alamat</small><span>' + esc(o.address || '-') + '</span></div></div>' +
      foot + '</div>';
  }

  function bodyHtml(m, mode) {
    if (!m.deleted && m.type === 'order_confirm') {
      return (m.message ? '<div class="lc-text-out">' + esc(m.message) + '</div>' : '') + orderHtml(m, mode);
    }
    if (!m.deleted && m.type === 'bot_reply') return '<div class="lc-bot-text">' + esc(m.message) + '</div><div class="lc-bot-note">Dikirim oleh chatbot</div>';
    if (!m.deleted && m.type === 'order_cancel') return cancelHtml(m, mode);
    if (!m.deleted && m.type === 'order_summary') return osumHtml(m);
    if (!m.deleted && m.type === 'bot_faq') return faqHtml(m);
    if (m.deleted) return '<span class="lc-deleted">🚫 Pesan ini telah dihapus</span>';
    const a = m.attachment;
    let h = '';
    if (m.type === 'sticker' && a) return '<img class="lc-sticker" src="' + esc(a.url) + '" alt="stiker">';
    if (m.type === 'product') h += productHtml(m.product);
    if (m.type === 'image' && a) h += '<img class="lc-img" src="' + esc(a.url) + '" alt="' + esc(a.name) + '" loading="lazy">';
    if ((m.type === 'voice' || m.type === 'audio') && a) {
      const isVoice = m.type === 'voice';
      h += '<div class="lc-voice' + (isVoice ? '' : ' is-file') + '" data-src="' + esc(a.url) + '" data-dur="' + (+a.duration || 0) + '">' +
             '<button type="button" class="lc-vplay" aria-label="Putar">▶</button>' +
             '<div class="lc-vmid"><div class="lc-vbar"><i></i></div>' +
               (isVoice ? '' : '<small class="lc-vname">' + esc(a.name) + '</small>') + '</div>' +
             '<span class="lc-vtime">' + mmss(a.duration) + '</span>' +
           '</div>';
    }
    if (m.type === 'file' && a) {
      h += '<a class="lc-doc" href="' + esc(a.url) + '" target="_blank" rel="noopener" download="' + esc(a.name) + '">' +
             '<span class="lc-doc-ic">📄</span>' +
             '<span class="lc-doc-tx"><b>' + esc(a.name) + '</b><small>' + fmtSize(a.size) + '</small></span>' +
             '<span class="lc-doc-dl">⬇</span></a>';
    }
    if (m.message) h += '<div class="lc-text-out">' + esc(m.message) + '</div>';
    return h;
  }

  function lightbox(url) {
    const o = document.createElement('div');
    o.className = 'lc-lightbox';
    o.innerHTML = '<img src="' + esc(url) + '" alt=""><button type="button" aria-label="Tutup">✕</button>';
    o.addEventListener('click', () => o.remove());
    document.body.appendChild(o);
  }

  /* ---------- core ---------- */
  function create(cfg) {
    injectStyle();

    SHOP_URL = (cfg.urls && cfg.urls.shop) || '#';
    const root = cfg.root;
    const mine = cfg.mode === 'admin' ? 'admin' : 'customer';
    const list = root.querySelector('[data-lc=list]');
    const comp = root.querySelector('[data-lc=composer]');

    const msgs = new Map();
    let lastId = 0, lastDay = null, timer = null, polling = false, destroyed = false;
    let replyTo = null, editing = null, file = null, sending = false, stick = true;
    let rec = null;   // sesi rekaman voice note yang sedang berjalan
    let cur = null;   // pemutar audio aktif: { id, audio, el }
    let emoTab = 0;   // tab emoji terakhir yang dibuka
    let prodTimer = null;

    const canRecord = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia && w.MediaRecorder);
    const canProduct = !!(cfg.urls && cfg.urls.products);

    /* composer */
    comp.innerHTML =
      '<div class="lc-banner" hidden></div>' +
      '<div class="lc-preview" hidden></div>' +
      '<div class="lc-panel" hidden></div>' +
      (cfg.mode === 'customer' ? '<div class="lc-chips" style="display:none">' + CHIPS.map(c => '<button type="button" class="lc-chip" data-q="' + esc(c) + '">' + esc(c) + '</button>').join('') + '</div>' : '') +
      '<div class="lc-row">' +
        '<button type="button" class="lc-btn lc-attach" title="Lampirkan">📎</button>' +
        (canProduct ? '<button type="button" class="lc-btn lc-prod" title="Kirim produk">🛍️</button>' : '') +
        '<button type="button" class="lc-btn lc-emoji" title="Emoji">😊</button>' +
        '<textarea class="lc-text" rows="1" maxlength="1000" placeholder="' + (cfg.mode === 'admin' ? 'Tulis balasan...' : 'Ketik pesan...') + '"></textarea>' +
        '<button type="button" class="lc-send">Kirim</button>' +
        '<button type="button" class="lc-mic" title="Rekam pesan suara" aria-label="Rekam pesan suara">' + SVG_MIC + '</button>' +
      '</div>' +
      '<div class="lc-rec" hidden>' +
        '<button type="button" class="lc-rec-del" title="Batalkan" aria-label="Batalkan rekaman">🗑️</button>' +
        '<span class="lc-rec-dot"></span><span class="lc-rec-time">0:00</span>' +
        '<span class="lc-rec-wave">' + '<i></i>'.repeat(26) + '</span>' +
        '<button type="button" class="lc-rec-send" title="Kirim" aria-label="Kirim pesan suara">' + SVG_SEND + '</button>' +
      '</div>' +
      '<input type="file" class="lc-in-doc" hidden style="display:none" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.zip,.rar">' +
      '<input type="file" class="lc-in-img" hidden style="display:none" accept="image/*">' +
      '<input type="file" class="lc-in-cam" hidden style="display:none" accept="image/*" capture="environment">' +
      '<input type="file" class="lc-in-aud" hidden style="display:none" accept="audio/*,.mp3,.wav,.m4a,.aac,.ogg,.opus">';

    const $ = s => comp.querySelector(s);
    const input = $('.lc-text'), panel = $('.lc-panel'), prev = $('.lc-preview'), banner = $('.lc-banner');
    const inDoc = $('.lc-in-doc'), inImg = $('.lc-in-img'), inCam = $('.lc-in-cam'), inAud = $('.lc-in-aud');
    const rowEl = $('.lc-row'), recEl = $('.lc-rec');

    // tombol rekam tetap tampil; pesan error ditampilkan saat ditekan

    /* ---------- helpers ---------- */
    function toast(t) {
      let el = root.querySelector('.lc-toast');
      if (!el) { el = document.createElement('div'); el.className = 'lc-toast'; root.appendChild(el); }
      el.textContent = t; el.classList.add('show');
      clearTimeout(el._t); el._t = setTimeout(() => el.classList.remove('show'), 3500);
    }

    async function api(url, opts) {
      opts = opts || {};
      const res = await fetch(url, Object.assign({ credentials: 'same-origin' }, opts, {
        headers: Object.assign({ 'Accept': 'application/json', 'X-CSRF-TOKEN': cfg.csrf, 'X-Requested-With': 'XMLHttpRequest' }, opts.headers || {})
      }));
      let j = {};
      try { j = await res.json(); } catch (e) { /* bukan JSON */ }
      if (!res.ok) {
        const first = j.errors ? Object.values(j.errors)[0][0] : null;
        throw new Error(first || j.message || ('Terjadi kesalahan (' + res.status + ')'));
      }
      return j;
    }

    const nearBottom = () => list.scrollHeight - list.scrollTop - list.clientHeight < 90;
    const scrollBottom = () => { list.scrollTop = list.scrollHeight; stick = true; };
    list.addEventListener('scroll', () => { stick = nearBottom(); });
    list.addEventListener('load', e => { if (e.target.tagName === 'IMG' && stick) scrollBottom(); }, true);

    function autoGrow() { input.style.height = 'auto'; input.style.height = Math.min(input.scrollHeight, 110) + 'px'; }

    /* tombol kirim <-> mikrofon (seperti WhatsApp: kolom kosong = mikrofon) */
    function syncBtn() {
      const has = input.value.trim() !== '' || !!file || !!editing;
      comp.classList.toggle('has-content', has);
    }
    input.addEventListener('input', () => { autoGrow(); syncBtn(); });

    /* ---------- pemutar audio / voice note ---------- */
    function paintVoice() {
      if (!cur || !cur.el) return;
      const a = cur.audio;
      const dur = (isFinite(a.duration) && a.duration > 0) ? a.duration : (+cur.el.dataset.dur || 0);
      cur.el.classList.toggle('playing', !a.paused);
      cur.el.querySelector('.lc-vplay').textContent = a.paused ? '▶' : '⏸';
      cur.el.querySelector('.lc-vbar i').style.width = (dur ? Math.min(100, a.currentTime / dur * 100) : 0) + '%';
      cur.el.querySelector('.lc-vtime').textContent = mmss((a.paused && a.currentTime === 0) ? dur : a.currentTime);
    }

    function stopPlayer() {
      if (!cur) return;
      cur.audio.pause();
      cur.audio.currentTime = 0;
      paintVoice();
      cur = null;
    }

    function togglePlay(vEl) {
      const id = +vEl.closest('.lc-msg').dataset.id;
      if (cur && cur.id === id) {
        if (cur.audio.paused) cur.audio.play().catch(() => toast('Tidak bisa memutar audio.'));
        else cur.audio.pause();
        return;
      }
      stopPlayer();
      const audio = new Audio(vEl.dataset.src);
      const mineAudio = () => cur && cur.audio === audio;
      cur = { id: id, audio: audio, el: vEl };
      audio.addEventListener('timeupdate', () => { if (mineAudio()) paintVoice(); });
      audio.addEventListener('loadedmetadata', () => { if (mineAudio()) paintVoice(); });
      audio.addEventListener('play', () => { if (mineAudio()) paintVoice(); });
      audio.addEventListener('pause', () => { if (mineAudio()) paintVoice(); });
      audio.addEventListener('ended', () => { if (mineAudio()) { audio.currentTime = 0; paintVoice(); } });
      audio.play().catch(() => { toast('Tidak bisa memutar audio.'); stopPlayer(); });
    }

    function seekVoice(vEl, ev) {
      const id = +vEl.closest('.lc-msg').dataset.id;
      if (!cur || cur.id !== id) { togglePlay(vEl); return; }
      const a = cur.audio;
      const dur = (isFinite(a.duration) && a.duration > 0) ? a.duration : (+vEl.dataset.dur || 0);
      if (!dur) return;
      const bar = vEl.querySelector('.lc-vbar'), r = bar.getBoundingClientRect();
      a.currentTime = Math.max(0, Math.min(1, (ev.clientX - r.left) / r.width)) * dur;
      paintVoice();
    }

    /* ---------- render ---------- */
    // label lawan bicara: admin melihat nama customer, customer melihat nama admin yang membalas
    function peerLabel(m) {
      if (cfg.mode === 'admin') return cfg.peerName || 'Customer';
      return (m && m.admin_name) || cfg.peerName || 'Admin';
    }

    function bubbleEl(m) {
      const own = m.sender === mine;
      const el = document.createElement('div');
      el.className = 'lc-msg ' + (own ? 'own' : 'other') +
        (!m.deleted && m.type === 'sticker' ? ' is-sticker' : '') + (m.deleted ? ' is-deleted' : '');
      el.dataset.id = m.id;

      let quote = '';
      if (m.reply && !m.deleted) {
        quote = '<div class="lc-quote" data-goto="' + m.reply.id + '"><b>' + (m.reply.sender === mine ? 'Anda' : esc(peerLabel(m.reply))) + '</b>' +
                '<span>' + (m.reply.deleted ? '🚫 Pesan dihapus' : esc(m.reply.message)) + '</span></div>';
      }
      const whoNote = (m.sender === 'admin' && !m.deleted)
        ? '<span class="lc-who-note' + (m.is_bot ? ' is-bot' : ' is-admin') + '">' + (m.is_bot ? 'Dikirim oleh chatbot' : 'Admin') + '</span> &middot; '
        : '';
      const meta = whoNote + (m.edited ? '<em>diedit</em> ' : '') + timeStr(m.created_at) + (own && !m.deleted ? ' ' + tick(m.status) : '');

      // nama admin di atas isi bubble (hanya terlihat oleh customer)
      const who = (cfg.mode === 'customer' && !own && !m.deleted && m.type !== 'sticker')
        ? '<b style="display:block;font-size:12px;color:#c4492e;margin-bottom:2px">' + esc(m.admin_name || 'Admin') + '</b>'
        : '';

      el.innerHTML =
        '<div class="lc-line"><div class="lc-bubble">' + who + quote + bodyHtml(m, cfg.mode) + '</div>' +
        '<button type="button" class="lc-more" data-more aria-label="Opsi pesan">⌄</button></div>' +
        '<div class="lc-meta">' + meta + '</div>';

      // bubble di-render ulang (mis. centang berubah) saat audio sedang diputar -> sambungkan lagi
      if (cur && cur.id === m.id) {
        const v = el.querySelector('.lc-voice');
        if (v) { cur.el = v; paintVoice(); }
      }
      return el;
    }

    function addMsg(m, force) {
      if (msgs.has(m.id)) return;
      msgs.set(m.id, m);
      lastId = Math.max(lastId, m.id);

      const dk = dayKey(m.created_at);
      if (dk !== lastDay) {
        lastDay = dk;
        const sep = document.createElement('div');
        sep.className = 'lc-day';
        sep.innerHTML = '<span>' + dayLabel(m.created_at) + '</span>';
        list.appendChild(sep);
      }
      list.appendChild(bubbleEl(m));
      if (force || stick || m.sender === mine) scrollBottom();
    }

    function refresh(m) {
      if (m.deleted && cur && cur.id === m.id) stopPlayer();
      msgs.set(m.id, m);
      const el = list.querySelector('.lc-msg[data-id="' + m.id + '"]');
      if (el) el.replaceWith(bubbleEl(m));
    }

    function removeMsg(id) {
      if (cur && cur.id === id) stopPlayer();
      msgs.delete(id);
      const el = list.querySelector('.lc-msg[data-id="' + id + '"]');
      if (el) el.remove();
    }

    function applyStates(states) {
      Object.keys(states).forEach(k => {
        const id = +k, s = states[k], m = msgs.get(id);
        if (!m) return;
        if (m.status === s.status && m.message === s.message && m.edited === s.edited && m.deleted === s.deleted && !!m.address_confirmed === !!s.address_confirmed) return;
        Object.assign(m, { status: s.status, message: s.message, edited: s.edited, deleted: s.deleted, delivered_at: s.delivered_at, read_at: s.read_at, address_confirmed: s.address_confirmed });
        if (s.deleted) { m.type = 'text'; m.attachment = null; m.product = null; m.reply = null; }
        refresh(m);
      });
    }

    /* ---------- polling ---------- */
    async function poll() {
      if (destroyed || polling) return;
      polling = true;
      try {
        const j = await api(cfg.urls.messages(lastId));
        if (destroyed) return;
        if (j.reset) { stopPlayer(); closeMenu(); msgs.clear(); list.innerHTML = ''; lastDay = null; stick = true; }
        const ho = !!j.has_order;
        if (ho !== HAS_ORDER) {
          HAS_ORDER = ho;
          msgs.forEach(x => { if (x.type === 'bot_faq') refresh(x); });
        }
        (j.messages || []).forEach(m => addMsg(m));
        applyStates(j.states || {});
        const chipsEl = comp.querySelector('.lc-chips');
        if (chipsEl) chipsEl.style.display = j.has_order ? '' : 'none';
        if (cfg.onPoll) cfg.onPoll(j);
      } catch (e) { /* diam, coba lagi di putaran berikutnya */ }
      finally { polling = false; }
    }

    function loop() {
      if (destroyed) return;
      poll().then(() => {
        const iv = typeof cfg.interval === 'function' ? cfg.interval() : (cfg.interval || 2500);
        timer = setTimeout(loop, iv);
      });
    }

    /* ---------- banner (balas / edit) ---------- */
    function setBanner(kind, m) {
      if (kind === 'reply') { replyTo = m; editing = null; }
      if (kind === 'edit')  { editing = m; replyTo = null; }
      banner.hidden = false;
      banner.innerHTML = '<div class="lc-banner-tx"><b>' +
        (kind === 'edit' ? 'Edit pesan' : 'Membalas ' + (m.sender === mine ? 'Anda' : esc(peerLabel(m)))) + '</b>' +
        '<span>' + esc(previewOf(m)) + '</span></div><button type="button" class="lc-banner-x" aria-label="Batal">✕</button>';
      banner.querySelector('.lc-banner-x').onclick = () => clearBanner(true);
      syncBtn();
      input.focus();
    }
    function clearBanner(clearInputIfEdit) {
      if (editing && clearInputIfEdit) { input.value = ''; autoGrow(); }
      replyTo = null; editing = null;
      banner.hidden = true; banner.innerHTML = '';
      syncBtn();
    }

    /* ---------- menu pesan ---------- */
    let ctx = null;
    function closeMenu() { if (ctx) { ctx.remove(); ctx = null; } }
    document.addEventListener('click', closeMenu);
    list.addEventListener('scroll', closeMenu);

    function openMenu(btn, m) {
      closeMenu();
      const own = m.sender === mine, type = m.type || 'text';
      const items = [];
      if (!m.deleted) items.push(['reply', '↩️ Balas']);
      if (!m.deleted && type === 'text' && m.message) items.push(['copy', '📋 Salin']);
      if (!m.deleted && own && type === 'text' && Date.now() - new Date(m.created_at).getTime() <= EDIT_MIN * 60000) items.push(['edit', '✏️ Edit']);
      items.push(['delme', '🗑️ Hapus untuk saya']);
      if (!m.deleted && own) items.push(['delall', '🚫 Hapus untuk semua']);

      ctx = document.createElement('div');
      ctx.className = 'lc-ctx';
      ctx.innerHTML = items.map(i => '<button type="button" data-a="' + i[0] + '">' + i[1] + '</button>').join('');
      document.body.appendChild(ctx);

      const r = btn.getBoundingClientRect(), cw = ctx.offsetWidth, ch = ctx.offsetHeight;
      let left = own ? r.right - cw : r.left;
      left = Math.max(8, Math.min(left, window.innerWidth - cw - 8));
      let top = r.bottom + 4;
      if (top + ch > window.innerHeight - 8) top = Math.max(8, r.top - ch - 4);
      ctx.style.left = left + 'px'; ctx.style.top = top + 'px';

      ctx.addEventListener('click', e => {
        const b = e.target.closest('[data-a]');
        if (!b) return;
        e.stopPropagation();
        closeMenu();
        action(b.dataset.a, m);
      });
    }

    async function action(a, m) {
      try {
        if (a === 'reply') setBanner('reply', m);
        else if (a === 'edit') { setBanner('edit', m); input.value = m.message; autoGrow(); syncBtn(); }
        else if (a === 'copy') { await navigator.clipboard.writeText(m.message); toast('Pesan disalin'); }
        else if (a === 'delme') {
          if (!confirm('Hapus pesan ini dari tampilan Anda?')) return;
          await api(cfg.urls.del(m.id), { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ mode: 'me' }) });
          removeMsg(m.id);
        } else if (a === 'delall') {
          if (!confirm('Hapus pesan ini untuk semua orang?')) return;
          const j = await api(cfg.urls.del(m.id), { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ mode: 'all' }) });
          refresh(j.message);
        }
      } catch (e) { toast(e.message); }
    }

    // klik kanan pada pesan -> menu yang sama dengan panah
    list.addEventListener('contextmenu', e => {
      const row = e.target.closest('.lc-msg');
      if (!row) return;
      const m = msgs.get(+row.dataset.id);
      if (!m) return;
      e.preventDefault();
      e.stopPropagation();
      const x = e.clientX, y = e.clientY;
      openMenu({ getBoundingClientRect: () => ({ left: x, right: x, top: y, bottom: y }) }, m);
    });

    list.addEventListener('click', e => {
      const more = e.target.closest('[data-more]');
      if (more) {
        e.stopPropagation();
        const m = msgs.get(+more.closest('.lc-msg').dataset.id);
        if (m) openMenu(more, m);
        return;
      }
      const g = e.target.closest('[data-goto]');
      if (g) {
        const t = list.querySelector('.lc-msg[data-id="' + g.dataset.goto + '"]');
        if (t) { t.scrollIntoView({ block: 'center', behavior: 'smooth' }); t.classList.add('flash'); setTimeout(() => t.classList.remove('flash'), 1200); }
        return;
      }
      const cn = e.target.closest('.lc-cancel-now');
      if (cn) { cancelOrder(+cn.closest('.lc-msg').dataset.id, cn); return; }
      const cb = e.target.closest('.lc-cancel-btn');
      if (cb) { cb.disabled = true; quick('Saya ingin membatalkan pesanan ' + (cb.dataset.code || '')); return; }
      const fq = e.target.closest('.lc-faq-item');
      if (fq) { quick(fq.dataset.q); return; }
      const ob = e.target.closest('.lc-order-btn');
      if (ob) { confirmAddress(+ob.closest('.lc-msg').dataset.id, ob); return; }
const vp = e.target.closest('.lc-vplay');
      if (vp) { togglePlay(vp.closest('.lc-voice')); return; }
      const vb = e.target.closest('.lc-vbar');
      if (vb) { seekVoice(vb.closest('.lc-voice'), e); return; }
      const img = e.target.closest('.lc-img');
      if (img) lightbox(img.src);
    });

    /* ---------- konfirmasi alamat (kartu pesanan) ---------- */
    async function confirmAddress(id, btn) {
      if (!cfg.urls.confirm) return;
      btn.disabled = true;
      try {
        await api(cfg.urls.confirm(id), { method: 'POST' });
        const m = msgs.get(id);
        if (m) { m.address_confirmed = true; refresh(m); }
        poll(); // ambil balasan otomatis "Terima kasih..." segera
      } catch (e) {
        btn.disabled = false;
        toast(e.message);
      }
    }

/* ---------- batalkan pesanan (belum dibayar) ---------- */
    async function cancelOrder(id, btn) {
      if (!cfg.urls.cancel) return;
      if (!confirm('Batalkan pesanan ini? Pesanan yang sudah dibatalkan tidak bisa dikembalikan.')) return;
      btn.disabled = true;
      try {
        await api(cfg.urls.cancel(id), { method: 'POST' });
        const m = msgs.get(id);
        if (m && m.order) { m.order.phase = 'cancelled'; refresh(m); }
        poll(); // ambil balasan bot "berhasil dibatalkan"
      } catch (e) {
        btn.disabled = false;
        toast(e.message);
      }
    }

    /* ---------- balasan cepat (FAQ & chip) ---------- */
    function quick(text) {
      if (sending || rec || editing || !text) return;
      const fd = new FormData();
      fd.append('message', text);
      post(fd).then(() => poll());
    }

    /* ---------- lampiran ---------- */
    function showPreview() {
      if (!file) { prev.hidden = true; prev.innerHTML = ''; syncBtn(); return; }
      const isImg = file.type.startsWith('image/');
      const ic = file.type.startsWith('audio/') ? '🎧' : '📄';
      prev.hidden = false;
      prev.innerHTML = (isImg ? '<img src="' + URL.createObjectURL(file) + '" alt="">' : '<span class="lc-doc-ic">' + ic + '</span>') +
        '<span class="lc-prev-name">' + esc(file.name) + '<small>' + fmtSize(file.size) + '</small></span>' +
        '<button type="button" class="lc-prev-x" aria-label="Hapus lampiran">✕</button>';
      prev.querySelector('.lc-prev-x').onclick = () => { file = null; showPreview(); };
      syncBtn();
    }

    function pickFile(f) {
      if (!f) return;
      if (editing) { toast('Selesaikan atau batalkan edit dulu.'); return; }
      if (rec) { toast('Selesaikan rekaman suara dulu.'); return; }
      if (f.size > MAX_MB * 1024 * 1024) { toast('Ukuran file maksimal ' + MAX_MB + ' MB.'); return; }
      file = f; showPreview(); closePanel(); input.focus();
    }
    inDoc.onchange = () => { pickFile(inDoc.files[0]); inDoc.value = ''; };
    inImg.onchange = () => { pickFile(inImg.files[0]); inImg.value = ''; };
    inCam.onchange = () => { pickFile(inCam.files[0]); inCam.value = ''; };
    inAud.onchange = () => { pickFile(inAud.files[0]); inAud.value = ''; };

    input.addEventListener('paste', e => {
      const f = Array.from((e.clipboardData && e.clipboardData.files) || []).find(x => x.type.startsWith('image/'));
      if (f) { e.preventDefault(); pickFile(f); }
    });

    ['dragenter', 'dragover'].forEach(ev => root.addEventListener(ev, e => { e.preventDefault(); root.classList.add('lc-drop'); }));
    ['dragleave', 'drop'].forEach(ev => root.addEventListener(ev, e => { e.preventDefault(); root.classList.remove('lc-drop'); }));
    root.addEventListener('drop', e => pickFile(e.dataTransfer && e.dataTransfer.files[0]));

    /* ---------- kamera ---------- */
    async function openCamera() {
      closePanel();
      if (editing) { toast('Selesaikan atau batalkan edit dulu.'); return; }
      if (rec) { toast('Selesaikan rekaman suara dulu.'); return; }

      // HP / tablet, atau browser tanpa getUserMedia -> pakai kamera bawaan lewat input file
      const touch = w.matchMedia && w.matchMedia('(pointer:coarse)').matches;
      if (touch || !(navigator.mediaDevices && navigator.mediaDevices.getUserMedia)) { inCam.click(); return; }

      const o = document.createElement('div');
      o.className = 'lc-cam';
      o.innerHTML =
        '<video autoplay playsinline muted></video>' +
        '<div class="lc-cam-bar">' +
          '<button type="button" class="lc-cam-x" aria-label="Tutup">✕</button>' +
          '<button type="button" class="lc-cam-shot" aria-label="Ambil foto"></button>' +
          '<button type="button" class="lc-cam-flip" aria-label="Balik kamera">🔄</button>' +
        '</div>';
      document.body.appendChild(o);

      const video = o.querySelector('video');
      let stream = null, facing = 'user';

      const stop = () => { if (stream) stream.getTracks().forEach(t => t.stop()); stream = null; };
      const onKey = e => { if (e.key === 'Escape') close(); };
      function close() { stop(); o.remove(); document.removeEventListener('keydown', onKey); }
      document.addEventListener('keydown', onKey);

      async function start() {
        stop();
        try {
          stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: facing }, audio: false });
          video.srcObject = stream;
          video.classList.toggle('mirror', facing === 'user');
        } catch (e) {
          close();
          toast('Kamera tidak bisa diakses. Periksa izin kamera di browser.');
        }
      }

      o.querySelector('.lc-cam-x').onclick = close;
      o.querySelector('.lc-cam-flip').onclick = () => { facing = facing === 'user' ? 'environment' : 'user'; start(); };
      o.querySelector('.lc-cam-shot').onclick = () => {
        if (!video.videoWidth) return;
        const c = document.createElement('canvas');
        c.width = video.videoWidth; c.height = video.videoHeight;
        c.getContext('2d').drawImage(video, 0, 0);
        c.toBlob(b => {
          if (!b) return;
          close();
          pickFile(new File([b], 'foto-' + Date.now() + '.jpg', { type: 'image/jpeg' }));
        }, 'image/jpeg', 0.9);
      };
      start();
    }

    /* ---------- voice note ---------- */
    function pickRecMime() {
      const c = ['audio/webm;codecs=opus', 'audio/ogg;codecs=opus', 'audio/webm', 'audio/mp4', 'audio/ogg'];
      return c.find(t => w.MediaRecorder.isTypeSupported(t)) || '';
    }

    function setRecUI(on) {
      recEl.hidden = !on;
      rowEl.hidden = on;
    }

    async function startRec() {
      if (rec || sending) return;
      if (editing) { toast('Selesaikan atau batalkan edit dulu.'); return; }
      if (!canRecord) { toast(w.isSecureContext === false ? 'Rekam suara butuh HTTPS atau localhost.' : 'Browser ini belum mendukung rekaman suara.'); return; }

      let stream;
      try {
        stream = await navigator.mediaDevices.getUserMedia({ audio: true });
      } catch (e) {
        toast('Izin mikrofon ditolak. Aktifkan mikrofon di pengaturan browser.');
        return;
      }

      const mime = pickRecMime();
      let mr;
      try { mr = new w.MediaRecorder(stream, mime ? { mimeType: mime } : undefined); }
      catch (e) { stream.getTracks().forEach(t => t.stop()); toast('Perekaman suara tidak bisa dimulai.'); return; }

      const r = { mr: mr, stream: stream, chunks: [], start: Date.now(), cancel: false, mime: mr.mimeType || mime || 'audio/webm', timer: null };
      rec = r;
      closePanel();
      stopPlayer();

      mr.ondataavailable = e => { if (e.data && e.data.size) r.chunks.push(e.data); };
      mr.onstop = () => {
        r.stream.getTracks().forEach(t => t.stop());
        clearInterval(r.timer);
        if (rec === r) rec = null;
        setRecUI(false);
        if (r.cancel) return;

        const secs = Math.round((Date.now() - r.start) / 1000);
        if (secs < 1 || !r.chunks.length) { toast('Rekaman terlalu singkat.'); return; }

        const base = r.mime.split(';')[0];
        const blob = new Blob(r.chunks, { type: base });
        if (blob.size > MAX_MB * 1024 * 1024) { toast('Rekaman terlalu besar (maks ' + MAX_MB + ' MB).'); return; }

        const ext = /ogg/.test(base) ? 'ogg' : /mp4/.test(base) ? 'm4a' : 'webm';
        const f = new File([blob], 'pesan-suara-' + Date.now() + '.' + ext, { type: base });
        const fd = new FormData();
        fd.append('file', f);
        fd.append('voice', '1');
        fd.append('duration', String(secs));
        post(fd);
      };

      recEl.querySelector('.lc-rec-time').textContent = '0:00';
      setRecUI(true);
      mr.start(250);
      r.timer = setInterval(() => {
        const s = (Date.now() - r.start) / 1000;
        recEl.querySelector('.lc-rec-time').textContent = mmss(s);
        if (s >= MAX_REC_SEC) { toast('Batas rekaman ' + (MAX_REC_SEC / 60) + ' menit tercapai.'); finishRec(true); }
      }, 250);
    }

    function finishRec(send) {
      if (!rec) return;
      rec.cancel = !send;
      if (rec.mr.state !== 'inactive') rec.mr.stop();
    }

    $('.lc-mic').onclick = startRec;
    $('.lc-rec-del').onclick = () => finishRec(false);
    $('.lc-rec-send').onclick = () => finishRec(true);

    /* ---------- panel: menu lampiran / emoji / produk ---------- */
    function closePanel() { clearTimeout(prodTimer); panel.hidden = true; panel.innerHTML = ''; panel.dataset.k = ''; }

    $('.lc-attach').onclick = e => {
      e.stopPropagation();
      if (!panel.hidden && panel.dataset.k === 'attach') return closePanel();
      panel.hidden = false; panel.dataset.k = 'attach';
      panel.innerHTML = '<div class="lc-menu">' +
        '<button type="button" data-a="doc"><span class="lc-ic lc-ic-doc">📄</span>Dokumen</button>' +
        '<button type="button" data-a="img"><span class="lc-ic lc-ic-img">🖼️</span>Foto</button>' +
        '<button type="button" data-a="cam"><span class="lc-ic lc-ic-cam">📷</span>Kamera</button>' +
        '<button type="button" data-a="aud"><span class="lc-ic lc-ic-aud">🎧</span>Audio</button></div>';
      panel.querySelector('[data-a=doc]').onclick = () => inDoc.click();
      panel.querySelector('[data-a=img]').onclick = () => inImg.click();
      panel.querySelector('[data-a=cam]').onclick = openCamera;
      panel.querySelector('[data-a=aud]').onclick = () => inAud.click();
    };

    // emoji dengan tab kategori
    function renderEmoji() {
      panel.innerHTML =
        '<div class="lc-x-tabs">' + EMOJI_CATS.map((c, i) =>
          '<button type="button" data-t="' + i + '" title="' + c.name + '"' + (i === emoTab ? ' class="on"' : '') + '>' + c.icon + '</button>').join('') + '</div>' +
        '<div class="lc-x-scroll"><div class="lc-grid lc-emo">' +
          EMOJI_CATS[emoTab].list.map(x => '<button type="button" data-e="' + x + '">' + x + '</button>').join('') + '</div></div>';
    }

    $('.lc-emoji').onclick = e => {
      e.stopPropagation();
      if (!panel.hidden && panel.dataset.k === 'emo') return closePanel();
      panel.hidden = false; panel.dataset.k = 'emo';
      renderEmoji();
    };

    panel.addEventListener('click', e => {
      if (panel.dataset.k !== 'emo') return;
      const tab = e.target.closest('[data-t]');
      if (tab) { emoTab = +tab.dataset.t; renderEmoji(); return; }
      const b = e.target.closest('[data-e]');
      if (!b) return;
      const em = b.dataset.e;
      const p = input.selectionStart == null ? input.value.length : input.selectionStart;
      input.value = input.value.slice(0, p) + em + input.value.slice(p);
      input.focus(); input.selectionStart = input.selectionEnd = p + em.length; autoGrow(); syncBtn();
    });

    // pemilih produk (seperti TikTok Shop: foto, nama, harga)
    function renderProducts(items) {
      const box = panel.querySelector('.lc-x-plist');
      if (!box) return;
      if (!items.length) { box.innerHTML = '<div class="lc-x-empty">Produk tidak ditemukan.</div>'; return; }
      box.innerHTML = items.map(p =>
        '<div class="lc-x-item" data-pid="' + p.id + '">' +
          (p.image ? '<img src="' + esc(p.image) + '" alt="" loading="lazy">' : '<span class="ph">🛍️</span>') +
          '<div class="tx"><b>' + esc(p.name) + '</b><span>' + rupiah(p.price) + '</span></div>' +
          '<button type="button">Kirim</button></div>').join('');
    }

    async function loadProducts(q) {
      const box = panel.querySelector('.lc-x-plist');
      if (box) box.innerHTML = '<div class="lc-x-empty">Memuat produk...</div>';
      try {
        const j = await api(cfg.urls.products(q || ''));
        if (panel.dataset.k === 'prod') renderProducts(j.products || []);
      } catch (e) {
        if (box) box.innerHTML = '<div class="lc-x-empty">Gagal memuat produk.</div>';
      }
    }

    function openProducts() {
      panel.hidden = false; panel.dataset.k = 'prod';
      panel.innerHTML = '<div class="lc-x-prod"><input type="text" class="lc-x-q" placeholder="Cari produk..."><div class="lc-x-plist"></div></div>';
      const q = panel.querySelector('.lc-x-q');
      q.addEventListener('input', () => { clearTimeout(prodTimer); prodTimer = setTimeout(() => loadProducts(q.value.trim()), 300); });
      loadProducts('');
      q.focus();
    }

    if (canProduct) {
      $('.lc-prod').onclick = e => {
        e.stopPropagation();
        if (editing) { toast('Selesaikan atau batalkan edit dulu.'); return; }
        if (!panel.hidden && panel.dataset.k === 'prod') return closePanel();
        openProducts();
      };
      panel.addEventListener('click', e => {
        if (panel.dataset.k !== 'prod') return;
        const it = e.target.closest('[data-pid]');
        if (!it) return;
        const fd = new FormData();
        fd.append('product_id', it.dataset.pid);
        if (input.value.trim()) fd.append('message', input.value.trim());
        post(fd, () => { input.value = ''; autoGrow(); syncBtn(); });
      });
    }

    document.addEventListener('click', e => { if (!comp.contains(e.target)) closePanel(); });

    /* ---------- kirim ---------- */
    async function post(fd, after) {
      if (sending) return;
      if (replyTo) fd.append('reply_to_id', replyTo.id);

      sending = true; root.classList.add('lc-sending');
      try {
        const j = await api(cfg.urls.send(), { method: 'POST', body: fd }); // jangan set Content-Type (biar boundary otomatis)
        addMsg(j.message, true);
        if (after) after();
        clearBanner(false); closePanel();
        if (cfg.onSent) cfg.onSent(j.message);
      } catch (e) { toast(e.message); }
      finally { sending = false; root.classList.remove('lc-sending'); }
    }

    async function send() {
      if (sending || rec) return;
      const msg = input.value.trim();

      // mode edit
      if (editing) {
        if (!msg) return;
        sending = true; root.classList.add('lc-sending');
        try {
          const j = await api(cfg.urls.edit(editing.id), { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ message: msg }) });
          refresh(j.message);
          clearBanner(false); input.value = ''; autoGrow(); syncBtn();
        } catch (e) { toast(e.message); }
        finally { sending = false; root.classList.remove('lc-sending'); }
        return;
      }

      if (!msg && !file) return;

      const fd = new FormData();
      if (msg) fd.append('message', msg);
      if (file) fd.append('file', file);

      await post(fd, () => { input.value = ''; autoGrow(); file = null; showPreview(); });
    }

    $('.lc-send').onclick = () => send();
    input.addEventListener('keydown', e => {
      if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); }
      if (e.key === 'Escape') { clearBanner(true); closePanel(); }
    });
    recEl.addEventListener('keydown', e => { if (e.key === 'Escape') finishRec(false); });

    syncBtn();

    comp.addEventListener('click', e => {
      const ch = e.target.closest('.lc-chip');
      if (ch) quick(ch.dataset.q);
    });

    /* ---------- tombol ke pesan terbaru (customer) ---------- */
    if (cfg.mode === 'customer') {
      if (getComputedStyle(root).position === 'static') root.style.position = 'relative';
      const down = document.createElement('button');
      down.type = 'button';
      down.id = 'lcDownBtn';
      down.title = 'Ke pesan terbaru';
      down.setAttribute('aria-label', 'Ke pesan terbaru');
      down.innerHTML = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>';
      down.style.cssText = 'position:absolute;right:16px;bottom:90px;width:38px;height:38px;border:0;border-radius:50%;background:#fff;color:#555;box-shadow:0 2px 8px rgba(0,0,0,.28);cursor:pointer;display:none;align-items:center;justify-content:center;z-index:5;padding:0';
      root.appendChild(down);

      const updDown = () => {
        const far = list.scrollHeight - list.scrollTop - list.clientHeight > 200;
        const rr = root.getBoundingClientRect(), cr = comp.getBoundingClientRect();
        down.style.bottom = Math.max(8, rr.bottom - cr.top + 10) + 'px';
        down.style.display = (far && root.offsetParent !== null) ? 'flex' : 'none';
      };
      list.addEventListener('scroll', updDown);
      const dt = setInterval(() => { if (destroyed) clearInterval(dt); else updDown(); }, 500);
      down.onclick = e => {
        e.stopPropagation();
        list.scrollTo({ top: list.scrollHeight, behavior: 'smooth' });
      };
    }

    /* ---------- hapus chat (customer) ---------- */
    if (cfg.mode === 'customer') {
      if (getComputedStyle(root).position === 'static') root.style.position = 'relative';
      const clr = document.createElement('button');
      clr.type = 'button';
      clr.id = 'lcClearBtn';
      clr.title = 'Hapus chat';
      clr.setAttribute('aria-label', 'Hapus chat');
      clr.textContent = '🗑️';
      clr.style.cssText = 'position:absolute;top:8px;right:14px;width:30px;height:30px;border:0;border-radius:50%;background:rgba(255,255,255,.92);box-shadow:0 1px 4px rgba(0,0,0,.2);cursor:pointer;font-size:14px;line-height:1;padding:0;z-index:5';
      root.appendChild(clr);

      clr.onclick = async e => {
        e.stopPropagation();
        if (!confirm('Hapus semua chat ini dari tampilan Anda? Tindakan ini tidak bisa dibatalkan.')) return;
        clr.disabled = true;
        try {
          const url = cfg.urls.send().replace(/\/send(\?|$)/, '/clear$1');
          await api(url, { method: 'POST' });
          closeMenu(); stopPlayer(); closePanel(); clearBanner(false);
          msgs.clear();
          list.innerHTML = '';
          lastDay = null;
          stick = true;
          toast('Chat dihapus');
          poll(); // ambil sapaan & FAQ baru
        } catch (err) {
          toast(err.message);
        } finally {
          clr.disabled = false;
        }
      };
    }

    /* ---------- public api ---------- */
    return {
      start() { if (!timer && !destroyed) loop(); },
      kick()  { clearTimeout(timer); timer = null; polling = false; loop(); },
      stop()  { clearTimeout(timer); timer = null; },
      destroy() { destroyed = true; clearTimeout(timer); closeMenu(); stopPlayer(); if (rec) finishRec(false); },
      scrollBottom, focus() { input.focus(); }
    };
  }

  w.LiveChat = { create };
})(window);
