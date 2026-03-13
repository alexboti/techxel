<?php
/**
 * CCI Portal — Login
 * Server-side passcode check with brute-force lockout.
 */
session_name('techxel_cci');
session_start();

// ── CONFIG ──────────────────────────────────
define('PASSCODE',       '3019490466');
define('MAX_ATTEMPTS',   5);
define('LOCKOUT_SECS',   900); // 15 minutes
// ────────────────────────────────────────────

// Already authenticated → go straight to portal
if (!empty($_SESSION['authenticated'])) {
    header('Location: portal.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lockout check
    $attempts  = $_SESSION['attempts']  ?? 0;
    $lockUntil = $_SESSION['lock_until'] ?? 0;

    if ($lockUntil && time() < $lockUntil) {
        $mins = ceil(($lockUntil - time()) / 60);
        $error = "Too many failed attempts. Please wait {$mins} minute(s).";
    } else {
        if ($lockUntil && time() >= $lockUntil) {
            // Lockout expired — reset
            $_SESSION['attempts']  = 0;
            $_SESSION['lock_until'] = 0;
            $attempts = 0;
        }

        $passcode = trim($_POST['passcode'] ?? '');

        if ($passcode === PASSCODE) {
            // Success — regenerate session to prevent fixation
            session_regenerate_id(true);
            $_SESSION['authenticated'] = true;
            $_SESSION['attempts']      = 0;
            $_SESSION['lock_until']    = 0;
            header('Location: portal.php');
            exit;
        } else {
            $attempts++;
            $_SESSION['attempts'] = $attempts;
            if ($attempts >= MAX_ATTEMPTS) {
                $_SESSION['lock_until'] = time() + LOCKOUT_SECS;
                $error = 'Too many failed attempts. Access locked for 15 minutes.';
            } else {
                $remaining = MAX_ATTEMPTS - $attempts;
                $error = "Incorrect passcode. {$remaining} attempt(s) remaining.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CCI Portal – Techxel</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg: #0a0e1a; --surface: #111827; --surface2: #1a2235;
      --border: rgba(99,179,237,0.15); --accent: #3b82f6; --accent2: #6366f1;
      --accent-glow: rgba(59,130,246,0.35); --text: #e2e8f0; --muted: #94a3b8;
      --danger: #ef4444; --radius: 16px; --radius-sm: 10px; --font: 'Inter', sans-serif;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; }
    body {
      font-family: var(--font); background: var(--bg); color: var(--text);
      min-height: 100vh; display: flex; align-items: center; justify-content: center;
      background-image:
        radial-gradient(ellipse 80% 60% at 50% -10%, rgba(59,130,246,0.18) 0%, transparent 70%),
        radial-gradient(ellipse 60% 40% at 80% 90%, rgba(99,102,241,0.12) 0%, transparent 60%);
    }
    .panel {
      background: var(--surface); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 2.5rem 2.75rem; width: 100%; max-width: 480px;
      box-shadow: 0 0 0 1px rgba(255,255,255,0.04), 0 24px 64px rgba(0,0,0,0.55), 0 0 80px var(--accent-glow);
      animation: fadeIn 0.4s ease;
    }
    @keyframes fadeIn { from { opacity:0; transform:translateY(12px);} to {opacity:1; transform:translateY(0);} }
    .brand { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; }
    .brand-icon {
      width: 44px; height: 44px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border-radius: 12px; display: flex; align-items: center; justify-content: center;
      font-size: 1.25rem; box-shadow: 0 4px 20px var(--accent-glow);
    }
    .brand-title { font-size: 1.1rem; font-weight: 700; color: var(--text); line-height: 1; }
    .brand-sub   { font-size: 0.72rem; color: var(--muted); letter-spacing: 0.08em; text-transform: uppercase; margin-top: 3px; }
    h2 {
      font-size: 1.5rem; font-weight: 700; margin-bottom: 0.4rem;
      background: linear-gradient(135deg, #e2e8f0 30%, #94a3b8);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .subtitle { font-size: 0.87rem; color: var(--muted); margin-bottom: 2rem; }
    label { display: block; font-size: 0.82rem; font-weight: 500; color: var(--muted); margin-bottom: 0.45rem; letter-spacing: 0.04em; text-transform: uppercase; }
    .input-wrap { position: relative; margin-bottom: 1.4rem; }
    .input-wrap input {
      width: 100%; padding: 0.85rem 3rem 0.85rem 1.1rem;
      background: var(--surface2); border: 1px solid var(--border);
      border-radius: var(--radius-sm); color: var(--text); font-size: 1rem;
      font-family: var(--font); outline: none; letter-spacing: 0.12em;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-wrap input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(59,130,246,0.2); }
    .eye-btn {
      position: absolute; right: 0.9rem; top: 50%; transform: translateY(-50%);
      background: none; border: none; cursor: pointer; color: var(--muted);
      padding: 0; line-height: 1; font-size: 1.1rem; transition: color 0.2s;
    }
    .eye-btn:hover { color: var(--text); }
    .btn {
      width: 100%; padding: 0.9rem;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border: none; border-radius: var(--radius-sm); color: #fff;
      font-size: 0.95rem; font-weight: 600; font-family: var(--font);
      cursor: pointer; transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 4px 20px var(--accent-glow);
    }
    .btn:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 8px 30px var(--accent-glow); }
    .btn:active { transform: translateY(0); }
    .btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
    .error-banner {
      background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.35);
      border-radius: var(--radius-sm); padding: 0.7rem 1rem;
      font-size: 0.84rem; color: #fca5a5; margin-bottom: 1.2rem;
      display: flex; align-items: center; gap: 0.5rem;
    }
  </style>
</head>
<body>
<div class="panel">
  <div class="brand">
    <div class="brand-icon">🔐</div>
    <div>
      <div class="brand-title">Techxel</div>
      <div class="brand-sub">Internal Portal</div>
    </div>
  </div>

  <h2>CCI Portal</h2>
  <p class="subtitle">Enter your passcode to access CCI resources.</p>

  <?php if ($error): ?>
    <div class="error-banner"><span>⚠️</span> <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="index.php">
    <label for="passcode">Passcode</label>
    <div class="input-wrap">
      <input type="password" id="passcode" name="passcode"
             placeholder="Enter passcode" autocomplete="current-password"
             <?= (!empty($_SESSION['lock_until']) && time() < $_SESSION['lock_until']) ? 'disabled' : '' ?> />
      <button type="button" class="eye-btn" onclick="toggleEye()" aria-label="Toggle visibility">👁</button>
    </div>
    <button type="submit" class="btn"
            <?= (!empty($_SESSION['lock_until']) && time() < $_SESSION['lock_until']) ? 'disabled' : '' ?>>
      Unlock Portal
    </button>
  </form>
</div>

<script>
  function toggleEye() {
    const inp = document.getElementById('passcode');
    inp.type = inp.type === 'password' ? 'text' : 'password';
  }
  // Focus input on load
  document.getElementById('passcode').focus();
</script>
</body>
</html>
