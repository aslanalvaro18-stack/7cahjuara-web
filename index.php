<?php
// 7C-portal-v6-with-admin-login.php
// Versi v6 — ditambah tombol Admin (login TinyFileManager)
// Simpan sebagai .php jika ingin tanggal server (digunakan di bawah)
?>
<!doctype html>
<html lang="id">
<head>
  <link rel="shortcut icon" type="image/x-icon" href="https://files.cloudkuimages.guru/images/9Owut0x4.jpg" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>7 Cah Juara</title>
  <meta name="description" content="Website Kelas 7 Cah Juara" />
  <style>
/* ===========================================================================
   7C Portal v6 — Stylesheet + Admin modal
   =========================================================================== */
:root{
  --bg-1: #04121a; --bg-2: #072028; --card-bg: rgba(255,255,255,0.03); --glass: rgba(255,255,255,0.04);
  --accent-1: #06b6d4; --accent-2: #0ea5a4; --accent-3: #7ee3d6;
  --text: #e8f6fb; --muted: #98b6c4; --shadow-1: 0 12px 34px rgba(2,6,23,0.5);
  --radius: 14px; --max-w: 1200px; --ui-size: 15px; --menu-z: 99999;
}
:root[data-theme="light"]{ --bg-1:#f6fbff; --bg-2:#eef8ff; --card-bg:rgba(10,20,30,0.04); --glass:rgba(255,255,255,0.6); --text:#072035; --muted:#475569; --shadow-1:0 12px 34px rgba(2,6,23,0.06); }
*{box-sizing:border-box}html,body{height:100%;margin:0;padding:0;font-family:Inter,system-ui,-apple-system,'Segoe UI',Roboto,Arial;background:linear-gradient(180deg,var(--bg-1),var(--bg-2));color:var(--text);-webkit-font-smoothing:antialiased;overflow-x:hidden}
img{max-width:100%;height:auto;display:block}
.hidden{display:none}.center{display:flex;align-items:center;justify-content:center}.container{width:100%;max-width:var(--max-w);margin:0 auto;padding:20px}
#preloader{position:fixed;inset:0;z-index:100000;background:linear-gradient(180deg,var(--bg-1),var(--bg-2));display:flex;align-items:center;justify-content:center}
#preloader .box{width:110px;height:110px;border-radius:18px;background:linear-gradient(180deg,var(--accent-2),var(--accent-1));box-shadow:var(--shadow-1);display:flex;align-items:center;justify-content:center}
#preloader .ring{width:56px;height:56px;border-radius:50%;border:3px solid rgba(2,10,12,0.12);animation:rotateScale 1400ms linear infinite}
#preloader .ring::after{content:'';width:12px;height:12px;border-radius:50%;background:#02121a;display:block;margin:auto;transform:translateY(-6px)}
@keyframes rotateScale{0%{transform:rotate(0) scale(0.86)}50%{transform:rotate(180deg) scale(1)}100%{transform:rotate(360deg) scale(0.86)}}
.bg-wrap{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden}.blob{position:absolute;border-radius:50%;filter:blur(64px);opacity:0.86;mix-blend-mode:screen;will-change:transform}
.blob.b1{width:900px;height:900px;left:-320px;top:-260px;background:radial-gradient(circle at 25% 30%, rgba(6,182,212,0.94), rgba(6,182,212,0.12) 35%, transparent 60%);animation:blobMove1 26s ease-in-out infinite}
.blob.b2{width:680px;height:680px;right:-200px;top:40px;background:radial-gradient(circle at 60% 40%, rgba(14,165,164,0.92), rgba(14,165,164,0.12) 35%, transparent 60%);animation:blobMove2 32s ease-in-out infinite}
.blob.b3{width:520px;height:520px;left:6%;bottom:-260px;background:radial-gradient(circle at 50% 50%, rgba(15,120,140,0.88), rgba(15,120,140,0.08) 40%, transparent 70%);animation:blobMove3 20s ease-in-out infinite}
@keyframes blobMove1{0%{transform:translate(0,0) rotate(0deg)}33%{transform:translate(28px,22px) rotate(3deg)}66%{transform:translate(-18px,28px) rotate(-2deg)}100%{transform:none}}
@keyframes blobMove2{0%{transform:none}50%{transform:translate(-28px,-18px) rotate(-6deg)}100%{transform:none}}
@keyframes blobMove3{0%{transform:none}50%{transform:translate(12px,-36px)}100%{transform:none}}
.ring{position:absolute;border-radius:50%;border:2px solid rgba(255,255,255,0.02);box-shadow:0 6px 40px rgba(6,22,34,0.12)}
.ring.r1{width:960px;height:960px;right:-420px;bottom:-420px;animation:spin 56s linear infinite}
.ring.r2{width:640px;height:640px;left:-300px;bottom:-320px;animation:spinRev 72s linear infinite}@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}@keyframes spinRev{from{transform:rotate(360deg)}to{transform:rotate(0deg)}}
canvas#network{position:absolute;inset:0;width:100%;height:100%;z-index:0;opacity:0.32;pointer-events:none}
.app{position:relative;z-index:10;min-height:100vh;display:flex;align-items:flex-start;justify-content:center;padding:28px 16px}.panel{width:100%;max-width:var(--max-w)}
.header{display:flex;align-items:center;justify-content:space-between;padding:12px;border-radius:12px;background:var(--card-bg);box-shadow:var(--shadow-1);backdrop-filter:blur(6px);position:relative;margin-bottom:18px;z-index:10}
.left-group{display:flex;align-items:center;gap:12px}.equals{width:56px;height:56px;border-radius:12px;border:0;background:linear-gradient(180deg,var(--accent-2),var(--accent-1));display:inline-grid;place-items:center;color:#02121a;cursor:pointer;box-shadow:0 8px 24px rgba(2,8,10,0.12)}
.equals .eq{width:26px;height:16px;position:relative}.equals .eq::before,.equals .eq::after{content:'';position:absolute;left:0;right:0;height:3px;background:rgba(2,10,12,0.95);border-radius:2px}.equals .eq::before{top:0}.equals .eq::after{bottom:0}
.logo-square{width:96px;height:96px;border-radius:18px;background:linear-gradient(180deg,rgba(255,255,255,0.02),transparent);display:flex;align-items:center;justify-content:center;margin:auto;box-shadow:0 8px 28px rgba(2,6,23,0.35);z-index:12}
.logo-square img{width:86%;height:86%;object-fit:cover;border-radius:10px}
.controls{display:flex;align-items:center;gap:10px}
.theme-btn{padding:8px 10px;border-radius:10px;border:0;background:transparent;color:var(--muted);cursor:pointer;font-weight:700}
/* Admin button style */
.admin-btn{padding:8px 12px;border-radius:10px;border:0;background:linear-gradient(90deg,var(--accent-2),var(--accent-1));color:#02121a;font-weight:800;cursor:pointer}
/* modal for admin iframe */
.admin-modal-backdrop{position:fixed;inset:0;background:rgba(2,6,10,0.55);display:none;z-index:100001;align-items:center;justify-content:center}
.admin-modal{width:92%;max-width:1100px;height:80vh;background:var(--card-bg);border-radius:12px;padding:10px;box-shadow:0 28px 80px rgba(2,6,23,0.7);backdrop-filter:blur(6px);display:flex;flex-direction:column}
.admin-modal header{display:flex;align-items:center;justify-content:space-between;padding:6px 8px}
.admin-modal .frame-wrap{flex:1;border-radius:8px;overflow:hidden;border:1px solid rgba(255,255,255,0.04);background:#02121a}
.admin-modal iframe{width:100%;height:100%;border:0}
.admin-modal .notice{font-size:13px;color:var(--muted);padding:8px}
.admin-modal .actions{display:flex;gap:8px}
.admin-modal .btn{padding:8px 10px;border-radius:8px;border:0;background:transparent;color:var(--text);cursor:pointer}
.menu-panel{position:fixed;left:12px;top:72px;min-width:360px;max-width:calc(100% - 48px);background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:12px;padding:12px;box-shadow:0 22px 60px rgba(2,6,23,0.6);backdrop-filter:blur(8px);transform-origin:top left;opacity:0;transform:translateY(-8px) scale(.98);pointer-events:none;transition:opacity 220ms ease, transform 220ms cubic-bezier(.2,.9,.2,1);z-index: var(--menu-z);}
.menu-panel.open{opacity:1;transform:translateY(0) scale(1);pointer-events:auto}
.menu-search{display:flex;align-items:center;gap:8px;margin-bottom:8px}
.menu-search input{flex:1;padding:8px 12px;border-radius:10px;border:1px solid rgba(255,255,255,0.04);background:transparent;color:var(--text)}
.menu-list{display:flex;flex-direction:column;gap:8px;max-height:60vh;overflow:auto;padding-right:6px}
.menu-item{display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;cursor:pointer;border:1px solid rgba(255,255,255,0.02);transition:transform 220ms cubic-bezier(.2,.9,.2,1), box-shadow 220ms;background:linear-gradient(180deg, rgba(255,255,255,0.01), transparent)}
.menu-item .ic{width:44px;height:44px;border-radius:8px;background:linear-gradient(180deg, rgba(255,255,255,0.02), transparent);display:inline-grid;place-items:center;font-weight:800}.menu-item:hover{transform:translateX(6px);box-shadow:0 8px 28px rgba(2,6,23,0.28)}.menu-item.active{background:linear-gradient(90deg,var(--accent-2),var(--accent-1));color:#02121a;font-weight:800}
.menu-item[data-order]{opacity:0;transform:translateX(-8px);animation:menuIn 420ms forwards}@keyframes menuIn{to{opacity:1;transform:none}}
.menu-item:focus{outline:3px solid rgba(6,182,212,0.14);outline-offset:3px}
.main-grid{display:grid;grid-template-columns:1fr;gap:18px}@media(min-width:980px){.main-grid{grid-template-columns:320px 1fr}}
.card{background:var(--card-bg);border-radius:12px;padding:14px;box-shadow:var(--shadow-1);backdrop-filter:blur(6px)}
.scroll{max-height:64vh;overflow:auto;padding-right:8px}
.roles{display:grid;gap:8px}.role{display:flex;justify-content:space-between;align-items:center;padding:10px;border-radius:10px;background:linear-gradient(90deg, rgba(255,255,255,0.01), transparent);font-weight:700}
.piket{display:grid;grid-template-columns:1fr;gap:10px;margin-top:10px}@media(min-width:700px){.piket{grid-template-columns:repeat(2,1fr)}}
.page{opacity:1;transform:none;transition:opacity 420ms ease, transform 420ms cubic-bezier(.2,.9,.2,1)}.page.hidden{display:none;opacity:0}
.card.float{animation:floatCard 6s ease-in-out infinite}@keyframes floatCard{0%{transform:translateY(0)}50%{transform:translateY(6px)}100%{transform:translateY(0)}}
.ripple{position:relative;overflow:hidden}.ripple:after{content:'';position:absolute;border-radius:50%;transform:scale(0);opacity:0;transition:transform 600ms ease, opacity 600ms ease}.ripple:active:after{transform:scale(8);opacity:0}
.hint{color:var(--muted);font-size:13px}
@media(max-width:420px){.menu-panel{left:8px;right:8px;top:60px}.menu-item{padding:10px}}
  </style>
</head>
<body>

  <!-- PRELOADER -->
  <div id="preloader" role="status" aria-live="polite">
    <div class="box">
      <div class="ring"></div>
    </div>
  </div>

  <!-- BACKGROUND LAYERS (visuals) -->
  <div class="bg-wrap" aria-hidden="true">
    <div class="blob b1"></div>
    <div class="blob b2"></div>
    <div class="blob b3"></div>
    <div class="ring r1"></div>
    <div class="ring r2"></div>
    <canvas id="network"></canvas>
  </div>

  <!-- APP -->
  <div class="app" id="app">
    <div class="panel container">

      <!-- HEADER -->
      <header class="header" role="banner" aria-label="Header portal">
        <div class="left-group">
          <button id="equalsBtn" class="equals ripple" aria-expanded="false" aria-controls="menuPanel" aria-label="Buka navigasi">
            <span class="eq" aria-hidden="true"></span>
          </button>
          <div style="display:flex;flex-direction:column">
            <div style="font-weight:900">7 Cah Juara</div>
            <div class="hint">Website kelas 7 Cah Juara — tekan tombol "=" untuk membuka menu</div>
          </div>
        </div>

        <div class="logo-square" aria-hidden="true">
          <img src="https://files.cloudkuimages.guru/images/9Owut0x4.jpg" alt="Logo 7C" onerror="this.style.display='none'"/>
        </div>

        <div class="controls">
          <button id="themeToggle" class="theme-btn" aria-pressed="false" title="Toggle theme">🌓 Tema</button>
          <!-- ADMIN LOGIN BUTTON -->
          <button id="adminBtn" class="admin-btn ripple" aria-haspopup="dialog" aria-controls="adminModal" title="Login Admin">🔒 Admin</button>
        </div>
      </header>

      <!-- MENU PANEL (fixed & top) -->
      <nav id="menuPanel" class="menu-panel" role="menu" aria-hidden="true">
        <div class="menu-search">
          <input id="menuSearch" type="search" placeholder="Cari menu..." aria-label="Cari menu" />
          <div class="hint">Menu Search</div>
        </div>
        <div id="menuList" class="menu-list" role="menu"></div>
      </nav>

      <!-- MAIN GRID -->
      <main class="main-grid" role="main">

        <!-- SIDEBAR -->
        <aside class="card float" aria-label="Informasi singkat">
        <div class="hint">Tanggal server: <?php echo date('d F Y'); ?></div>
        <br>
          <h4 style="margin:0 0 8px 0">Info Singkat</h4>
          <p class="hint" style="margin:0 0 12px 0">Menu: pilih slide (Ringkasan, Jadwal, Pengumuman, Kegiatan). Tema tersimpan di perangkatmu.</p>
          <div style="margin-top:12px">
            <h5 style="margin:0 0 8px 0">Kontak</h5>
            <p style="margin:0;color:var(--muted)">Ketua: Athar</p>
            <p style="margin:0;color:var(--muted)">Wakil: Bilvan</p>
            <br>
            <h5 style="margin:0 0 8px 0">Follow on</h5>
            <input type="image" src="https://upload.wikimedia.org/wikipedia/commons/9/95/Instagram_logo_2022.svg" style="width: 10%;max-width= 10px" onclick="location.href='https://www.instagram.com/7che_pradnyasiwi';"/>
            <input type="image" src="https://upload.wikimedia.org/wikipedia/commons/8/88/TikTok_Icon.png" style="width: 10%;max-width= 10px" onclick="location.href='https://www.tiktok.com/@7che_pradnyasiwi';"/>
            <input type="image" src="https://upload.wikimedia.org/wikipedia/commons/1/19/WhatsApp_logo-color-vertical.svg" style="width: 10%;max-width= 10px" onclick="location.href='https://whatsapp.com/channel/0029Vb6FIBA3QxRudWNFvP0q';"/>
            <br>
            <br>
            <p style="margin:0;color:var(--muted)">&copy;7CAHJUARA</p>
          </div>
        </aside>

        <!-- CONTENT -->
        <section>
          <article id="ringkasan" class="card page" aria-labelledby="ringkasanTitle">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:8px">
              <div>
                <h2 id="ringkasanTitle" style="margin:0;font-size:1.05rem">Ringkasan Kelas</h2>
                <div class="hint">Pengurus & Jadwal Piket</div>
              </div>
            </div>

            <h3 style="margin:6px 0">⭐️ Pengurus kelas 7 Cah Juara</h3>
            <div class="roles" role="list">
              <div class="role" role="listitem"><span>Ketua kelas</span><strong>Athar</strong></div>
              <div class="role" role="listitem"><span>Wakil ketua</span><strong>Bilvan</strong></div>
              <div class="role" role="listitem"><span>Sekretaris 1</span><strong>Wira Damar</strong></div>
              <div class="role" role="listitem"><span>Sekretaris 2</span><strong>Alea</strong></div>
              <div class="role" role="listitem"><span>Bendahara 1</span><strong>Lentera</strong></div>
              <div class="role" role="listitem"><span>Bendahara 2</span><strong>Gina</strong></div>
            </div>

            <h3 style="margin-top:14px">Jadwal Piket 🧹</h3>
            <div class="piket">
              <div class="card"><strong>Senin</strong><div style="color:var(--muted);margin-top:6px">Athar, Laiq, Wira, Nadin, Alista, Gina, Zahira</div></div>
              <div class="card"><strong>Selasa</strong><div style="color:var(--muted);margin-top:6px">Alfi, Bilvan, Abi, Alea, Naya, Cesta</div></div>
              <div class="card"><strong>Rabu</strong><div style="color:var(--muted);margin-top:6px">Farel, Hamza, Affan, Rizky, Syifa, Mayang, Jyo</div></div>
              <div class="card"><strong>Kamis</strong><div style="color:var(--muted);margin-top:6px">Damar, Varo, Saguna, Ciesta, Inca, Kikan</div></div>
              <div class="card"><strong>Jumat</strong><div style="color:var(--muted);margin-top:6px">Nafis, Fatih, Faiz, Narra, Dila, Tera</div></div>
            </div>
          </article>

          <article id="jadwal" class="card page hidden" style="margin-top:18px">
            <h3>Jadwal Pelajaran</h3>
            <div class="scroll" style="margin-top:8px">
              <mg src="https://files.cloudkuimages.guru/images/C42RlMnp.jpg" alt="Jadwal pelajaran" onerror="this.style.display='Tidak Ada Jadwal'">
              <h3>Tidak Ada Jadwal</h3>
            </div>
          </article>

          <article id="pengumuman" class="card page hidden" style="margin-top:18px">
            <h3>Pengumuman</h3>
            <ul>
              <li>Jangan lupa mengerjakan tes literasi!</li>
            </ul>
          </article>

          <article id="kegiatan" class="card page hidden" style="margin-top:18px">
            <h3>Kegiatan Mendatang</h3>
            <ol>
              <li>Belum ada informasi.</li>
            </ol>
          </article>
          
          <article id="pr" class="card page hidden" style="margin-top:18px">
            <h3>TUGAS 7 CAH JUARA</h3>
            <ul>
              <li>Tugas PJOK, dikumpulkan saat pelajaran PJOK | Membuat gambar lapangan basket beserta ukuran, posisi pemain, dan peraturan.</li>
            </ul>
          </article>
          
                    <article id="developer" class="card page hidden" style="margin-top:18px">
            <h3>Developer Tercinta</h3>
            <ul>
            <li>Kak Paro, absen 6 sebagai owner utama. 🥳</li>
            </ul>
          </article>

        </section>

      </main>

    </div>
  </div>

  <!-- ADMIN MODAL (iframe to tinyfilemanager) -->
  <div id="adminBackdrop" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="admin-modal" id="adminModal">
      <header>
        <div style="font-weight:800">Admin — File Manager</div>
        <div style="display:flex;align-items:center;gap:8px">
          <button id="closeAdmin" class="btn" aria-label="Tutup admin">✖</button>
        </div>
      </header>
      <div class="frame-wrap">
        <iframe id="adminFrame" title="TinyFileManager" src="about:blank"></iframe>
      </div>
      <div style="padding-top:8px;display:flex;justify-content:flex-end;gap:8px">
        <button id="openNewTab" class="btn">Buka di Tab Baru</button>
      </div>
    </div>
  </div>

  <!-- SCRIPTS: interactions, animations, theme, canvas network -->
  <script>
const $ = sel => document.querySelector(sel);
const $$ = sel => Array.from(document.querySelectorAll(sel));
const now = () => new Date().getTime();

// Preloader
(function preloader(){
  const pre = document.getElementById('preloader');
  function hide(){ if(pre){ pre.style.opacity='0'; pre.style.pointerEvents='none'; setTimeout(()=>pre.remove(),550); } }
  window.addEventListener('load', ()=>{ setTimeout(hide, 420); });
  setTimeout(hide, 4000);
})();

// network canvas
(function networkCanvas(){
  const canvas = document.getElementById('network'); if(!canvas) return; const ctx = canvas.getContext('2d'); let dpr = window.devicePixelRatio || 1; let W = 0, H = 0; let pts = [];
  function init(){ dpr = window.devicePixelRatio || 1; W = canvas.width = innerWidth * dpr; H = canvas.height = innerHeight * dpr; canvas.style.width = innerWidth + 'px'; canvas.style.height = innerHeight + 'px'; ctx.setTransform(dpr,0,0,dpr,0,0); pts = []; const count = Math.max(14, Math.floor((innerWidth * innerHeight) / 110000)); for(let i=0;i<count;i++){ pts.push({x:Math.random()*innerWidth, y:Math.random()*innerHeight, vx:(Math.random()-0.5)*0.6, vy:(Math.random()-0.5)*0.6}); } }
  function frame(){ ctx.clearRect(0,0,innerWidth,innerHeight); for(let i=0;i<pts.length;i++){ const a = pts[i]; a.x += a.vx; a.y += a.vy; if(a.x < 0 || a.x > innerWidth) a.vx *= -1; if(a.y < 0 || a.y > innerHeight) a.vy *= -1; }
    for(let i=0;i<pts.length;i++){ for(let j=i+1;j<pts.length;j++){ const a=pts[i], b=pts[j]; const dx=a.x-b.x, dy=a.y-b.y; const d=Math.hypot(dx,dy); if(d<140){ ctx.strokeStyle = 'rgba(14,165,164,' + (0.14*(1 - d/140)).toFixed(3) + ')'; ctx.lineWidth = 1; ctx.beginPath(); ctx.moveTo(a.x,a.y); ctx.lineTo(b.x,b.y); ctx.stroke(); } } }
    for(const p of pts){ ctx.fillStyle = 'rgba(255,255,255,0.06)'; ctx.beginPath(); ctx.arc(p.x,p.y,1.6,0,Math.PI*2); ctx.fill(); }
    requestAnimationFrame(frame);
  }
  window.addEventListener('resize', ()=>{ init(); }); init(); frame();
})();

// MENU MODULE (unchanged, omitted comments for brevity)
(function menuModule(){
  const menuData = [
    {id:'ringkasan', title:'Ringkasan', subtitle:'Pengurus & jadwal piket', icon:'🏠'},
    {id:'jadwal', title:'Jadwal', subtitle:'Jadwal pelajaran', icon:'📅'},
    {id:'pengumuman', title:'Pengumuman', subtitle:'Info terbaru', icon:'📢'},
    {id:'kegiatan', title:'Kegiatan', subtitle:'Agenda mendatang', icon:'🎯'},
    {id:'pr', title:'Tugas', subtitle:'Tugas Rumah / PR', icon:'📚'},
    {id:'developer', title:'Developer', subtitle:'Thanks To', icon:'🥳'}
  ];

  const menuPanel = $('#menuPanel');
  const menuList = $('#menuList');
  const equalsBtn = $('#equalsBtn');
  const searchInput = $('#menuSearch');
  const pages = $$('.page');

  function buildList(){ menuList.innerHTML = ''; menuData.forEach((m,i)=>{ const div=document.createElement('div'); div.className='menu-item ripple'; div.tabIndex=0; div.setAttribute('role','menuitem'); div.dataset.target=m.id; div.dataset.order=i; div.style.animationDelay=(60+i*60)+'ms'; div.innerHTML = `<div class="ic">${m.icon}</div><div style="flex:1"><div style="font-weight:800">${m.title}</div><div class="meta" style="color:var(--muted);font-size:13px">${m.subtitle}</div></div>`; menuList.appendChild(div); }); const first = menuList.querySelector('.menu-item'); if(first) first.classList.add('active'); }
  buildList();

  function openPanel(){ menuPanel.classList.add('open'); equalsBtn.setAttribute('aria-expanded','true'); menuPanel.setAttribute('aria-hidden','false'); setTimeout(()=>searchInput.focus(),180); }
  function closePanel(){ menuPanel.classList.remove('open'); equalsBtn.setAttribute('aria-expanded','false'); menuPanel.setAttribute('aria-hidden','true'); }
  function togglePanel(){ menuPanel.classList.contains('open') ? closePanel() : openPanel(); }

  equalsBtn.addEventListener('click', e=>{ e.stopPropagation(); togglePanel(); equalsBtn.animate([{transform:'scale(1)'},{transform:'scale(.96) rotate(-6deg)'},{transform:'scale(1)'}],{duration:220}); });
  document.addEventListener('click', e=>{ if(!menuPanel.contains(e.target) && e.target !== equalsBtn) closePanel(); });
  document.addEventListener('keydown', e=>{ if(e.key==='Escape') closePanel(); if((e.ctrlKey||e.metaKey) && e.key.toLowerCase()==='k'){ e.preventDefault(); openPanel(); searchInput.focus(); } });
  searchInput.addEventListener('input', ()=>{ const q=(searchInput.value||'').trim().toLowerCase(); const items = Array.from(menuList.children); items.forEach(it=>{ const title = it.querySelector('div:nth-child(2) div').textContent.toLowerCase(); it.style.display = (q && !title.includes(q)) ? 'none' : 'flex'; }); });
  menuList.addEventListener('click', e=>{ const item = e.target.closest('.menu-item'); if(!item) return; const tgt = item.dataset.target; Array.from(menuList.children).forEach(c=>c.classList.remove('active')); item.classList.add('active'); showPage(tgt); closePanel(); });
  menuList.addEventListener('keydown', e=>{ const visible = Array.from(menuList.querySelectorAll('.menu-item')).filter(x=>x.style.display!=='none'); if(visible.length===0) return; const idx = visible.indexOf(document.activeElement); if(e.key==='ArrowDown'){ e.preventDefault(); visible[(idx+1)%visible.length].focus(); } if(e.key==='ArrowUp'){ e.preventDefault(); visible[(idx-1+visible.length)%visible.length].focus(); } if(e.key==='Enter' || e.key===' '){ e.preventDefault(); document.activeElement.click(); } });

  window.showPage = function(id){ const active = pages.find(p=>!p.classList.contains('hidden')) || pages[0]; const target = document.getElementById(id); if(!target || active===target) return; active.style.transition='opacity 300ms ease, transform 300ms cubic-bezier(.2,.9,.2,1)'; active.style.opacity='0'; active.style.transform='translateY(-8px) scale(.995)'; setTimeout(()=>{ active.classList.add('hidden'); active.style.opacity=''; active.style.transform=''; target.classList.remove('hidden'); target.style.opacity='0'; target.style.transform='translateY(12px) scale(.995)'; requestAnimationFrame(()=>{ target.style.transition='opacity 420ms ease, transform 420ms cubic-bezier(.2,.9,.2,1)'; target.style.opacity='1'; target.style.transform='translateY(0) scale(1)'; }); },320); };
  window.addEventListener('load', ()=>{ setTimeout(()=>{ showPage('ringkasan'); }, 100); });
})();

/* THEME MODULE */
(function themeModule(){
  const KEY='7c_theme_v6'; const btn=document.getElementById('themeToggle'); const root=document.documentElement;
  function applyTheme(theme){ if(theme==='light') root.setAttribute('data-theme','light'); else root.removeAttribute('data-theme'); btn.setAttribute('aria-pressed', theme==='light' ? 'true' : 'false'); try{ localStorage.setItem(KEY, theme); }catch(e){} }
  (function restore(){ try{ const saved = localStorage.getItem(KEY); if(saved){ applyTheme(saved); return; } }catch(e){} if(window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) applyTheme('light'); else applyTheme('dark'); })();
  btn.addEventListener('click', ()=>{ const current = root.getAttribute('data-theme') === 'light' ? 'light' : 'dark'; const next = current === 'light' ? 'dark' : 'light'; applyTheme(next); btn.animate([{transform:'scale(1)'},{transform:'scale(.96)'},{transform:'scale(1)'}],{duration:180}); });
})();

/* PARALLAX (unchanged) */
(function parallaxBlobs(){ const b1=document.querySelector('.blob.b1'); const b2=document.querySelector('.blob.b2'); const b3=document.querySelector('.blob.b3'); let lx=0,ly=0; function move(e){ const x=(e.clientX/window.innerWidth)-0.5; const y=(e.clientY/window.innerHeight)-0.5; lx += (x-lx)*0.08; ly += (y-ly)*0.08; if(b1) b1.style.transform = `translate(${lx*24}px, ${ly*18}px)`; if(b2) b2.style.transform = `translate(${lx*-18}px, ${ly*-12}px)`; if(b3) b3.style.transform = `translate(${lx*12}px, ${ly*-24}px)`; } window.addEventListener('mousemove', move); window.addEventListener('touchmove', (e)=>{ if(e.touches && e.touches[0]) move(e.touches[0]); }, {passive:true}); })();

/* RIPPLE */
(function ripple(){ function create(e){ const el=e.currentTarget; const rect=el.getBoundingClientRect(); const r=document.createElement('span'); const size=Math.max(rect.width,rect.height)*1.6; r.style.position='absolute'; r.style.width=r.style.height=size+'px'; r.style.left=(e.clientX-rect.left-size/2)+'px'; r.style.top=(e.clientY-rect.top-size/2)+'px'; r.style.borderRadius='50%'; r.style.background='rgba(255,255,255,0.06)'; r.style.transform='scale(0)'; r.style.opacity='1'; r.style.transition='transform 600ms ease, opacity 600ms ease'; r.style.pointerEvents='none'; el.appendChild(r); requestAnimationFrame(()=>{ r.style.transform='scale(1)'; r.style.opacity='0'; }); setTimeout(()=> r.remove(),700); }
  document.querySelectorAll('.ripple').forEach(el=>el.addEventListener('pointerdown', create)); })();

/* ADMIN BUTTON + MODAL LOGIC */
(function adminModule(){
  const adminBtn = document.getElementById('adminBtn');
  const backdrop = document.getElementById('adminBackdrop');
  const closeBtn = document.getElementById('closeAdmin');
  const frame = document.getElementById('adminFrame');
  const openNewTab = document.getElementById('openNewTab');
  const openRawBtn = document.getElementById('openRawBtn');

  // The path where tinyfilemanager should live on your webserver/termux
  const LOCAL_PATH = '/tinyfilemanager.php';
  // Raw GitHub URL for download (single-file manager). If you want latest version, go to repository.
  const GITHUB_RAW = 'https://raw.githubusercontent.com/prasathmani/tinyfilemanager/master/tinyfilemanager.php';

  function openModal(){ backdrop.style.display='flex'; backdrop.setAttribute('aria-hidden','false'); // try to load local path
    frame.src = LOCAL_PATH; }
  function closeModal(){ backdrop.style.display='none'; backdrop.setAttribute('aria-hidden','true'); frame.src = 'about:blank'; }

  adminBtn.addEventListener('click', ()=>{ openModal(); });
  closeBtn.addEventListener('click', ()=>{ closeModal(); });
  backdrop.addEventListener('click', (e)=>{ if(e.target===backdrop) closeModal(); });
  

  // Open file manager in new tab (same local path) — user may host in Termux
  openNewTab.addEventListener('click', ()=>{ window.open(LOCAL_PATH,'_blank'); });

  // Open raw GitHub page to download the php file (fallback if file not on server)
  openRawBtn.addEventListener('click', ()=>{ window.open(GITHUB_RAW,'_blank'); });
})();

/* focus trap for menu */
(function focusTrap(){ const menu = document.getElementById('menuPanel'); document.addEventListener('keydown', (e)=>{ if(e.key !== 'Tab') return; if(!menu.classList.contains('open')) return; const focusable = Array.from(menu.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])')).filter(a => a.offsetParent !== null); if(!focusable.length) return; const first = focusable[0], last = focusable[focusable.length - 1]; if(e.shiftKey && document.activeElement === first){ e.preventDefault(); last.focus(); } else if(!e.shiftKey && document.activeElement === last){ e.preventDefault(); first.focus(); } }); })();

window.goTo = function(id){ const list = document.getElementById('menuList'); if(!list) return; const item = Array.from(list.children).find(it => it.dataset.target === id); if(item) item.click(); };
  </script>
</body>
</html>