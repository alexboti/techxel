<?php
/**
 * CCI Printer Registry (session-guarded)
 */
session_name('techxel_cci');
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
  <title>CCI Printer Registry – Techxel</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg: #0a0e1a; --surface: #111827; --surface2: #1a2235; --surface3: #212d45;
      --border: rgba(99,179,237,0.15); --accent: #3b82f6; --accent2: #6366f1;
      --accent-glow: rgba(59,130,246,0.3); --text: #e2e8f0; --muted: #94a3b8;
      --danger: #ef4444; --danger-bg: rgba(239,68,68,0.12);
      --success: #22c55e; --success-bg: rgba(34,197,94,0.12);
      --warn: #f59e0b; --warn-bg: rgba(245,158,11,0.12);
      --radius: 14px; --radius-sm: 9px; --font: 'Inter', sans-serif;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: var(--font); background: var(--bg); color: var(--text);
      min-height: 100vh; padding: 0;
      background-image:
        radial-gradient(ellipse 80% 50% at 50% -10%, rgba(59,130,246,0.15) 0%, transparent 70%),
        radial-gradient(ellipse 50% 40% at 85% 90%, rgba(99,102,241,0.1) 0%, transparent 60%);
    }

    /* ── Top bar ── */
    .topbar {
      background: var(--surface); border-bottom: 1px solid var(--border);
      padding: 14px 28px; display: flex; align-items: center; gap: 14px;
      position: sticky; top: 0; z-index: 100; backdrop-filter: blur(10px);
    }
    .back-btn {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 7px 14px; border-radius: var(--radius-sm);
      border: 1px solid var(--border); background: transparent;
      color: var(--muted); font-family: var(--font); font-size: 0.8rem;
      cursor: pointer; text-decoration: none; transition: all .18s;
    }
    .back-btn:hover { border-color: var(--accent); color: var(--accent); }
    .topbar-title { font-size: 1rem; font-weight: 700; }
    .topbar-sub { font-size: 0.75rem; color: var(--muted); margin-top: 1px; }
    .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }

    /* sync dot */
    #syncDot {
      width: 8px; height: 8px; border-radius: 50%;
      background: var(--success); display: inline-block;
      transition: background .3s; flex-shrink: 0;
    }
    #syncLabel { font-size: 0.73rem; color: var(--muted); }

    /* ── Main layout ── */
    .main { padding: 28px; max-width: 900px; margin: 0 auto; }

    /* ── Toolbar ── */
    .toolbar {
      display: flex; gap: 10px; align-items: center;
      margin-bottom: 18px; flex-wrap: wrap;
    }
    .search-wrap { position: relative; flex: 1; min-width: 200px; }
    .search-wrap svg {
      position: absolute; left: 11px; top: 50%;
      transform: translateY(-50%); color: var(--muted); pointer-events: none;
    }
    #searchInput {
      width: 100%; padding: 9px 12px 9px 36px;
      background: var(--surface); border: 1px solid var(--border);
      border-radius: var(--radius-sm); color: var(--text);
      font-family: var(--font); font-size: 0.85rem; outline: none;
      transition: border-color .18s;
    }
    #searchInput:focus { border-color: var(--accent); }
    #searchInput::placeholder { color: var(--muted); }

    .btn {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 9px 18px; border-radius: var(--radius-sm); font-size: 0.84rem;
      font-weight: 600; cursor: pointer; border: 1px solid transparent;
      font-family: var(--font); transition: all .18s; white-space: nowrap;
    }
    .btn-primary { background: var(--accent); color: #fff; border-color: var(--accent); }
    .btn-primary:hover { background: #2563eb; }
    .btn-ghost {
      background: transparent; border-color: var(--border); color: var(--muted);
    }
    .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }

    /* ── Table ── */
    .table-wrap {
      background: var(--surface); border: 1px solid var(--border);
      border-radius: var(--radius); overflow: hidden;
      box-shadow: 0 4px 32px rgba(0,0,0,0.35);
    }
    table { width: 100%; border-collapse: collapse; }
    thead th {
      padding: 12px 16px; text-align: left;
      font-size: 0.72rem; text-transform: uppercase; letter-spacing: .08em;
      color: var(--muted); border-bottom: 1px solid var(--border);
      background: var(--surface2); user-select: none;
    }
    thead th.sortable { cursor: pointer; }
    thead th.sortable:hover { color: var(--accent); }
    thead th .sort-arrow { margin-left: 5px; opacity: 0.4; }
    thead th.sort-asc  .sort-arrow { opacity: 1; }
    thead th.sort-desc .sort-arrow { opacity: 1; }

    tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--surface2); }

    td { padding: 11px 16px; font-size: 0.88rem; vertical-align: middle; }
    .td-actions { text-align: right; white-space: nowrap; width: 140px; }

    .action-btn {
      display: inline-flex; align-items: center; justify-content: center;
      width: 32px; height: 32px; border-radius: 7px;
      border: 1px solid var(--border); background: transparent;
      color: var(--muted); cursor: pointer; font-size: 0.85rem;
      transition: all .15s; font-family: var(--font);
    }
    .action-btn:hover.edit-btn  { border-color: var(--accent); color: var(--accent); background: rgba(59,130,246,0.1); }
    .action-btn:hover.del-btn   { border-color: var(--danger); color: var(--danger); background: var(--danger-bg); }

    /* ── Ink status pill ── */
    .ink-pill {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 3px 10px; border-radius: 20px;
      font-size: 0.75rem; font-weight: 600; white-space: nowrap;
    }
    .ink-ok  { background: var(--success-bg); color: var(--success); border: 1px solid rgba(34,197,94,0.25); }
    .ink-low { background: var(--warn-bg);    color: var(--warn);    border: 1px solid rgba(245,158,11,0.25); }

    /* ── Buy button ── */
    .buy-btn {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 4px 11px; border-radius: 7px; font-size: 0.76rem; font-weight: 600;
      background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.35);
      color: #818cf8; cursor: pointer; text-decoration: none;
      transition: all .15s; white-space: nowrap;
    }
    .buy-btn:hover { background: rgba(99,102,241,0.28); border-color: #818cf8; color: #a5b4fc; }

    /* ── Ink toggle in edit mode ── */
    .ink-toggle-wrap {
      display: flex; align-items: center; gap: 8px;
      font-size: 0.84rem; color: var(--text);
    }
    .ink-toggle-wrap input[type=checkbox] { width: 16px; height: 16px; cursor: pointer; accent-color: var(--warn); }

    /* ── Edit form: url field label ── */
    .edit-field-wrap { display: flex; flex-direction: column; gap: 4px; }
    .edit-field-label { font-size: 0.7rem; color: var(--muted); text-transform: uppercase; letter-spacing:.06em; }

    /* ── Inline editing ── */
    .edit-input {
      width: 100%; padding: 6px 10px;
      background: var(--surface3); border: 1px solid var(--accent);
      border-radius: 6px; color: var(--text); font-family: var(--font);
      font-size: 0.88rem; outline: none;
    }

    /* ── Add-row form ── */
    .add-row { background: var(--surface2); }
    .add-row td { padding: 10px 16px; }
    .add-input {
      width: 100%; padding: 7px 11px;
      background: var(--surface3); border: 1px solid var(--border);
      border-radius: 7px; color: var(--text); font-family: var(--font);
      font-size: 0.88rem; outline: none; transition: border-color .18s;
    }
    .add-input:focus { border-color: var(--accent); }
    .add-input::placeholder { color: var(--muted); }

    /* ── Empty state ── */
    .empty-state {
      text-align: center; padding: 60px 20px; color: var(--muted); display: none;
    }
    .empty-state .icon { font-size: 2.5rem; margin-bottom: 12px; }
    .empty-state p { font-size: 0.9rem; }

    /* ── Count badge ── */
    .count-badge {
      display: inline-flex; align-items: center; justify-content: center;
      background: var(--surface2); border: 1px solid var(--border);
      border-radius: 20px; padding: 3px 11px;
      font-size: 0.75rem; color: var(--muted); margin-left: 8px;
    }

    /* ── Toast ── */
    #toast {
      position: fixed; bottom: 24px; right: 24px;
      background: var(--surface2); border: 1px solid var(--border);
      color: var(--text); padding: 11px 18px; border-radius: var(--radius-sm);
      font-size: 0.84rem; font-weight: 500; z-index: 9999;
      box-shadow: 0 8px 30px rgba(0,0,0,0.6);
      transform: translateY(16px); opacity: 0;
      transition: all .25s ease; pointer-events: none;
    }
    #toast.show { transform: translateY(0); opacity: 1; }

    @media (max-width: 600px) {
      .main { padding: 16px; }
      .topbar { padding: 12px 16px; }
      td { padding: 9px 10px; font-size: 0.82rem; }
      thead th { padding: 10px; }
    }
  </style>
</head>
<body>

  <!-- Top bar -->
  <div class="topbar">
    <a class="back-btn" href="portal.php">← Back</a>
    <div>
      <div class="topbar-title">🖨️ Printer Registry</div>
      <div class="topbar-sub">CapitalCare – House Printer Inventory</div>
    </div>
    <div class="topbar-right">
      <span id="syncDot"></span>
      <span id="syncLabel">Syncing…</span>
    </div>
  </div>

  <!-- Main content -->
  <div class="main">

    <!-- Toolbar -->
    <div class="toolbar">
      <div class="search-wrap">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" id="searchInput" placeholder="Search location or model…" oninput="applyFilter()">
      </div>
      <button class="btn btn-primary" onclick="showAddRow()">＋ Add Printer</button>
    </div>

    <!-- Table -->
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th class="sortable" id="th-location" onclick="sortBy('location')">
              Location <span class="sort-arrow" id="arrow-location">↕</span>
            </th>
            <th class="sortable" id="th-model" onclick="sortBy('model')">
              Printer Model <span class="sort-arrow" id="arrow-model">↕</span>
            </th>
            <th>Ink Status</th>
            <th>Purchase</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
      <div class="empty-state" id="emptyState">
        <div class="icon">🖨️</div>
        <p id="emptyMsg">No printers yet. Click <strong>+ Add Printer</strong> to get started.</p>
      </div>
    </div>

  </div>

  <div id="toast"></div>

  <script>
    const API = 'printers-api.php';
    let printers  = [];   // [{ id, location, model, ink_low, purchase_url }]
    let editingId = null;
    let sortCol   = 'location';
    let sortDir   = 'asc';
    let addRowVisible = false;

    // ── Sync indicator ────────────────────────────────────────────────────
    function setSyncState(s) {
      const dot   = document.getElementById('syncDot');
      const label = document.getElementById('syncLabel');
      const map = {
        synced:  ['#22c55e', 'Synced'],
        saving:  ['#f59e0b', 'Saving…'],
        loading: ['#3b82f6', 'Loading…'],
        error:   ['#ef4444', 'Sync error'],
      };
      const [color, text] = map[s] || map.synced;
      dot.style.background = color;
      label.textContent    = text;
    }

    // ── API helpers ───────────────────────────────────────────────────────
    async function loadPrinters(silent = false) {
      if (!silent) setSyncState('loading');
      try {
        const res  = await fetch(API + '?_=' + Date.now());
        const data = await res.json();
        if (data.success) {
          printers = data.printers || [];
          renderTable();
          setSyncState('synced');
        }
      } catch {
        setSyncState('error');
        if (!silent) showToast('⚠ Could not load data from server.', true);
      }
    }

    async function apiPost(body) {
      setSyncState('saving');
      try {
        const res  = await fetch(API, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(body)
        });
        const data = await res.json();
        if (data.success) {
          printers = data.printers || [];
          renderTable();
          setSyncState('synced');
          return true;
        } else {
          setSyncState('error');
          showToast('⚠ ' + (data.error || 'Operation failed.'), true);
          return false;
        }
      } catch {
        setSyncState('error');
        showToast('⚠ Network error.', true);
        return false;
      }
    }

    // ── Sorting ───────────────────────────────────────────────────────────
    function sortBy(col) {
      if (sortCol === col) {
        sortDir = sortDir === 'asc' ? 'desc' : 'asc';
      } else {
        sortCol = col; sortDir = 'asc';
      }
      renderTable();
    }

    function getSorted(list) {
      return [...list].sort((a, b) => {
        const av = (a[sortCol] || '').toLowerCase();
        const bv = (b[sortCol] || '').toLowerCase();
        return sortDir === 'asc' ? av.localeCompare(bv) : bv.localeCompare(av);
      });
    }

    function updateSortArrows() {
      ['location', 'model'].forEach(col => {
        const th    = document.getElementById('th-' + col);
        const arrow = document.getElementById('arrow-' + col);
        th.classList.remove('sort-asc', 'sort-desc');
        if (sortCol === col) {
          th.classList.add('sort-' + sortDir);
          arrow.textContent = sortDir === 'asc' ? '↑' : '↓';
        } else {
          arrow.textContent = '↕';
        }
      });
    }

    // ── Render ────────────────────────────────────────────────────────────
    function applyFilter() {
      renderTable();
    }

    function renderTable() {
      const q     = document.getElementById('searchInput').value.toLowerCase();
      const tbody = document.getElementById('tableBody');
      updateSortArrows();

      // Remove existing data rows (keep add-row if present)
      Array.from(tbody.querySelectorAll('tr:not(.add-row)')).forEach(r => r.remove());

      const filtered = getSorted(printers).filter(p =>
        !q || p.location.toLowerCase().includes(q) || p.model.toLowerCase().includes(q)
      );

      if (filtered.length === 0 && !addRowVisible) {
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('emptyMsg').textContent =
          q ? 'No printers match your search.' : 'No printers yet. Click + Add Printer to get started.';
      } else {
        document.getElementById('emptyState').style.display = 'none';
      }

      filtered.forEach(p => {
        const tr = buildRow(p);
        tbody.insertBefore(tr, tbody.querySelector('.add-row'));
      });
    }

    function buildRow(p) {
      const tr = document.createElement('tr');
      tr.dataset.id = p.id;

      if (editingId === p.id) {
        // ── Edit mode (5 columns, spans ink+purchase into one wide cell) ──
        const inkChecked = p.ink_low ? 'checked' : '';
        const purchaseVal = escHtml(p.purchase_url || '');
        tr.innerHTML = `
          <td><input class="edit-input" id="edit-location-${p.id}" value="${escHtml(p.location)}" placeholder="Location"></td>
          <td><input class="edit-input" id="edit-model-${p.id}" value="${escHtml(p.model)}" placeholder="Printer model"></td>
          <td colspan="2" style="min-width:260px">
            <div style="display:flex;flex-direction:column;gap:8px;">
              <label class="ink-toggle-wrap">
                <input type="checkbox" id="edit-ink-${p.id}" ${inkChecked}>
                <span>Ink needs replacement</span>
              </label>
              <div class="edit-field-wrap">
                <span class="edit-field-label">Purchase link (optional)</span>
                <input class="edit-input" id="edit-url-${p.id}" value="${purchaseVal}" placeholder="https://…" style="font-size:.8rem;">
              </div>
            </div>
          </td>
          <td class="td-actions">
            <button class="btn btn-primary" style="padding:5px 12px;font-size:.78rem;" onclick="saveEdit('${p.id}')">Save</button>
            <button class="btn btn-ghost"   style="padding:5px 10px;font-size:.78rem;margin-left:4px;" onclick="cancelEdit()">✕</button>
          </td>`;
        setTimeout(() => document.getElementById('edit-location-' + p.id)?.focus(), 30);
      } else {
        // ── View mode ──
        const inkPill = p.ink_low
          ? `<span class="ink-pill ink-low">⚠ Low</span>`
          : `<span class="ink-pill ink-ok">✓ OK</span>`;
        const buyBtn = p.purchase_url
          ? `<a class="buy-btn" href="${escHtml(p.purchase_url)}" target="_blank" rel="noopener">🛒 Buy</a>`
          : `<span style="color:var(--muted);font-size:.78rem;">—</span>`;
        tr.innerHTML = `
          <td>${escHtml(p.location)}</td>
          <td>${escHtml(p.model)}</td>
          <td>${inkPill}</td>
          <td>${buyBtn}</td>
          <td class="td-actions">
            <button class="action-btn edit-btn" title="Edit" onclick="startEdit('${p.id}')">✏️</button>
            <button class="action-btn del-btn"  title="Delete" onclick="deletePrinter('${p.id}', '${escHtml(p.location)}')" style="margin-left:5px;">🗑️</button>
          </td>`;
      }
      return tr;
    }

    // ── Add row ───────────────────────────────────────────────────────────
    function showAddRow() {
      if (addRowVisible) {
        document.getElementById('add-location')?.focus();
        return;
      }
      addRowVisible = true;
      document.getElementById('emptyState').style.display = 'none';
      const tbody = document.getElementById('tableBody');
      const tr    = document.createElement('tr');
      tr.className = 'add-row';
      tr.innerHTML = `
        <td><input class="add-input" id="add-location" placeholder="e.g. Trexler House" autocomplete="off"></td>
        <td><input class="add-input" id="add-model" placeholder="e.g. HP LaserJet Pro M404dn" autocomplete="off"></td>
        <td></td>
        <td></td>
        <td class="td-actions">
          <button class="btn btn-primary" style="padding:5px 12px;font-size:.78rem;" onclick="commitAdd()">Add</button>
          <button class="btn btn-ghost"   style="padding:5px 10px;font-size:.78rem;margin-left:4px;" onclick="hideAddRow()">✕</button>
        </td>`;
      tbody.appendChild(tr);
      document.getElementById('add-location').focus();

      // Submit on Enter key
      tr.querySelectorAll('.add-input').forEach(inp => {
        inp.addEventListener('keydown', e => { if (e.key === 'Enter') commitAdd(); });
      });
    }

    function hideAddRow() {
      addRowVisible = false;
      document.querySelector('.add-row')?.remove();
      renderTable();
    }

    async function commitAdd() {
      const location = document.getElementById('add-location')?.value.trim();
      const model    = document.getElementById('add-model')?.value.trim();
      if (!location || !model) { showToast('Please fill in both fields.', true); return; }
      const ok = await apiPost({ action: 'add', location, model });
      if (ok) {
        hideAddRow();
        showToast('Printer added.');
      }
    }

    // ── Edit ──────────────────────────────────────────────────────────────
    function startEdit(id) {
      editingId = id;
      renderTable();
    }

    function cancelEdit() {
      editingId = null;
      renderTable();
    }

    async function saveEdit(id) {
      const location     = document.getElementById('edit-location-' + id)?.value.trim();
      const model        = document.getElementById('edit-model-' + id)?.value.trim();
      const ink_low      = document.getElementById('edit-ink-' + id)?.checked || false;
      const purchase_url = document.getElementById('edit-url-' + id)?.value.trim() || '';
      if (!location || !model) { showToast('Both location and model are required.', true); return; }
      const ok = await apiPost({ action: 'update', id, location, model, ink_low, purchase_url });
      if (ok) { editingId = null; showToast('Entry updated.'); }
    }

    // ── Delete ────────────────────────────────────────────────────────────
    async function deletePrinter(id, locationName) {
      if (!confirm(`Delete the printer entry for "${locationName}"?`)) return;
      const ok = await apiPost({ action: 'delete', id });
      if (ok) showToast('Entry deleted.');
    }

    // ── Utilities ─────────────────────────────────────────────────────────
    function escHtml(str) {
      return String(str)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    let toastTimer;
    function showToast(msg, isError = false) {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.style.borderColor = isError ? '#ef4444' : 'rgba(99,179,237,0.15)';
      t.classList.add('show');
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => t.classList.remove('show'), 2800);
    }

    // ── Init ──────────────────────────────────────────────────────────────
    loadPrinters();
    setInterval(() => loadPrinters(true), 20000);
    document.addEventListener('visibilitychange', () => {
      if (!document.hidden) loadPrinters(true);
    });
  </script>
</body>
</html>
