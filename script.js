// public/script.js

// --- DOMContentLoaded: Main entry point for scripts ---
document.addEventListener('DOMContentLoaded', () => {

    // Helper to format the current date, replacing the PHP function
    const dateSpan = document.getElementById('server-date');
    if (dateSpan) {
        dateSpan.textContent = new Date().toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    // --- Module Initializations ---
    initializePreloader();
    initializeNetworkCanvas();
    initializeMenu();
    initializeTheme();
    initializeParallax();
    initializeRippleEffect();
    initializeAdminModal();
    initializeFocusTrap();
    initializeMemberTabs();
    initializeImageErrorFixer();
    initializeMathGame(); // This will inject the game UI and logic
    initializeLogoRain();
});

// ===========================================================================
// MODULES (Each wrapped in its own function for clarity)
// ===========================================================================

function initializePreloader() {
    const pre = document.getElementById('preloader');
    if (!pre) return;
    const hide = () => {
        pre.style.opacity = '0';
        pre.style.pointerEvents = 'none';
        setTimeout(() => pre.remove(), 550);
    };
    window.addEventListener('load', () => setTimeout(hide, 420));
    setTimeout(hide, 4000); // Failsafe
}

function initializeNetworkCanvas() {
    const canvas = document.getElementById('network');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let dpr = window.devicePixelRatio || 1;
    let W, H, pts = [];

    const init = () => {
        dpr = window.devicePixelRatio || 1;
        W = canvas.width = window.innerWidth * dpr;
        H = canvas.height = window.innerHeight * dpr;
        canvas.style.width = `${window.innerWidth}px`;
        canvas.style.height = `${window.innerHeight}px`;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        
        pts = [];
        const count = Math.max(14, Math.floor((window.innerWidth * window.innerHeight) / 110000));
        for (let i = 0; i < count; i++) {
            pts.push({
                x: Math.random() * window.innerWidth,
                y: Math.random() * window.innerHeight,
                vx: (Math.random() - 0.5) * 0.6,
                vy: (Math.random() - 0.5) * 0.6
            });
        }
    };

    const frame = () => {
        ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);
        for (const p of pts) {
            p.x += p.vx;
            p.y += p.vy;
            if (p.x < 0 || p.x > window.innerWidth) p.vx *= -1;
            if (p.y < 0 || p.y > window.innerHeight) p.vy *= -1;
        }

        ctx.lineWidth = 1;
        for (let i = 0; i < pts.length; i++) {
            for (let j = i + 1; j < pts.length; j++) {
                const a = pts[i], b = pts[j];
                const d = Math.hypot(a.x - b.x, a.y - b.y);
                if (d < 140) {
                    ctx.strokeStyle = `rgba(14,165,164,${(0.14 * (1 - d / 140)).toFixed(3)})`;
                    ctx.beginPath();
                    ctx.moveTo(a.x, a.y);
                    ctx.lineTo(b.x, b.y);
                    ctx.stroke();
                }
            }
        }

        ctx.fillStyle = 'rgba(255,255,255,0.06)';
        for (const p of pts) {
            ctx.beginPath();
            ctx.arc(p.x, p.y, 1.6, 0, Math.PI * 2);
            ctx.fill();
        }
        requestAnimationFrame(frame);
    };

    window.addEventListener('resize', init);
    init();
    frame();
}

function initializeMenu() {
    const menuData = [
        { id: 'ringkasan', title: 'Ringkasan', subtitle: 'Pengurus & jadwal piket', icon: '🏠' },
        { id: 'jadwal', title: 'Jadwal', subtitle: 'Jadwal pelajaran', icon: '📅' },
        { id: 'pengumuman', title: 'Pengumuman', subtitle: 'Info terbaru', icon: '📢' },
        { id: 'kegiatan', title: 'Kegiatan', subtitle: 'Agenda mendatang', icon: '🎯' },
        { id: 'pr', title: 'Tugas', subtitle: 'Tugas Rumah / PR', icon: '📚' },
        { id: 'developer', title: 'Developer', subtitle: 'Thanks To', icon: '🥳' },
        { id: 'mathgame', title: 'Game', subtitle: 'Belajar sambil bermain!', icon: '😏' }
    ];

    const menuPanel = document.getElementById('menuPanel');
    const menuList = document.getElementById('menuList');
    const equalsBtn = document.getElementById('equalsBtn');
    const searchInput = document.getElementById('menuSearch');
    const pages = Array.from(document.querySelectorAll('.page'));

    const buildList = () => {
        menuList.innerHTML = '';
        menuData.forEach((m, i) => {
            const div = document.createElement('div');
            div.className = 'menu-item ripple';
            div.tabIndex = 0;
            div.setAttribute('role', 'menuitem');
            div.dataset.target = m.id;
            div.style.animationDelay = `${60 + i * 60}ms`;
            div.innerHTML = `<div class="ic">${m.icon}</div><div style="flex:1"><div style="font-weight:800">${m.title}</div><div class="hint">${m.subtitle}</div></div>`;
            menuList.appendChild(div);
        });
        menuList.querySelector('.menu-item')?.classList.add('active');
    };
    buildList();

    const togglePanel = (forceClose = false) => {
        const isOpen = menuPanel.classList.contains('open');
        if (forceClose || isOpen) {
            menuPanel.classList.remove('open');
            equalsBtn.setAttribute('aria-expanded', 'false');
            menuPanel.setAttribute('aria-hidden', 'true');
        } else {
            menuPanel.classList.add('open');
            equalsBtn.setAttribute('aria-expanded', 'true');
            menuPanel.setAttribute('aria-hidden', 'false');
            setTimeout(() => searchInput.focus(), 180);
        }
    };
    
    window.showPage = (id) => {
        const active = pages.find(p => !p.classList.contains('hidden'));
        const target = document.getElementById(id);
        if (!target || (active && active.id === id)) return;
    
        if (active) {
            active.style.transition = 'opacity 300ms ease, transform 300ms cubic-bezier(.2,.9,.2,1)';
            active.style.opacity = '0';
            active.style.transform = 'translateY(-8px) scale(.995)';
            setTimeout(() => {
                active.classList.add('hidden');
                active.style.opacity = '';
                active.style.transform = '';
                
                target.classList.remove('hidden');
                target.style.opacity = '0';
                target.style.transform = 'translateY(12px) scale(.995)';
                requestAnimationFrame(() => {
                    target.style.transition = 'opacity 420ms ease, transform 420ms cubic-bezier(.2,.9,.2,1)';
                    target.style.opacity = '1';
                    target.style.transform = 'translateY(0) scale(1)';
                });
            }, 320);
        } else { // No active page, just show the target
            target.classList.remove('hidden');
        }
    };
    
    equalsBtn.addEventListener('click', e => {
        e.stopPropagation();
        togglePanel();
    });
    
    document.addEventListener('click', e => {
        if (!menuPanel.contains(e.target) && e.target !== equalsBtn) {
            togglePanel(true);
        }
    });

    searchInput.addEventListener('input', () => {
        const q = searchInput.value.trim().toLowerCase();
        Array.from(menuList.children).forEach(it => {
            const title = it.querySelector('div[style*="font-weight:800"]').textContent.toLowerCase();
            it.style.display = (q && !title.includes(q)) ? 'none' : 'flex';
        });
    });

    menuList.addEventListener('click', e => {
        const item = e.target.closest('.menu-item');
        if (!item) return;
        Array.from(menuList.children).forEach(c => c.classList.remove('active'));
        item.classList.add('active');
        showPage(item.dataset.target);
        togglePanel(true);
    });

    showPage('ringkasan');
}

function initializeTheme() {
    const KEY = '7c_theme_v6';
    const btn = document.getElementById('themeToggle');
    const root = document.documentElement;
    const applyTheme = (theme) => {
        root.setAttribute('data-theme', theme);
        btn.setAttribute('aria-pressed', theme === 'light' ? 'true' : 'false');
        localStorage.setItem(KEY, theme);
    };
    const saved = localStorage.getItem(KEY) || 'dark';
    applyTheme(saved);
    btn.addEventListener('click', () => {
        const next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        applyTheme(next);
    });
}

function initializeParallax() {
    const blobs = [document.querySelector('.blob.b1'), document.querySelector('.blob.b2'), document.querySelector('.blob.b3')];
    let lx = 0, ly = 0;
    const move = (e) => {
        const x = (e.clientX / window.innerWidth) - 0.5;
        const y = (e.clientY / window.innerHeight) - 0.5;
        lx += (x - lx) * 0.08;
        ly += (y - ly) * 0.08;
        if (blobs[0]) blobs[0].style.transform = `translate(${lx * 24}px, ${ly * 18}px)`;
        if (blobs[1]) blobs[1].style.transform = `translate(${lx * -18}px, ${ly * -12}px)`;
        if (blobs[2]) blobs[2].style.transform = `translate(${lx * 12}px, ${ly * -24}px)`;
    };
    window.addEventListener('mousemove', move);
}

function initializeRippleEffect() {
    document.querySelectorAll('.ripple').forEach(el => {
        el.addEventListener('pointerdown', e => {
            const rect = el.getBoundingClientRect();
            const r = document.createElement('span');
            const size = Math.max(rect.width, rect.height) * 1.6;
            r.style.cssText = `position:absolute; width:${size}px; height:${size}px; left:${e.clientX - rect.left - size / 2}px; top:${e.clientY - rect.top - size / 2}px; border-radius:50%; background:rgba(255,255,255,0.06); transform:scale(0); opacity:1; transition:transform 600ms ease, opacity 600ms ease; pointer-events:none;`;
            el.appendChild(r);
            requestAnimationFrame(() => {
                r.style.transform = 'scale(1)';
                r.style.opacity = '0';
            });
            setTimeout(() => r.remove(), 700);
        });
    });
}

function initializeAdminModal() {
    const adminBtn = document.getElementById('adminBtn');
    const backdrop = document.getElementById('adminBackdrop');
    const closeBtn = document.getElementById('closeAdmin');
    const frame = document.getElementById('adminFrame');
    const openNewTab = document.getElementById('openNewTab');

    const LOCAL_PATH = '/tinyfilemanager.php'; // Example path

    const toggleModal = (show = false) => {
        backdrop.style.display = show ? 'flex' : 'none';
        backdrop.setAttribute('aria-hidden', !show);
        if (show) frame.src = LOCAL_PATH;
        else frame.src = 'about:blank';
    };

    adminBtn.addEventListener('click', () => toggleModal(true));
    closeBtn.addEventListener('click', () => toggleModal(false));
    backdrop.addEventListener('click', e => {
        if (e.target === backdrop) toggleModal(false);
    });
    openNewTab.addEventListener('click', () => window.open(LOCAL_PATH, '_blank'));
}

function initializeFocusTrap() {
    // Basic focus trap for modals or panels could be added here if needed
}

function initializeMemberTabs() {
    const container = document.getElementById('memberTabs');
    if (!container) return;
    const buttons = Array.from(container.querySelectorAll('.tab-btn'));
    const panels = Array.from(container.querySelectorAll('.member-panel'));

    const activate = (memberKey) => {
        const currentPanel = panels.find(p => !p.hasAttribute('hidden'));
        const targetPanel = panels.find(p => p.dataset.member === memberKey);
        if (!targetPanel) return;

        buttons.forEach(b => b.classList.toggle('active', b.dataset.member === memberKey));

        if (currentPanel && currentPanel !== targetPanel) {
            currentPanel.classList.add('leave');
            currentPanel.addEventListener('animationend', () => {
                currentPanel.setAttribute('hidden', '');
                currentPanel.classList.remove('leave');
                targetPanel.removeAttribute('hidden');
                targetPanel.classList.add('enter');
            }, { once: true });
        } else if (!currentPanel) {
            targetPanel.removeAttribute('hidden');
            targetPanel.classList.add('enter');
        }
        targetPanel.addEventListener('animationend', () => targetPanel.classList.remove('enter'), { once: true });
    };
    
    buttons.forEach(btn => btn.addEventListener('click', () => activate(btn.dataset.member)));
    activate('athar'); // Activate the first tab initially
}


function initializeImageErrorFixer() {
    document.querySelectorAll('.tab-btn img, .member-photo img').forEach(img => {
        img.addEventListener('error', function() {
            this.src = `https://via.placeholder.com/150?text=${this.alt || 'Image'}`;
        });
    });
}

function initializeLogoRain() {
    const LOGO_URL = 'https://files.cloudkuimages.guru/images/9Owut0x4.jpg';
    let container;

    const ensureContainer = () => {
        if (container) return container;
        container = document.createElement('div');
        container.className = 'rain-container';
        document.body.appendChild(container);
        return container;
    };
    
    const createDrop = () => {
        const cont = ensureContainer();
        const el = document.createElement('div');
        el.className = 'logo-drop shape-round';
        const size = Math.random() * 40 + 20;
        el.style.width = `${size}px`;
        el.style.height = `${size}px`;
        el.style.left = `${Math.random() * window.innerWidth}px`;
        el.innerHTML = `<div class="badge"><img src="${LOGO_URL}" alt="7C Logo"></div>`;
        cont.appendChild(el);

        const duration = Math.random() * 2000 + 4000;
        const drift = (Math.random() - 0.5) * 140;
        const rotate = (Math.random() - 0.5) * 360;

        el.animate([
            { transform: 'translateY(-100px) rotate(0deg)', opacity: 1 },
            { transform: `translateY(${window.innerHeight + 100}px) translateX(${drift}px) rotate(${rotate}deg)`, opacity: 0 }
        ], {
            duration: duration,
            easing: 'linear'
        }).onfinish = () => el.remove();
    };

    document.querySelector('.logo-square')?.addEventListener('click', () => {
        for (let i = 0; i < 15; i++) {
            setTimeout(createDrop, i * 100);
        }
    });
}

function initializeMathGame() {
    const art = document.getElementById('mathgame');
    if (!art) return;

    // Inject game HTML
    art.innerHTML = `
      <h3>Belajar sambil bermain :)</h3>
      <div class="game-wrap">
        <div class="game-area">
          <div class="game-card">
            <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
              <div class="small">Skor: <strong id="g_score">0</strong></div>
              <div class="small">Streak: <strong id="g_streak">0</strong></div>
              <div class="small">Nyawa: <strong id="g_lives">3</strong></div>
            </div>
            <div class="question" id="g_question">Tekan "Mulai" untuk memulai permainan</div>
            <div class="small" id="g_hint">Pilih tingkat kesulitan lalu tekan mulai.</div>
            <div style="margin-top:8px" class="input-row">
              <input type="text" id="g_answer" placeholder="Jawaban (contoh: 3/4 atau 0.75)" />
              <button id="g_submit" class="btn">Kirim</button>
            </div>
            <div style="display:flex;gap:8px;margin-top:10px;align-items:center; flex-wrap: wrap;">
              <div style="display:flex;gap:6px;align-items:center">Level:
                <button class="btn small g_diff" data-diff="1">Mudah</button>
                <button class="btn small g_diff" data-diff="2">Sedang</button>
                <button class="btn small g_diff" data-diff="3">Sulit</button>
              </div>
              <button id="g_start" class="btn">Mulai</button>
              <button id="g_skip" class="btn">Lewati</button>
            </div>
            <div style="margin-top:10px; max-height: 100px; overflow-y: auto;" id="g_log" class="small"></div>
          </div>
          <div class="game-card">
            <div style="font-weight:800; display: flex; justify-content: space-between; align-items: center;">Papan Skor
              <button id="g_refresh" class="btn" title="Refresh papan skor" style="padding: 4px 8px;">⟳</button>
            </div>
            <div id="g_highscores" style="margin-top:8px"></div>
          </div>
        </div>
      </div>
    `;

    // Game Logic
    let difficulty = 1;
    let currentNonce = null;

    const log = (msg) => {
        const el = document.getElementById('g_log');
        el.innerHTML = `<div>[${new Date().toLocaleTimeString()}] ${msg}</div>` + el.innerHTML;
    };
    
    const escapeHtml = (s) => String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

    const loadHighscores = async () => {
        const box = document.getElementById('g_highscores');
        box.innerHTML = '<div class="small">Loading...</div>';
        try {
            const res = await fetch('/api/highscores');
            const data = await res.json();
            if (data.ok) {
                box.innerHTML = data.list.map(it =>
                    `<div style="display:flex;justify-content:space-between;padding:4px 0;">
                        <div style="font-weight:700">${escapeHtml(it.name)}</div>
                        <div>${it.score}</div>
                    </div>`
                ).join('');
            } else {
                box.innerHTML = '<div class="small">Failed to load scores.</div>';
            }
        } catch (e) {
            box.innerHTML = '<div class="small">Network error.</div>';
        }
    };

    const nextProblem = async () => {
        const res = await fetch(`/api/problem?difficulty=${difficulty}`);
        const data = await res.json();
        if (data.ok) {
            currentNonce = data.nonce;
            document.getElementById('g_question').innerHTML = data.question_html;
            document.getElementById('g_hint').textContent = data.hint;
            document.getElementById('g_answer').value = '';
            document.getElementById('g_answer').focus();
        } else {
            alert('Failed to get a new problem.');
        }
    };

    const handleSubmit = (data) => {
        if (!data.ok) {
            alert(data.error || 'Submission error.');
            return;
        }
        log(data.message);
        document.getElementById('g_score').textContent = data.score;
        document.getElementById('g_streak').textContent = data.streak;
        document.getElementById('g_lives').textContent = data.lives;

        if (data.game_over) {
            const name = prompt(`Game Over! Your score: ${data.score}\nEnter your name for the highscore board:`, 'Player');
            if (name) {
                fetch('/api/save_name', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name: name.trim() })
                }).then(() => loadHighscores());
            }
            document.getElementById('g_question').textContent = 'Game Over! Press Start to play again.';
        } else {
            setTimeout(nextProblem, 700);
        }
    };
    
    const submitAnswer = async () => {
        const answer = document.getElementById('g_answer').value.trim();
        if (!currentNonce) return;
        const res = await fetch('/api/submit', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nonce: currentNonce, answer })
        });
        handleSubmit(await res.json());
    };

    document.getElementById('g_start').addEventListener('click', async () => {
        await fetch('/api/reset');
        document.getElementById('g_log').innerHTML = '';
        log('New game started!');
        nextProblem();
    });

    document.getElementById('g_submit').addEventListener('click', submitAnswer);
    document.getElementById('g_answer').addEventListener('keydown', e => e.key === 'Enter' && submitAnswer());
    document.getElementById('g_skip').addEventListener('click', async () => {
        if (!currentNonce || !confirm('Skip this question? (-1 life)')) return;
         const res = await fetch('/api/submit', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nonce: currentNonce, answer: '__skip__' })
        });
        handleSubmit(await res.json());
    });
    
    document.querySelectorAll('.g_diff').forEach(btn => {
        btn.addEventListener('click', () => {
            difficulty = btn.dataset.diff;
            document.querySelectorAll('.g_diff').forEach(b => b.style.opacity = '0.6');
            btn.style.opacity = '1';
            log(`Difficulty set to ${btn.textContent}.`);
        });
    });
    
    document.getElementById('g_refresh').addEventListener('click', loadHighscores);

    loadHighscores();
    document.querySelector('.g_diff[data-diff="1"]').style.opacity = '1';
}
