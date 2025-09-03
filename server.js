// server.js
const express = require('express');
const session = require('express-session');
const fs = require('fs').promises;
const path = require('path');

const app = express();
const PORT = 3000;
const HIGHSCORE_FILE = path.join(__dirname, 'highscores.json');

// --- Middleware Setup ---
app.use(express.static('public')); // Serve static files from the 'public' directory
app.use(express.json()); // Parse JSON request bodies

// Session management setup
app.use(session({
    secret: '7cah-juara-secret-key', // Change this to a random secret
    resave: false,
    saveUninitialized: true,
    cookie: { secure: false } // Set to true if using HTTPS
}));

// ===========================================================================
// API Routes (Replaces the PHP 'action' logic)
// ===========================================================================

app.get('/api/reset', (req, res) => {
    req.session.destroy(() => {
        res.json({ ok: true });
    });
});

app.get('/api/problem', (req, res) => {
    const difficulty = parseInt(req.query.difficulty, 10) || 1;
    const problem = generate_problem(difficulty);
    
    // Store answer in session with a nonce
    const nonce = Date.now().toString(36) + Math.random().toString(36).substring(2);
    if (!req.session.problems) req.session.problems = {};
    
    // Set a 90-second expiry for the problem
    req.session.problems[nonce] = { answer: problem.answer, expires: Date.now() + 90000 };
    
    // Clean up expired problems
    for (const k in req.session.problems) {
        if (req.session.problems[k].expires < Date.now()) {
            delete req.session.problems[k];
        }
    }
    
    res.json({
        ok: true,
        nonce: nonce,
        question_html: problem.question_html,
        hint: problem.hint,
        time_limit: problem.time_limit
    });
});

app.post('/api/submit', (req, res) => {
    const { nonce, answer = '' } = req.body;

    if (!nonce || !req.session.problems || !req.session.problems[nonce]) {
        return res.json({ ok: false, error: 'Problem expired or is invalid. Please request a new one.' });
    }

    const correct = req.session.problems[nonce].answer;
    delete req.session.problems[nonce];

    // Initialize session variables if they don't exist
    req.session.score = req.session.score || 0;
    req.session.streak = req.session.streak || 0;
    req.session.lives = req.session.lives === undefined ? 3 : req.session.lives;

    const is_correct = compare_answers(answer.toString(), correct.toString());
    let delta = 0;
    let msg = '';

    if (is_correct) {
        req.session.streak += 1;
        const bonus = Math.min(req.session.streak, 5);
        delta = 10 * bonus;
        req.session.score += delta;
        msg = `Correct! +${delta} points. Streak: ${req.session.streak}`;
    } else {
        req.session.streak = 0;
        req.session.lives -= 1;
        msg = `Incorrect. The correct answer was: ${format_answer(correct)}. Lives remaining: ${req.session.lives}`;
    }

    let game_over = false;
    let requires_name = false;

    if (req.session.lives <= 0) {
        game_over = true;
        req.session.last_score = req.session.score;
        req.session.pending_score = true;
        requires_name = true;
    }

    res.json({
        ok: true,
        is_correct,
        score: req.session.score,
        streak: req.session.streak,
        lives: req.session.lives,
        delta,
        message: msg,
        game_over,
        requires_name
    });
});

app.post('/api/save_name', async (req, res) => {
    const { name = '' } = req.body;
    const trimmedName = name.trim();
    if (!trimmedName) {
        return res.status(400).json({ ok: false, error: 'Name cannot be empty' });
    }
    if (!req.session.pending_score || !req.session.hasOwnProperty('last_score')) {
        return res.status(400).json({ ok: false, error: 'No score is pending to be saved' });
    }

    await save_highscore(trimmedName, parseInt(req.session.last_score, 10));
    delete req.session.pending_score;
    delete req.session.last_score;
    res.json({ ok: true });
});

app.get('/api/highscores', async (req, res) => {
    const list = await load_highscores();
    res.json({ ok: true, list });
});


// ===========================================================================
// Helper Functions (Game Logic - Ported from PHP)
// ===========================================================================

function generate_problem(difficulty) {
    const topics = {
        1: ['add', 'sub', 'mul', 'div', 'frac'],
        2: ['add', 'sub', 'mul', 'div', 'frac', 'percent'],
        3: ['mul', 'div', 'frac', 'percent', 'equation']
    };
    const pool = topics[difficulty] || topics[1];
    const topic = pool[Math.floor(Math.random() * pool.length)];

    switch (topic) {
        case 'add': {
            const a = rand_for_diff(10, difficulty);
            const b = rand_for_diff(10, difficulty);
            return { question_html: `${a} + ${b} = ?`, answer: (a + b).toString(), hint: 'Try adding the tens and ones.', time_limit: 30 };
        }
        case 'sub': {
            const a = rand_for_diff(20, difficulty);
            const b = Math.floor(Math.random() * (a * 0.8)) + 1;
            return { question_html: `${a} - ${b} = ?`, answer: (a - b).toString(), hint: 'Subtract the smaller number from the larger one.', time_limit: 30 };
        }
        case 'mul': {
            const a = rand_for_diff(5, difficulty);
            const b = rand_for_diff(5, difficulty);
            return { question_html: `${a} × ${b} = ?`, answer: (a * b).toString(), hint: 'Multiply as usual. Remember your times tables.', time_limit: 30 };
        }
        case 'div': {
            const b = rand_for_diff(2, difficulty);
            const res = rand_for_diff(5, difficulty);
            const a = b * res;
            return { question_html: `${a} ÷ ${b} = ?`, answer: res.toString(), hint: 'Divide completely—the result is a whole number.', time_limit: 30 };
        }
        case 'frac': {
            const num1 = Math.floor(Math.random() * 9) + 1;
            const den1 = Math.floor(Math.random() * 8) + 2;
            const num2 = Math.floor(Math.random() * 9) + 1;
            const den2 = Math.floor(Math.random() * 8) + 2;
            const op = Math.random() > 0.5 ? '+' : '-';
            const answer_fraction = compute_fraction(num1, den1, num2, den2, op);
            return { question_html: `${num1}/${den1} ${op} ${num2}/${den2} = ? (simplify)`, answer: answer_fraction, hint: 'Convert to a common denominator then calculate the numerator.', time_limit: 40 };
        }
        case 'percent': {
            const base = rand_for_diff(50, difficulty) * 10;
            const pct = Math.floor(Math.random() * 46) + 5;
            const ans = (base * pct) / 100;
            return { question_html: `${pct}% of ${base} = ?`, answer: format_answer(ans), hint: 'Calculate percentage: (percent/100) × value.', time_limit: 35 };
        }
        case 'equation': {
            const a = Math.floor(Math.random() * 5) + 1;
            const x = Math.floor(Math.random() * 10) + 1;
            const b = Math.floor(Math.random() * 21) - 10;
            const c = a * x + b;
            return { question_html: `${a}x + (${b}) = ${c}. Find x.`, answer: x.toString(), hint: 'Solve for x: x = (c - b) / a.', time_limit: 45 };
        }
    }
}

function rand_for_diff(base, difficulty) {
    let multiplier;
    switch (difficulty) {
        case 1: multiplier = 1; break;
        case 2: multiplier = 2; break;
        default: multiplier = 3;
    }
    return Math.floor(Math.random() * (base * multiplier)) + 1;
}

function gcd(a, b) {
    return b === 0 ? a : gcd(b, a % b);
}

function compute_fraction(n1, d1, n2, d2, op) {
    let num = n1 * d2 + (op === '+' ? 1 : -1) * n2 * d1;
    let den = d1 * d2;
    if (num === 0) return '0';
    const commonDivisor = gcd(Math.abs(num), den);
    num /= commonDivisor;
    den /= commonDivisor;
    if (den === 1) return num.toString();
    return `${num}/${den}`;
}

function compare_answers(given, correct) {
    const g = given.trim();
    const c = correct.trim();
    if (g === '') return false;
    
    // Handle fraction comparison by converting to floats
    const g_norm = normalize_fraction(g);
    const c_norm = normalize_fraction(c);
    
    if (g_norm === false || c_norm === false) return false;
    
    return Math.abs(g_norm - c_norm) < 1e-9;
}

function normalize_fraction(s) {
    s = s.trim();
    if (s === '') return false;
    if (s.includes('/')) {
        const parts = s.split('/');
        if (parts.length !== 2) return false;
        const num = parseFloat(parts[0]);
        const den = parseFloat(parts[1]);
        if (isNaN(num) || isNaN(den) || den === 0) return false;
        return num / den;
    }
    const val = parseFloat(s);
    if (isNaN(val)) return false;
    return val;
}

function format_answer(a) {
    const num = Number(a);
    if (!isNaN(num) && Number.isInteger(num)) {
        return num.toString();
    }
    return a.toString();
}

async function load_highscores() {
    try {
        const data = await fs.readFile(HIGHSCORE_FILE, 'utf8');
        return JSON.parse(data);
    } catch (error) {
        // If file doesn't exist or is invalid, return an empty array
        return [];
    }
}

async function save_highscore(name, score) {
    const list = await load_highscores();
    list.push({ name: name.substring(0, 30), score: parseInt(score, 10), time: Date.now() });
    list.sort((a, b) => b.score - a.score);
    const top50 = list.slice(0, 50);
    await fs.writeFile(HIGHSCORE_FILE, JSON.stringify(top50, null, 2));
}

// --- Start the server ---
app.listen(PORT, () => {
    console.log(`Server is running at http://localhost:${PORT}`);
});
