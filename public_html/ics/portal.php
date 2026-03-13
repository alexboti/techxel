<?php
/**
 * ICS Portal — Links Page (session-guarded)
 */
session_name('techxel_ics');
session_start();

if (empty($_SESSION['authenticated'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ICS Links – Techxel</title>
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
      backdrop-filter: blur(10px); animation: fadeIn 0.4s ease;
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
    .page-header { text-align: center; margin-bottom: 2.5rem; }
    .page-header h1 {
      font-size: 1.85rem; font-weight: 700;
      background: linear-gradient(135deg, #e2e8f0, #94a3b8);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .page-header p { font-size: 0.87rem; color: var(--muted); margin-top: 0.35rem; }
    .links-grid { display: flex; flex-direction: column; gap: 1rem; }
    .link-card {
      display: flex; align-items: center; gap: 1.1rem;
      background: var(--surface2); border: 1px solid var(--border);
      border-radius: var(--radius-sm); padding: 1.1rem 1.25rem;
      text-decoration: none; color: var(--text);
      transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .link-card:hover {
      border-color: var(--accent); background: rgba(59,130,246,0.07);
      transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.35);
    }
    .link-icon {
      width: 42px; height: 42px; flex-shrink: 0; border-radius: 10px;
      display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
    }
    .link-icon.blue   { background: rgba(59,130,246,0.18); }
    .link-icon.purple { background: rgba(99,102,241,0.18); }
    .link-icon.green  { background: rgba(34,197,94,0.18); }
    .link-icon.orange { background: rgba(249,115,22,0.18); }
    .link-icon.pink   { background: rgba(236,72,153,0.18); }
    .link-info { flex: 1; }
    .link-title { font-size: 0.95rem; font-weight: 600; line-height: 1.2; }
    .link-desc  { font-size: 0.78rem; color: var(--muted); margin-top: 0.2rem; }
    .link-arrow { color: var(--muted); font-size: 1rem; transition: transform 0.2s, color 0.2s; }
    .link-card:hover .link-arrow { transform: translateX(4px); color: var(--accent); }
    .logout-form { margin-top: 2rem; }
    .logout-btn {
      width: 100%; padding: 0.7rem; background: transparent;
      border: 1px solid var(--border); border-radius: var(--radius-sm);
      color: var(--muted); font-size: 0.82rem; font-family: var(--font);
      cursor: pointer; transition: border-color 0.2s, color 0.2s;
    }
    .logout-btn:hover { border-color: var(--danger); color: #fca5a5; }
  </style>
</head>
<body>
<div class="panel">
  <div class="brand">
    <div class="brand-icon">🌐</div>
    <div>
      <div class="brand-title">ICS Portal</div>
      <div class="brand-sub">Techxel Internal</div>
    </div>
  </div>

  <div class="page-header">
    <h1>ICS Links</h1>
    <p>Quick access to all ICS tools &amp; resources</p>
  </div>

  <div class="links-grid">

    <!-- Link 1: Email Creation -->
    <a class="link-card" href="https://ics-emailcreation.onrender.com" target="_blank" rel="noopener">
      <div class="link-icon blue">✉️</div>
      <div class="link-info">
        <div class="link-title">ICS Email Creation</div>
        <div class="link-desc">Create and provision new ICS email accounts</div>
      </div>
      <span class="link-arrow">→</span>
    </a>

    <!-- Link 2: Email Review -->
    <a class="link-card" href="https://techxel.com/ics-review/index.html" target="_blank" rel="noopener">
      <div class="link-icon purple">📋</div>
      <div class="link-info">
        <div class="link-title">ICS Email Review</div>
        <div class="link-desc">Review and manage existing ICS email accounts</div>
      </div>
      <span class="link-arrow">→</span>
    </a>

    <!-- ══ ADD MORE LINKS BELOW THIS LINE ══
         Copy a link-card block above, then update:
           href       – destination URL
           link-icon  – class: blue | purple | green | orange | pink
           emoji      – any relevant emoji
           link-title – short tool name
           link-desc  – one-line description
    -->

  </div>

  <form class="logout-form" method="POST" action="logout.php">
    <button type="submit" class="logout-btn">🔒 Lock Portal</button>
  </form>
</div>
</body>
</html>
