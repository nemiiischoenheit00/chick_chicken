<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="assets/Logo.png" />
    <link rel="stylesheet" href="admin.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Chick Chicken – Inventory</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');

        /* ── Page layout ────────────────────────────────── */
        .inv-page {
            padding: 0 28px 40px 28px;
        }
        .inv-page h1 {
            font-family: 'Oswald', sans-serif;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: #1a1a1a;
        }
        .inv-subtitle {
            color: #888;
            font-size: 13px;
            margin-bottom: 24px;
        }

        /* ── Stat cards ────────────────────────────────── */
        .inv-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }
        .inv-stat {
            background: #fff;
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .inv-stat-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .inv-stat-icon.yellow  { background: #fff8dc; color: #e0a800; }
        .inv-stat-icon.green   { background: #e8f5e9; color: #2e7d32; }
        .inv-stat-icon.orange  { background: #fff3e0; color: #e65c00; }
        .inv-stat-icon.red     { background: #fce4ec; color: #c62828; }
        .inv-stat-label { font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
        .inv-stat-val   { font-size: 22px; font-weight: 800; color: #1a1a1a; line-height: 1.1; font-family: 'Oswald', sans-serif; }

        /* ── Toolbar ────────────────────────────────────── */
        .inv-toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }
        .inv-search {
            flex: 1; min-width: 200px;
            position: relative;
        }
        .inv-search input {
            width: 100%;
            border: 1.5px solid #e8e8e8;
            border-radius: 10px;
            padding: 9px 14px 9px 38px;
            font-size: 14px;
            outline: none;
            background: #fff;
            transition: border .2s;
        }
        .inv-search input:focus { border-color: #f5c800; }
        .inv-search .search-ico {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #bbb; font-size: 16px; pointer-events: none;
        }
        .inv-filter-select {
            border: 1.5px solid #e8e8e8;
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 13px;
            background: #fff;
            outline: none;
            cursor: pointer;
            transition: border .2s;
        }
        .inv-filter-select:focus { border-color: #f5c800; }
        .btn-inv-add {
            background: #f5c800;
            border: none;
            border-radius: 10px;
            padding: 9px 20px;
            font-weight: 700;
            font-size: 14px;
            color: #1a1a1a;
            display: flex; align-items: center; gap: 6px;
            cursor: pointer;
            transition: background .2s, transform .1s;
            white-space: nowrap;
        }
        .btn-inv-add:hover { background: #e0b300; transform: translateY(-1px); }

        /* ── Table card ────────────────────────────────── */
        .inv-table-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 14px rgba(0,0,0,.07);
            overflow: hidden;
        }
        .inv-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .inv-table thead tr {
            background: #fafafa;
            border-bottom: 2px solid #f0f0f0;
        }
        .inv-table thead th {
            padding: 13px 16px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #888;
            white-space: nowrap;
        }
        .inv-table tbody tr {
            border-bottom: 1px solid #f5f5f5;
            transition: background .15s;
        }
        .inv-table tbody tr:last-child { border-bottom: none; }
        .inv-table tbody tr:hover { background: #fffdf0; }
        .inv-table td {
            padding: 13px 16px;
            vertical-align: middle;
        }
        .prod-name {
            font-weight: 700;
            color: #1a1a1a;
        }
        .prod-cat {
            font-size: 11px;
            color: #bbb;
            margin-top: 2px;
        }

        /* ── Stock bar ────────────────────────────────── */
        .stock-bar-wrap {
            display: flex; align-items: center; gap: 8px;
        }
        .stock-bar-bg {
            flex: 1; height: 7px; background: #f0f0f0; border-radius: 99px; overflow: hidden;
        }
        .stock-bar-fill {
            height: 100%; border-radius: 99px; transition: width .4s;
        }
        .fill-ok     { background: #4caf50; }
        .fill-low    { background: #ff9800; }
        .fill-out    { background: #e53935; }
        .stock-pct   { font-size: 12px; font-weight: 700; color: #666; width: 36px; text-align: right; }

        /* ── Badges ──────────────────────────────────── */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        .badge-ok   { background: #e8f5e9; color: #2e7d32; }
        .badge-low  { background: #fff3e0; color: #e65c00; }
        .badge-out  { background: #fce4ec; color: #c62828; }

        /* ── Row action buttons ───────────────────────── */
        .row-actions { display: flex; gap: 6px; }
        .btn-row {
            border: none; border-radius: 8px; padding: 6px 10px;
            font-size: 12px; font-weight: 700; cursor: pointer;
            transition: opacity .15s, transform .1s;
            display: flex; align-items: center; gap: 4px;
        }
        .btn-row:hover { opacity: .8; transform: translateY(-1px); }
        .btn-edit    { background: #e8f4fd; color: #1565c0; }
        .btn-restock { background: #e8f5e9; color: #2e7d32; }
        .btn-delete  { background: #fce4ec; color: #c62828; }

        /* ── Empty / loading states ─────────────────── */
        .inv-empty {
            text-align: center; padding: 50px 20px;
            color: #bbb; font-size: 14px;
        }
        .inv-empty ion-icon { font-size: 40px; display: block; margin: 0 auto 10px; }
        .skeleton {
            display: inline-block; background: linear-gradient(90deg,#eee 25%,#f8f8f8 50%,#eee 75%);
            background-size: 200% 100%; animation: shimmer 1.2s infinite;
            border-radius: 4px; height: 1em;
        }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

        /* ── Modal tweaks ────────────────────────────── */
        .modal-header {
            background: #1a1a1a;
            color: #fff;
            border-radius: 12px 12px 0 0;
        }
        .modal-header .btn-close { filter: invert(1); }
        .modal-title { font-family: 'Oswald', sans-serif; font-size: 1.2rem; font-weight: 600; }
        .modal-content { border-radius: 14px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,.18); }
        .modal-footer { border-top: 1px solid #f0f0f0; }
        .form-label { font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; color: #555; }
        .form-control, .form-select {
            border: 1.5px solid #e8e8e8; border-radius: 9px; font-size: 14px;
            transition: border .2s;
        }
        .form-control:focus, .form-select:focus { border-color: #f5c800; box-shadow: 0 0 0 3px rgba(245,200,0,.15); }
        .btn-save { background: #f5c800; border: none; color: #1a1a1a; font-weight: 800; border-radius: 9px; padding: 10px 24px; }
        .btn-save:hover { background: #e0b300; }
        .btn-cancel { border-radius: 9px; }

        /* ── Toast ───────────────────────────────────── */
        #toast {
            position: fixed; bottom: 24px; right: 24px;
            background: #1a1a1a; color: #fff;
            padding: 12px 20px; border-radius: 10px;
            font-size: 13px; font-weight: 600;
            display: none; z-index: 9999;
            box-shadow: 0 8px 30px rgba(0,0,0,.2);
            animation: slideUp .25s ease;
        }
        @keyframes slideUp { from { transform: translateY(20px); opacity:0 } to { transform:translateY(0); opacity:1 } }
        #toast.success::before { content: '✅ '; }
        #toast.error::before   { content: '❌ '; }

        /* ── Low stock alert bar ────────────────────── */
        .alert-low {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            border: 1.5px solid #ffb74d;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 13px;
            color: #bf360c;
            font-weight: 600;
            margin-bottom: 18px;
            display: none;
            align-items: center;
            gap: 10px;
        }
        .alert-low ion-icon { font-size: 18px; }

        /* ── Pagination ──────────────────────────────── */
        .inv-pagination {
            display: flex; justify-content: space-between; align-items: center;
            padding: 14px 20px;
            border-top: 1px solid #f0f0f0;
            font-size: 13px; color: #888;
        }
        .pg-btns { display: flex; gap: 6px; }
        .pg-btn {
            width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid #e8e8e8;
            background: #fff; font-size: 13px; font-weight: 700; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all .15s;
        }
        .pg-btn:hover, .pg-btn.active { background: #f5c800; border-color: #f5c800; color: #1a1a1a; }
        .pg-btn:disabled { opacity: .4; cursor: default; }

        @media (max-width: 900px) {
            .inv-stats { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>

<body>
<header>
    <div class="sidebar">
        <div class="logo">
            <h1>
                <a href="admin.html"><img src="assets/Logo2.png" alt="ChickChicken" style="width:auto;height:55px"/></a>
            </h1>
        </div>
        <div class="navigation--admin">
            <nav>
                <ul>
                    <li><a href="admin.html#dashboard--admin" class="header_button"><ion-icon name="grid-outline"></ion-icon><span>Dashboard</span></a></li>
                    <li><a href="orders--admin.html" class="header_button"><ion-icon name="bag-handle-outline"></ion-icon><span>Orders</span></a></li>
                    <li><a href="menu--admin.html" class="header_button"><ion-icon name="book-outline"></ion-icon><span>Menus</span></a></li>
                    <li><a href="inventory.html" class="header_button active"><ion-icon name="clipboard-outline"></ion-icon><span>Inventory</span></a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main class="main-content">
<div class="inv-page">
    <h1>Inventory</h1>
    <p class="inv-subtitle">Track stock levels, restocks, and consumption across all menu items.</p>

    <!-- Low stock alert -->
    <div class="alert-low" id="lowAlert">
        <ion-icon name="warning-outline"></ion-icon>
        <span id="lowAlertText"></span>
    </div>

    <!-- Stat cards -->
    <div class="inv-stats">
        <div class="inv-stat">
            <div class="inv-stat-icon yellow"><ion-icon name="cube-outline"></ion-icon></div>
            <div>
                <div class="inv-stat-label">Total Items</div>
                <div class="inv-stat-val" id="sTotal"><span class="skeleton" style="width:40px"></span></div>
            </div>
        </div>
        <div class="inv-stat">
            <div class="inv-stat-icon green"><ion-icon name="checkmark-circle-outline"></ion-icon></div>
            <div>
                <div class="inv-stat-label">In Stock</div>
                <div class="inv-stat-val" id="sOk"><span class="skeleton" style="width:40px"></span></div>
            </div>
        </div>
        <div class="inv-stat">
            <div class="inv-stat-icon orange"><ion-icon name="alert-circle-outline"></ion-icon></div>
            <div>
                <div class="inv-stat-label">Low Stock</div>
                <div class="inv-stat-val" id="sLow"><span class="skeleton" style="width:40px"></span></div>
            </div>
        </div>
        <div class="inv-stat">
            <div class="inv-stat-icon red"><ion-icon name="close-circle-outline"></ion-icon></div>
            <div>
                <div class="inv-stat-label">Out of Stock</div>
                <div class="inv-stat-val" id="sOut"><span class="skeleton" style="width:40px"></span></div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="inv-toolbar">
        <div class="inv-search">
            <ion-icon name="search-outline" class="search-ico"></ion-icon>
            <input type="text" id="searchInput" placeholder="Search items…" />
        </div>
        <select class="inv-filter-select" id="categoryFilter">
            <option value="">All Categories</option>
        </select>
        <select class="inv-filter-select" id="statusFilter">
            <option value="">All Status</option>
            <option value="ok">In Stock</option>
            <option value="low">Low Stock</option>
            <option value="out">Out of Stock</option>
        </select>
        <button class="btn-inv-add" onclick="openAddModal()">
            <ion-icon name="add-outline"></ion-icon> Add / Update Stock
        </button>
    </div>

    <!-- Table -->
    <div class="inv-table-card">
        <table class="inv-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Initial Stock</th>
                    <th>Remaining</th>
                    <th>Stock Level</th>
                    <th>Total Sold</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="invTableBody">
                <tr><td colspan="9" class="inv-empty">
                    <span class="skeleton" style="width:60%;display:block;margin:auto;height:18px"></span>
                </td></tr>
            </tbody>
        </table>
        <div class="inv-pagination" id="pagination" style="display:none">
            <span id="pgInfo"></span>
            <div class="pg-btns" id="pgBtns"></div>
        </div>
    </div>
</div>
</main>

<!-- ── EDIT / ADD MODAL ──────────────────────────────────── -->
<div class="modal fade" id="invModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Edit Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="editId" />
                <input type="hidden" id="editProductId" />

                <div class="mb-3" id="productSelectWrap">
                    <label class="form-label">Product</label>
                    <select class="form-select" id="editProductSelect"></select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Initial Stock</label>
                        <input type="number" class="form-control" id="editInitial" min="0" placeholder="e.g. 100" />
                    </div>
                    <div class="col-6">
                        <label class="form-label">Remaining</label>
                        <input type="number" class="form-control" id="editRemaining" min="0" placeholder="e.g. 75" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Low Stock Threshold <small style="color:#bbb;font-weight:400;">(alert when below)</small></label>
                    <input type="number" class="form-control" id="editThreshold" min="0" placeholder="e.g. 10" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-cancel" data-bs-dismiss="modal">Discard</button>
                <button type="button" class="btn btn-save" onclick="saveItem()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- ── RESTOCK MODAL ──────────────────────────────────────── -->
<div class="modal fade" id="restockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Restock Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="restockId" />
                <p style="font-size:13px;color:#666;" id="restockName"></p>
                <label class="form-label">Amount to Add</label>
                <input type="number" class="form-control" id="restockAmount" min="1" placeholder="e.g. 50" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-save" onclick="doRestock()">Restock</button>
            </div>
        </div>
    </div>
</div>

<!-- ── DELETE CONFIRM MODAL ───────────────────────────────── -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#c62828;">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <ion-icon name="trash-outline" style="font-size:36px;color:#c62828;display:block;margin:0 auto 10px"></ion-icon>
                <p style="font-size:14px;color:#444;">Remove <strong id="deleteName"></strong> from inventory tracking? The product record will not be deleted.</p>
                <input type="hidden" id="deleteId" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="doDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="admin.js"></script>

<script>
// ── CONFIG ─────────────────────────────────────────────────
const API = 'inventory_process.php';
const PER_PAGE = 10;
let allItems = [];
let currentPage = 1;
let editModal, restockModal, deleteModal;

// ── INIT ───────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    editModal    = new bootstrap.Modal(document.getElementById('invModal'));
    restockModal = new bootstrap.Modal(document.getElementById('restockModal'));
    deleteModal  = new bootstrap.Modal(document.getElementById('deleteModal'));

    loadStats();
    loadCategories();
    loadInventory();

    document.getElementById('searchInput').addEventListener('input', debounce(renderTable, 250));
    document.getElementById('categoryFilter').addEventListener('change', renderTable);
    document.getElementById('statusFilter').addEventListener('change', renderTable);

    setInterval(() => { loadStats(); loadInventory(true); }, 60000);
});

// ── FETCH HELPERS ──────────────────────────────────────────
async function get(action, params = {}) {
    const q = new URLSearchParams({ action, ...params });
    const r = await fetch(`${API}?${q}`);
    if (!r.ok) throw new Error('HTTP ' + r.status);
    return r.json();
}

async function post(action, body) {
    const r = await fetch(`${API}?action=${action}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body),
    });
    return r.json();
}

// ── LOAD STATS ─────────────────────────────────────────────
async function loadStats() {
    try {
        const s = await get('stats');
        document.getElementById('sTotal').textContent = s.total;
        document.getElementById('sOk').textContent    = s.ok;
        document.getElementById('sLow').textContent   = s.low;
        document.getElementById('sOut').textContent   = s.out;

        const alert = document.getElementById('lowAlert');
        if (s.low > 0 || s.out > 0) {
            alert.style.display = 'flex';
            let msg = [];
            if (s.out > 0) msg.push(`${s.out} item(s) are out of stock`);
            if (s.low > 0) msg.push(`${s.low} item(s) are running low`);
            document.getElementById('lowAlertText').textContent = msg.join(' · ') + '. Consider restocking soon.';
        } else {
            alert.style.display = 'none';
        }
    } catch(e) { console.error('Stats error', e); }
}

// ── LOAD CATEGORIES ────────────────────────────────────────
async function loadCategories() {
    try {
        const cats = await get('categories');
        const sel = document.getElementById('categoryFilter');
        cats.forEach(c => {
            const o = document.createElement('option');
            o.value = o.textContent = c;
            sel.appendChild(o);
        });
    } catch(e) {}
}

// ── LOAD INVENTORY ─────────────────────────────────────────
async function loadInventory(silent = false) {
    try {
        allItems = await get('list');
        currentPage = 1;
        renderTable();
    } catch(e) {
        if (!silent) {
            document.getElementById('invTableBody').innerHTML = `
                <tr><td colspan="9" class="inv-empty">
                    <ion-icon name="cloud-offline-outline"></ion-icon>
                    Could not connect. Check that XAMPP is running.
                </td></tr>`;
        }
    }
}

// ── RENDER TABLE (client-side filter + paginate) ───────────
function renderTable() {
    const search   = document.getElementById('searchInput').value.toLowerCase();
    const category = document.getElementById('categoryFilter').value;
    const status   = document.getElementById('statusFilter').value;

    const filtered = allItems.filter(it => {
        if (search   && !it.name.toLowerCase().includes(search)) return false;
        if (category && it.category !== category) return false;
        if (status   && it.stock_status !== status) return false;
        return true;
    });

    const totalPages = Math.max(1, Math.ceil(filtered.length / PER_PAGE));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage - 1) * PER_PAGE;
    const page  = filtered.slice(start, start + PER_PAGE);

    const tbody = document.getElementById('invTableBody');

    if (!page.length) {
        tbody.innerHTML = `<tr><td colspan="9" class="inv-empty">
            <ion-icon name="search-outline"></ion-icon>
            No items match your filters.
        </td></tr>`;
        document.getElementById('pagination').style.display = 'none';
        return;
    }

    tbody.innerHTML = page.map((it, idx) => {
        const pct   = parseFloat(it.stock_pct) || 0;
        const fillCls = it.stock_status === 'ok' ? 'fill-ok' : it.stock_status === 'low' ? 'fill-low' : 'fill-out';
        const badgeCls = `badge-${it.stock_status}`;
        const badgeTxt = it.stock_status === 'ok' ? 'In Stock' : it.stock_status === 'low' ? 'Low' : 'Out';

        return `<tr>
            <td style="color:#bbb;font-size:13px;">${start + idx + 1}</td>
            <td>
                <div class="prod-name">${esc(it.name)}</div>
                <div class="prod-cat">${esc(it.category || '—')}</div>
            </td>
            <td style="color:#888;font-size:13px;">${esc(it.category || '—')}</td>
            <td><strong>${it.initial_stock}</strong></td>
            <td><strong>${it.remaining}</strong></td>
            <td>
                <div class="stock-bar-wrap">
                    <div class="stock-bar-bg">
                        <div class="stock-bar-fill ${fillCls}" style="width:${Math.min(pct,100)}%"></div>
                    </div>
                    <span class="stock-pct">${pct}%</span>
                </div>
            </td>
            <td style="color:#888;">${it.total_sold ?? 0}</td>
            <td><span class="status-badge ${badgeCls}">${badgeTxt}</span></td>
            <td>
                <div class="row-actions">
                    <button class="btn-row btn-edit"    onclick="openEditModal(${JSON.stringify(it).replace(/"/g,'&quot;')})">
                        <ion-icon name="create-outline"></ion-icon> Edit
                    </button>
                    <button class="btn-row btn-restock" onclick="openRestockModal(${it.id}, '${esc(it.name)}')">
                        <ion-icon name="add-circle-outline"></ion-icon> Restock
                    </button>
                    <button class="btn-row btn-delete"  onclick="openDeleteModal(${it.id}, '${esc(it.name)}')">
                        <ion-icon name="trash-outline"></ion-icon>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');

    // Pagination info
    document.getElementById('pgInfo').textContent =
        `Showing ${start + 1}–${Math.min(start + PER_PAGE, filtered.length)} of ${filtered.length} items`;

    const pgBtns = document.getElementById('pgBtns');
    pgBtns.innerHTML = '';
    for (let p = 1; p <= totalPages; p++) {
        const btn = document.createElement('button');
        btn.className = 'pg-btn' + (p === currentPage ? ' active' : '');
        btn.textContent = p;
        btn.onclick = () => { currentPage = p; renderTable(); };
        pgBtns.appendChild(btn);
    }
    document.getElementById('pagination').style.display = 'flex';
}

// ── MODALS ─────────────────────────────────────────────────
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add / Update Stock';
    document.getElementById('editId').value = '';
    document.getElementById('editInitial').value   = 50;
    document.getElementById('editRemaining').value = 50;
    document.getElementById('editThreshold').value = 10;

    // Populate product dropdown
    const sel = document.getElementById('editProductSelect');
    sel.innerHTML = '<option value="">— Select a product —</option>';
    allItems.forEach(it => {
        const o = document.createElement('option');
        o.value = it.product_id;
        o.textContent = it.name;
        sel.appendChild(o);
    });
    document.getElementById('productSelectWrap').style.display = 'block';
    editModal.show();
}

function openEditModal(it) {
    document.getElementById('modalTitle').textContent = 'Edit Stock – ' + it.name;
    document.getElementById('editId').value        = it.id;
    document.getElementById('editProductId').value = it.product_id;
    document.getElementById('editInitial').value   = it.initial_stock;
    document.getElementById('editRemaining').value = it.remaining;
    document.getElementById('editThreshold').value = it.low_stock_threshold;
    document.getElementById('productSelectWrap').style.display = 'none';
    editModal.show();
}

async function saveItem() {
    const id        = document.getElementById('editId').value;
    const productId = document.getElementById('editProductId').value ||
                      document.getElementById('editProductSelect').value;
    const initial   = parseInt(document.getElementById('editInitial').value);
    const remaining = parseInt(document.getElementById('editRemaining').value);
    const threshold = parseInt(document.getElementById('editThreshold').value) || 10;

    if (!productId) { toast('Please select a product.', 'error'); return; }
    if (isNaN(initial) || isNaN(remaining)) { toast('Please fill in all stock fields.', 'error'); return; }
    if (remaining > initial) { toast('Remaining cannot exceed initial stock.', 'error'); return; }

    try {
        const action = id ? 'update' : 'add';
        const res = await post(action, { id, product_id: productId, initial_stock: initial, remaining, low_stock_threshold: threshold });
        if (res.success) {
            editModal.hide();
            toast('Inventory saved successfully.', 'success');
            await loadInventory(true);
            await loadStats();
        } else {
            toast(res.error || 'Save failed.', 'error');
        }
    } catch(e) { toast('Network error.', 'error'); }
}

function openRestockModal(id, name) {
    document.getElementById('restockId').value = id;
    document.getElementById('restockName').textContent = 'Restocking: ' + name;
    document.getElementById('restockAmount').value = '';
    restockModal.show();
}

async function doRestock() {
    const id     = document.getElementById('restockId').value;
    const amount = parseInt(document.getElementById('restockAmount').value);
    if (!amount || amount < 1) { toast('Enter a valid amount.', 'error'); return; }
    try {
        const res = await post('restock', { id, amount });
        if (res.success) {
            restockModal.hide();
            toast(`Added ${amount} units to stock.`, 'success');
            await loadInventory(true);
            await loadStats();
        }
    } catch(e) { toast('Network error.', 'error'); }
}

function openDeleteModal(id, name) {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteName').textContent = name;
    deleteModal.show();
}

async function doDelete() {
    const id = document.getElementById('deleteId').value;
    try {
        const res = await post('delete', { id });
        if (res.success) {
            deleteModal.hide();
            toast('Item removed from inventory.', 'success');
            await loadInventory(true);
            await loadStats();
        }
    } catch(e) { toast('Network error.', 'error'); }
}

// ── UTILS ──────────────────────────────────────────────────
function esc(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function debounce(fn, ms) {
    let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
}

let toastTimer;
function toast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = type;
    el.style.display = 'block';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { el.style.display = 'none'; }, 3000);
}
</script>
</body>
</html>
