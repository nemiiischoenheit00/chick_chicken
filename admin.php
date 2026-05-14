<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="assets/Logo.png" />
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="admin.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Chart.js for Sales Overview -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <title>Chick Chicken - Admin</title>

    <style>
        .skeleton {
            display: inline-block;
            width: 90px;
            height: 1.2em;
            background: linear-gradient(90deg, #e0e0e0 25%, #f5f5f5 50%, #e0e0e0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite;
            border-radius: 4px;
            vertical-align: middle;
        }
        @keyframes shimmer {
            0%   { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* ── Change badge ── */
        .change-badge {
            font-size: 13px;
            font-weight: 600;
        }
        .change-badge.up   { color: #2e7d32; }
        .change-badge.down { color: #c62828; }
        .change-badge.flat { color: #888; }

        /* ── Chart wrapper ── */
        .chart-wrapper {
            position: relative;
            height: 200px;
            width: 100%;
        }

        /* ── Recent orders list ── */
        .recent-orders li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f2f2f2;
            font-size: 14px;
        }
        .recent-orders li:last-child { border-bottom: none; }
        .recent-orders .order-id { font-weight: 700; color: #333; }
        .order-status {
            font-size: 11px;
            font-weight: 800;
            padding: 3px 9px;
            border-radius: 20px;
            text-transform: capitalize;
        }
        .status-pending   { background: #fff8e1; color: #e65c00; }
        .status-confirmed { background: #e8f5e9; color: #2e7d32; }
        .status-cancelled { background: #fce4ec; color: #c62828; }

        /* ── Error state ── */
        .db-error {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            color: #856404;
            margin-bottom: 18px;
            display: none;
        }
    </style>
</head>

<body>
    <header>
        <div class="sidebar">
            <div class="logo">
                <h1>
                    <a href="admin.php"><img src="assets/Logo2.png" alt="ChickChicken"
                            style="width: auto; height: 55px" /></a>
                </h1>
            </div>
            <div class="navigation--admin">
                <nav>
                    <ul>
                        <li>
                            <a href="#dashboard--admin" class="header_button active">
                                <ion-icon name="grid-outline"></ion-icon>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="orders--admin.php" class="header_button">
                                <ion-icon name="bag-handle-outline"></ion-icon>
                                <span>Orders</span>
                            </a>
                        </li>
                        <li>
                            <a href="menu--admin.php" class="header_button">
                                <ion-icon name="book-outline"></ion-icon>
                                <span>Menus</span>
                            </a>
                        </li>
                        <li>
                            <a href="inventory.php" class="header_button">
                                <ion-icon name="clipboard-outline"></ion-icon>
                                <span>Inventory</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="main-content">
        <section id="dashboard--admin" class="page-content active">
            <h1 style="margin-bottom: 25px;">Dashboard</h1>

            <!-- DB error banner (shown if API fails) -->
            <div class="db-error" id="dbError">
                ⚠️ Could not connect to the database. Make sure XAMPP is running and <code>db.php</code> is configured.
            </div>

            <!-- ── STAT CARDS ── -->
            <div class="dashboard-cards">

                <div class="dash-card">
                    <div class="dash-card-info">
                        <h3>Total Orders</h3>
                        <h2 id="statOrders"><span class="skeleton"></span></h2>
                        <p id="statOrdersChange" class="change-badge"></p>
                    </div>
                    <div class="dash-card-icon">
                        <ion-icon name="bag-handle-outline"></ion-icon>
                    </div>
                </div>

                <div class="dash-card">
                    <div class="dash-card-info">
                        <h3>Customers</h3>
                        <h2 id="statCustomers"><span class="skeleton"></span></h2>
                        <p id="statCustomersChange" class="change-badge"></p>
                    </div>
                    <div class="dash-card-icon">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div>

                <div class="dash-card">
                    <div class="dash-card-info">
                        <h3>Revenue</h3>
                        <h2 id="statRevenue"><span class="skeleton"></span></h2>
                        <p id="statRevenueChange" class="change-badge"></p>
                    </div>
                    <div class="dash-card-icon">
                        <ion-icon name="cash-outline"></ion-icon>
                    </div>
                </div>

                <div class="dash-card">
                    <div class="dash-card-info">
                        <h3>Pending Orders</h3>
                        <h2 id="statPending"><span class="skeleton"></span></h2>
                        <p id="statPendingChange" class="change-badge"></p>
                    </div>
                    <div class="dash-card-icon">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                    </div>
                </div>

            </div>

            <!-- ── BOTTOM ROW ── -->
            <div class="dashboard-bottom">

                <div class="dash-box">
                    <h3>Sales Overview <small style="font-size:12px;color:#aaa;font-weight:400;">(last 7 days)</small></h3>
                    <div class="chart-wrapper">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <div class="dash-box">
                    <h3>Recent Orders</h3>
                    <ul class="recent-orders" id="recentOrdersList">
                        <li><span class="skeleton" style="width:100%;"></span></li>
                        <li><span class="skeleton" style="width:100%;"></span></li>
                        <li><span class="skeleton" style="width:100%;"></span></li>
                        <li><span class="skeleton" style="width:100%;"></span></li>
                    </ul>
                </div>

            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="admin.js"></script>

    <script>
    // ── CONFIG ──────────────────────────────────────────────
    const API = 'dashboard.php';
    let salesChartInstance = null;

    // ── HELPERS ─────────────────────────────────────────────
    function changeBadge(pct, elId) {
        const el = document.getElementById(elId);
        if (pct === 0) {
            el.textContent = '→ No change this week';
            el.className = 'change-badge flat';
        } else if (pct > 0) {
            el.textContent = `↑ ${pct}% this week`;
            el.className = 'change-badge up';
        } else {
            el.textContent = `↓ ${Math.abs(pct)}% this week`;
            el.className = 'change-badge down';
        }
    }

    function showError() {
        document.getElementById('dbError').style.display = 'block';
    }

    async function apiFetch(action) {
        const res = await fetch(`${API}?action=${action}`);
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    }

    // ── LOAD STAT CARDS ─────────────────────────────────────
    async function loadStats() {
        try {
            const [orders, customers, revenue, pending] = await Promise.all([
                apiFetch('total_orders'),
                apiFetch('total_customers'),
                apiFetch('revenue'),
                apiFetch('pending_orders'),
            ]);

            document.getElementById('statOrders').textContent    = Number(orders.total).toLocaleString();
            document.getElementById('statCustomers').textContent = Number(customers.total).toLocaleString();
            document.getElementById('statRevenue').textContent   = '₱' + Number(revenue.total).toLocaleString('en-PH', {minimumFractionDigits: 2});
            document.getElementById('statPending').textContent   = Number(pending.total).toLocaleString();

            changeBadge(orders.change,    'statOrdersChange');
            changeBadge(customers.change, 'statCustomersChange');
            changeBadge(revenue.change,   'statRevenueChange');
            changeBadge(pending.change,   'statPendingChange');

        } catch (e) {
            console.error('Stats error:', e);
            showError();
        }
    }

    // ── RECENT ORDERS ────────────────────────────────────────
    async function loadRecentOrders() {
        try {
            const orders = await apiFetch('recent_orders');
            const ul = document.getElementById('recentOrdersList');
            if (!orders.length) {
                ul.innerHTML = '<li style="color:#aaa;">No orders yet.</li>';
                return;
            }
            ul.innerHTML = orders.map(o => `
                <li>
                    <span>
                        <span class="order-id">#${String(o.id).padStart(7, '0')}</span>
                        &nbsp;· ₱${Number(o.total).toLocaleString('en-PH', {minimumFractionDigits: 2})}
                    </span>
                    <span class="order-status status-${o.status}">${o.status}</span>
                </li>
            `).join('');
        } catch (e) {
            console.error('Recent orders error:', e);
        }
    }

    // ── SALES CHART ──────────────────────────────────────────
    async function loadSalesChart() {
        try {
            const data = await apiFetch('sales_overview');

            // Fill in missing days in the last 7 days
            const days = [];
            const revenues = [];
            for (let i = 6; i >= 0; i--) {
                const d = new Date();
                d.setDate(d.getDate() - i);
                const key = `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
                const found = data.find(r => r.day === key);
                days.push(d.toLocaleDateString('en-PH', { weekday: 'short', month: 'short', day: 'numeric' }));
                revenues.push(found ? parseFloat(found.revenue) : 0);
            }

            const ctx = document.getElementById('salesChart').getContext('2d');
            if (salesChartInstance) salesChartInstance.destroy();

            salesChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: revenues,
                        backgroundColor: 'rgba(245, 200, 0, 0.75)',
                        borderColor: '#e0b300',
                        borderWidth: 2,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => '₱' + ctx.parsed.y.toLocaleString('en-PH', { minimumFractionDigits: 2 })
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: v => '₱' + v.toLocaleString()
                            },
                            grid: { color: '#f0f0f0' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        } catch (e) {
            console.error('Chart error:', e);
        }
    }

    // ── INIT ─────────────────────────────────────────────────
    loadStats();
    loadRecentOrders();
    loadSalesChart();

    // Auto-refresh every 60 seconds
    setInterval(() => {
        loadStats();
        loadRecentOrders();
        loadSalesChart();
    }, 60000);
    </script>

</body>
</html>