<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="assets/Logo.png" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <title>Chick Chicken - Sales Report</title>

    <style>
        * { margin: 0; box-sizing: border-box; list-style-type: none; text-decoration: none; }

        :root {
            --mustard: #FFDE59;
            --mustard-dark: #e0b300;
            --red: #FF0000;
            --black: #000;
            --wine-red: #9A0404;
            --primary: #ec994b;
            --white: #ffffff;
        }

        html, body {
            display: block; padding: 0; margin: 0;
            height: auto; overflow-x: hidden;
            font-family: "Oswald", sans-serif;
            background: #f4f6f8;
        }

        /* ── SIDEBAR ── */
        header {
            display: grid; padding: 40px 20px; height: 100%;
            width: 220px; align-content: baseline; position: fixed;
            background-color: var(--mustard); z-index: 500;
        }
        .logo { justify-content: center; align-self: center; margin-bottom: 30px; }
        nav ul { padding-left: 0; margin: 0; }
        nav ul li { list-style: none; margin-bottom: 15px; }
        .header_button {
            display: flex; align-items: center; gap: 15px;
            padding: 12px 20px; border-radius: 20px 0 0 20px;
            color: var(--white); background-color: transparent;
            font-size: 22px; font-family: "Oswald", sans-serif;
            font-weight: 500; text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .header_button ion-icon { font-size: 26px; }
        .header_button:hover, .header_button.active {
            background-color: var(--white); color: var(--mustard);
        }

        /* ── MAIN ── */
        .main-content { margin-left: 220px; padding: 40px; }

        /* ── SUMMARY CARDS ── */
        .summary-cards {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 20px; margin-bottom: 30px;
        }
        .sum-card {
            background: #fff; border-radius: 16px; padding: 22px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.10);
            display: flex; justify-content: space-between; align-items: center;
            transition: 0.2s ease;
        }
        .sum-card:hover { transform: scale(1.03); }
        .sum-card-info h4 { font-size: 15px; font-weight: 500; color: #777; margin-bottom: 4px; }
        .sum-card-info h2 { font-size: 28px; font-weight: 700; color: #111; }
        .sum-card-info .sub { font-size: 12px; color: #aaa; margin-top: 3px; }
        .sum-card-icon ion-icon { font-size: 40px; color: var(--mustard); }

        /* ── FILTER BAR ── */
        .filter-bar {
            background: #fff; border-radius: 16px; padding: 20px 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 28px;
            display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;
        }
        .filter-bar label { font-size: 13px; font-weight: 600; color: #555; display: block; margin-bottom: 5px; }
        .filter-bar input, .filter-bar select {
            font-family: "Oswald", sans-serif; font-size: 15px;
            border: 1.5px solid #e0e0e0; border-radius: 10px;
            padding: 8px 14px; outline: none; background: #fafafa;
            transition: border 0.2s;
        }
        .filter-bar input:focus, .filter-bar select:focus { border-color: var(--mustard-dark); }
        .btn-filter {
            background: var(--mustard); color: #111; border: none;
            border-radius: 10px; padding: 9px 22px; font-family: "Oswald", sans-serif;
            font-size: 15px; font-weight: 600; cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-filter:hover { background: var(--mustard-dark); transform: scale(1.02); }
        .btn-reset {
            background: #f0f0f0; color: #555; border: none;
            border-radius: 10px; padding: 9px 18px; font-family: "Oswald", sans-serif;
            font-size: 15px; cursor: pointer; transition: background 0.2s;
        }
        .btn-reset:hover { background: #e0e0e0; }
        .btn-export {
            background: #111; color: #fff; border: none;
            border-radius: 10px; padding: 9px 20px; font-family: "Oswald", sans-serif;
            font-size: 15px; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; gap: 7px;
            transition: background 0.2s;
        }
        .btn-export:hover { background: #333; }

        /* ── CHARTS ROW ── */
        .charts-row { display: grid; grid-template-columns: 2fr 1fr; gap: 22px; margin-bottom: 28px; }
        .chart-box {
            background: #fff; border-radius: 16px; padding: 22px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .chart-box h3 { font-size: 18px; font-weight: 600; margin-bottom: 16px; }
        .chart-wrapper { position: relative; height: 220px; width: 100%; }

        /* ── TABLE ── */
        .table-box {
            background: #fff; border-radius: 16px; padding: 22px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow-x: auto;
        }
        .table-box-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 16px; flex-wrap: wrap; gap: 10px;
        }
        .table-box-header h3 { font-size: 18px; font-weight: 600; }
        .result-count { font-size: 13px; color: #999; }

        table { width: 100%; border-collapse: collapse; font-family: "Oswald", sans-serif; }
        thead tr th {
            background: var(--mustard); color: #111;
            padding: 12px 14px; text-align: left;
            font-size: 14px; font-weight: 700; letter-spacing: 0.3px;
        }
        thead tr th:first-child { border-radius: 10px 0 0 0; }
        thead tr th:last-child { border-radius: 0 10px 0 0; }

        tbody tr { transition: background 0.15s; }
        tbody tr:nth-child(even) { background: #fafafa; }
        tbody tr:hover { background: #fff8d6; }
        tbody td {
            padding: 11px 14px; font-size: 14px; color: #333;
            border-bottom: 1px solid #f0f0f0;
        }
        .order-id { font-weight: 700; color: #111; }

        /* status pills */
        .badge-status {
            font-size: 11px; font-weight: 800; padding: 3px 10px;
            border-radius: 20px; text-transform: capitalize; white-space: nowrap;
        }
        .s-pending    { background: #fff8e1; color: #e65c00; }
        .s-confirmed  { background: #e8f5e9; color: #2e7d32; }
        .s-cooking    { background: #e3f2fd; color: #1565c0; }
        .s-in_transit { background: #f3e5f5; color: #6a1b9a; }
        .s-completed  { background: #e8f5e9; color: #1b5e20; }
        .s-cancelled  { background: #fce4ec; color: #c62828; }

        /* payment pills */
        .badge-pay {
            font-size: 11px; font-weight: 700; padding: 3px 10px;
            border-radius: 20px; text-transform: uppercase;
        }
        .p-gcash { background: #e8eaf6; color: #283593; }
        .p-cod   { background: #f3e5f5; color: #4a148c; }

        /* discount badge */
        .badge-disc {
            font-size: 11px; font-weight: 700; padding: 3px 9px;
            border-radius: 20px; background: #fff3e0; color: #e65c00;
        }

        /* pagination */
        .pagination-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 18px; flex-wrap: wrap; gap: 10px;
        }
        .page-info { font-size: 13px; color: #999; }
        .page-btns { display: flex; gap: 6px; }
        .page-btn {
            background: #f0f0f0; border: none; border-radius: 8px;
            padding: 6px 14px; font-family: "Oswald", sans-serif; font-size: 14px;
            cursor: pointer; transition: background 0.2s;
        }
        .page-btn:hover, .page-btn.active { background: var(--mustard); color: #111; font-weight: 700; }
        .page-btn:disabled { opacity: 0.4; cursor: default; }

        /* skeleton */
        .skeleton {
            display: inline-block; width: 90px; height: 1.2em;
            background: linear-gradient(90deg, #e0e0e0 25%, #f5f5f5 50%, #e0e0e0 75%);
            background-size: 200% 100%; animation: shimmer 1.2s infinite;
            border-radius: 4px; vertical-align: middle;
        }
        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        .empty-state { text-align: center; padding: 50px 0; color: #bbb; font-size: 18px; }

        /* DB error */
        .db-error {
            background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px;
            padding: 12px 16px; font-size: 13px; color: #856404;
            margin-bottom: 18px; display: none;
        }

        @media (max-width: 1100px) {
            .summary-cards { grid-template-columns: repeat(2, 1fr); }
            .charts-row { grid-template-columns: 1fr; }
        }
        @media (max-width: 700px) {
            .main-content { margin-left: 0; padding: 20px; }
            header { display: none; }
            .summary-cards { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <h1><a href="admin.php"><img src="assets/Logo2.png" alt="ChickChicken" style="width:auto;height:55px"/></a></h1>
    </div>
    <div class="navigation--admin">
        <nav>
            <ul>
                <li><a href="admin.php" class="header_button"><ion-icon name="grid-outline"></ion-icon><span>Dashboard</span></a></li>
                 <li><a href="sales-report.php" class="header_button active"><ion-icon name="bar-chart-outline"></ion-icon><span>Sales Report</span></a></li>
                <li><a href="orders--admin.php" class="header_button"><ion-icon name="bag-handle-outline"></ion-icon><span>Orders</span></a></li>
                <li><a href="menu--admin.php" class="header_button"><ion-icon name="book-outline"></ion-icon><span>Menus</span></a></li>
                <li><a href="inventory.php" class="header_button"><ion-icon name="clipboard-outline"></ion-icon><span>Inventory</span></a></li>
                <li><a href="admin-discount.php" class="header_button"><ion-icon name="pricetag-outline"></ion-icon><span>Discounts</span></a></li>
                <li><a href="admins-review.php" class="header_button"><ion-icon name="chatbubbles-outline"></ion-icon><span>Reviews</span></a></li>
                <div style="margin-top: auto; padding: 20px 0 10px;">
                    <a href="admin_logout.php" class="header_button" style="color:#c62828;"
                    onclick="return confirm('Are you sure you want to log out?')">
                        <ion-icon name="log-out-outline"></ion-icon>
                        <span>Log Out</span>
                    </a>
                </div>
            </ul>
        </nav>
    </div>
</header>

<main class="main-content">
    <h1 style="margin-bottom:25px;">Sales Report</h1>

    <div class="db-error" id="dbError">
        ⚠️ Could not connect to the database. Make sure XAMPP is running and <code>sales_report_process.php</code> is configured.
    </div>

    <!-- SUMMARY CARDS -->
    <div class="summary-cards">
        <div class="sum-card">
            <div class="sum-card-info">
                <h4>Total Revenue</h4>
                <h2 id="sumRevenue"><span class="skeleton"></span></h2>
                <div class="sub" id="sumRevenueSub">&nbsp;</div>
            </div>
            <div class="sum-card-icon"><ion-icon name="cash-outline"></ion-icon></div>
        </div>
        <div class="sum-card">
            <div class="sum-card-info">
                <h4>Total Transactions</h4>
                <h2 id="sumTx"><span class="skeleton"></span></h2>
                <div class="sub" id="sumTxSub">&nbsp;</div>
            </div>
            <div class="sum-card-icon"><ion-icon name="receipt-outline"></ion-icon></div>
        </div>
        <div class="sum-card">
            <div class="sum-card-info">
                <h4>Avg. Order Value</h4>
                <h2 id="sumAvg"><span class="skeleton"></span></h2>
                <div class="sub" id="sumAvgSub">&nbsp;</div>
            </div>
            <div class="sum-card-icon"><ion-icon name="trending-up-outline"></ion-icon></div>
        </div>
        <div class="sum-card">
            <div class="sum-card-info">
                <h4>Discount Savings</h4>
                <h2 id="sumDisc"><span class="skeleton"></span></h2>
                <div class="sub" id="sumDiscSub">&nbsp;</div>
            </div>
            <div class="sum-card-icon"><ion-icon name="pricetag-outline"></ion-icon></div>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar">
        <div>
            <label>From Date</label>
            <input type="date" id="filterFrom" />
        </div>
        <div>
            <label>To Date</label>
            <input type="date" id="filterTo" />
        </div>
        <div>
            <label>Status</label>
            <select id="filterStatus">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cooking">Cooking</option>
                <option value="in_transit">In Transit</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div>
            <label>Payment</label>
            <select id="filterPayment">
                <option value="">All Methods</option>
                <option value="gcash">GCash</option>
                <option value="cod">COD</option>
            </select>
        </div>
        <div>
            <label>Branch</label>
            <select id="filterBranch">
                <option value="">All Branches</option>
            </select>
        </div>
        <div>
            <label>Discount</label>
            <select id="filterDiscount">
                <option value="">All</option>
                <option value="yes">With Discount</option>
                <option value="no">No Discount</option>
            </select>
        </div>
        <div style="display:flex;gap:8px;align-items:flex-end;">
            <button class="btn-filter" onclick="applyFilters()">
                <ion-icon name="search-outline" style="vertical-align:middle;margin-right:4px;"></ion-icon>Apply
            </button>
            <button class="btn-reset" onclick="resetFilters()">Reset</button>
            <button class="btn-export" onclick="exportCSV()">
                <ion-icon name="download-outline"></ion-icon>Export CSV
            </button>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="charts-row">
        <div class="chart-box">
            <h3>Revenue Over Time <small style="font-size:12px;color:#aaa;font-weight:400;" id="chartRangeLabel"></small></h3>
            <div class="chart-wrapper"><canvas id="revenueChart"></canvas></div>
        </div>
        <div class="chart-box">
            <h3>Payment Method Split</h3>
            <div class="chart-wrapper"><canvas id="paymentChart"></canvas></div>
        </div>
    </div>

    <!-- TRANSACTIONS TABLE -->
    <div class="table-box">
        <div class="table-box-header">
            <h3>Transactions</h3>
            <span class="result-count" id="resultCount">Loading…</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date & Time</th>
                    <th>Customer</th>
                    <th>Branch</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Discount</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody id="txBody">
                <tr><td colspan="8"><span class="skeleton" style="width:100%;display:block;height:18px;"></span></td></tr>
                <tr><td colspan="8"><span class="skeleton" style="width:80%;display:block;height:18px;"></span></td></tr>
                <tr><td colspan="8"><span class="skeleton" style="width:90%;display:block;height:18px;"></span></td></tr>
            </tbody>
        </table>
        <div class="pagination-row">
            <div class="page-info" id="pageInfo"></div>
            <div class="page-btns" id="pageBtns"></div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

<script>
// ── CONFIG ────────────────────────────────────────────────
const API = 'sales_report_process.php';
const PAGE_SIZE = 15;

let allData      = [];   // full filtered dataset from API
let currentPage  = 1;
let revenueChart = null;
let paymentChart = null;

// ── HELPERS ───────────────────────────────────────────────
async function apiFetch(params = {}) {
    const url = new URL(API, location.href);
    Object.entries(params).forEach(([k, v]) => { if (v !== '') url.searchParams.set(k, v); });
    const res = await fetch(url);
    if (!res.ok) throw new Error('HTTP ' + res.status);
    return res.json();
}

function fmtMoney(n) {
    return '₱' + Number(n || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function fmtDate(ts) {
    const d = new Date(ts);
    return d.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' })
         + ' ' + d.toLocaleTimeString('en-PH', { hour: '2-digit', minute: '2-digit' });
}

function getFilters() {
    return {
        from:     document.getElementById('filterFrom').value,
        to:       document.getElementById('filterTo').value,
        status:   document.getElementById('filterStatus').value,
        payment:  document.getElementById('filterPayment').value,
        branch:   document.getElementById('filterBranch').value,
        discount: document.getElementById('filterDiscount').value,
    };
}

function showError() { document.getElementById('dbError').style.display = 'block'; }

// ── SET DEFAULT DATE RANGE (last 30 days) ─────────────────
function setDefaultDates() {
    const today = new Date();
    const from  = new Date();
    from.setDate(today.getDate() - 29);

    const fmt = d => {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    };

    document.getElementById('filterTo').value   = fmt(today);
    document.getElementById('filterFrom').value = fmt(from);
}

// ── SUMMARY CARDS ─────────────────────────────────────────
function renderSummary(data) {
    const completed = data.filter(r => r.status === 'completed');
    const totalRev  = completed.reduce((s, r) => s + parseFloat(r.total || 0), 0);
    const totalTx   = data.length;
    const avg       = totalTx ? totalRev / completed.length || 0 : 0;
    const discSaved = data.reduce((s, r) => s + parseFloat(r.discount_amount || 0), 0);

    document.getElementById('sumRevenue').textContent = fmtMoney(totalRev);
    document.getElementById('sumTx').textContent      = totalTx.toLocaleString();
    document.getElementById('sumAvg').textContent     = fmtMoney(avg);
    document.getElementById('sumDisc').textContent    = fmtMoney(discSaved);

    document.getElementById('sumRevenueSub').textContent  = `${completed.length} completed orders`;
    document.getElementById('sumTxSub').textContent       = `across all statuses`;
    document.getElementById('sumAvgSub').textContent      = `per completed order`;
    document.getElementById('sumDiscSub').textContent     = `total discount given`;
}

// ── CHARTS ────────────────────────────────────────────────
function renderCharts(data) {
    // Revenue by day
    const dayMap = {};
    data.filter(r => r.status === 'completed').forEach(r => {
        const day = r.created_at ? r.created_at.split('T')[0].split(' ')[0] : '';
        if (!day) return;
        dayMap[day] = (dayMap[day] || 0) + parseFloat(r.total || 0);
    });
    const days = Object.keys(dayMap).sort();
    const revs = days.map(d => dayMap[d]);

    document.getElementById('chartRangeLabel').textContent =
        days.length ? `(${days[0]} → ${days[days.length-1]})` : '';

    const ctx1 = document.getElementById('revenueChart').getContext('2d');
    if (revenueChart) revenueChart.destroy();
    revenueChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: days.map(d => new Date(d).toLocaleDateString('en-PH', { month: 'short', day: 'numeric' })),
            datasets: [{ label: 'Revenue (₱)', data: revs,
                backgroundColor: 'rgba(255,222,89,0.8)', borderColor: '#e0b300',
                borderWidth: 2, borderRadius: 6 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false },
                tooltip: { callbacks: { label: c => fmtMoney(c.parsed.y) } } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => '₱' + v.toLocaleString() }, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Payment split doughnut
    const gcash = data.filter(r => r.payment_method === 'gcash').length;
    const cod   = data.filter(r => r.payment_method === 'cod').length;
    const ctx2  = document.getElementById('paymentChart').getContext('2d');
    if (paymentChart) paymentChart.destroy();
    paymentChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['GCash', 'COD'],
            datasets: [{ data: [gcash, cod],
                backgroundColor: ['#3949ab', '#8e24aa'],
                borderWidth: 0 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { font: { family: 'Oswald', size: 13 } } },
                tooltip: { callbacks: { label: c => `${c.label}: ${c.parsed} orders` } }
            },
            cutout: '65%'
        }
    });
}

// ── TABLE RENDER ──────────────────────────────────────────
function renderTable() {
    const start = (currentPage - 1) * PAGE_SIZE;
    const page  = allData.slice(start, start + PAGE_SIZE);
    const body  = document.getElementById('txBody');

    if (!allData.length) {
        body.innerHTML = `<tr><td colspan="8" class="empty-state">No transactions found for the selected filters.</td></tr>`;
        document.getElementById('resultCount').textContent = '0 results';
        document.getElementById('pageInfo').textContent = '';
        document.getElementById('pageBtns').innerHTML = '';
        return;
    }

    document.getElementById('resultCount').textContent = `${allData.length.toLocaleString()} result${allData.length !== 1 ? 's' : ''}`;

    body.innerHTML = page.map(r => {
        const discount = parseFloat(r.discount_amount || 0);
        const discBadge = discount > 0
            ? `<span class="badge-disc">-${fmtMoney(discount)}</span>`
            : `<span style="color:#ccc;font-size:12px;">—</span>`;
        return `
        <tr>
            <td><span class="order-id">#${String(r.id).padStart(7,'0')}</span></td>
            <td>${fmtDate(r.created_at)}</td>
            <td>
                <div style="font-weight:600;">${escHtml(r.name)}</div>
                <div style="font-size:12px;color:#999;">${escHtml(r.phone)}</div>
            </td>
            <td style="font-size:13px;">${escHtml(r.branch || '—')}</td>
            <td><span class="badge-pay p-${r.payment_method}">${r.payment_method}</span></td>
            <td><span class="badge-status s-${r.status}">${r.status.replace('_',' ')}</span></td>
            <td>${discBadge}</td>
            <td style="text-align:right;font-weight:700;">${fmtMoney(r.total)}</td>
        </tr>`;
    }).join('');

    renderPagination();
}

function renderPagination() {
    const total = Math.ceil(allData.length / PAGE_SIZE);
    const info  = document.getElementById('pageInfo');
    const btns  = document.getElementById('pageBtns');
    const start = (currentPage - 1) * PAGE_SIZE + 1;
    const end   = Math.min(currentPage * PAGE_SIZE, allData.length);

    info.textContent = `Showing ${start}–${end} of ${allData.length.toLocaleString()}`;

    let html = `<button class="page-btn" onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''}>‹</button>`;
    for (let i = 1; i <= total; i++) {
        if (total > 7 && i > 3 && i < total - 1 && Math.abs(i - currentPage) > 1) {
            if (i === 4 || i === total - 2) html += `<span style="padding:6px 4px;color:#aaa;">…</span>`;
            continue;
        }
        html += `<button class="page-btn ${i===currentPage?'active':''}" onclick="goPage(${i})">${i}</button>`;
    }
    html += `<button class="page-btn" onclick="goPage(${currentPage+1})" ${currentPage===total?'disabled':''}>›</button>`;
    btns.innerHTML = html;
}

function goPage(n) {
    const total = Math.ceil(allData.length / PAGE_SIZE);
    if (n < 1 || n > total) return;
    currentPage = n;
    renderTable();
    document.querySelector('.table-box').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function escHtml(s) {   
    return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// ── LOAD DATA ─────────────────────────────────────────────
async function loadData() {
    const filters = getFilters();
    try {
        const res = await apiFetch({ action: 'sales_report', ...filters });
        allData = res.transactions || [];
        currentPage = 1;

        renderSummary(allData);
        renderCharts(allData);
        renderTable();

        // Populate branch dropdown from data if not already done
        const branchSel = document.getElementById('filterBranch');
        if (branchSel.options.length === 1) {
            const branches = [...new Set(allData.map(r => r.branch).filter(Boolean))].sort();
            branches.forEach(b => {
                const opt = document.createElement('option');
                opt.value = b; opt.textContent = b;
                branchSel.appendChild(opt);
            });
        }
    } catch (e) {
        console.error('Sales report error:', e);
        showError();
        // Demo fallback with sample data for preview
        loadDemoData();
    }
}

// ── DEMO DATA (for preview without backend) ───────────────
function loadDemoData() {
    const statuses = ['pending','confirmed','cooking','in_transit','completed','completed','completed'];
    const payments = ['gcash','cod'];
    const branches = ['Chick Chicken - Amang Rodriguez Pasig','Chick Chicken - Ortigas Pasig','Chick Chicken - Shaw Mandaluyong'];
    const names    = ['Maria Santos','Juan dela Cruz','Ana Reyes','Carlo Mendoza','Luz Garcia','Pedro Bautista','Rosa Lim'];

    allData = Array.from({ length: 62 }, (_, i) => {
        const d = new Date(); d.setDate(d.getDate() - Math.floor(Math.random() * 30));
        const total    = (Math.random() * 900 + 100).toFixed(2);
        const discount = Math.random() > 0.7 ? (Math.random() * 80 + 10).toFixed(2) : '0.00';
        return {
            id:              i + 1,
            created_at:      d.toISOString().replace('T', ' ').slice(0, 19),
            name:            names[i % names.length],
            phone:           '09' + String(Math.floor(Math.random()*1e9)).padStart(9,'0'),
            branch:          branches[i % branches.length],
            payment_method:  payments[i % 2],
            status:          statuses[i % statuses.length],
            discount_amount: discount,
            total:           total,
        };
    });

    // Populate branches
    const branchSel = document.getElementById('filterBranch');
    if (branchSel.options.length === 1) {
        const bs = [...new Set(allData.map(r => r.branch))].sort();
        bs.forEach(b => { const o = document.createElement('option'); o.value = b; o.textContent = b; branchSel.appendChild(o); });
    }

    currentPage = 1;
    renderSummary(allData);
    renderCharts(allData);
    renderTable();
}

// ── FILTER / RESET ────────────────────────────────────────
function applyFilters() { loadData(); }

function resetFilters() {
    setDefaultDates();
    document.getElementById('filterStatus').value   = '';
    document.getElementById('filterPayment').value  = '';
    document.getElementById('filterBranch').value   = '';
    document.getElementById('filterDiscount').value = '';
    loadData();
}

// ── EXPORT CSV ────────────────────────────────────────────
function exportCSV() {
    if (!allData.length) return alert('No data to export.');
    const headers = ['Order ID','Date','Customer','Phone','Branch','Payment','Status','Discount (₱)','Total (₱)'];
    const rows = allData.map(r => [
        '#' + String(r.id).padStart(7,'0'),
        r.created_at,
        r.name, r.phone, r.branch, r.payment_method, r.status,
        parseFloat(r.discount_amount || 0).toFixed(2),
        parseFloat(r.total || 0).toFixed(2),
    ]);
    const csv = [headers, ...rows].map(r => r.map(v => `"${String(v).replace(/"/g,'""')}"`).join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const a    = document.createElement('a');
    a.href     = URL.createObjectURL(blob);
    a.download = `sales-report-${new Date().toISOString().split('T')[0]}.csv`;
    a.click();
}

// ── INIT ─────────────────────────────────────────────────
setDefaultDates();
loadData();
</script>
</body>
</html>