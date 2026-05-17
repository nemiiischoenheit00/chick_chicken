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

        /* ── Tabs ───────────────────────────────────────── */
        .inv-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 28px;
            border-bottom: 2px solid #e8e8e8;
        }
        .inv-tab-btn {
            font-family: 'Oswald', sans-serif;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 10px 28px;
            border: none;
            background: none;
            color: #aaa;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: color .2s, border-color .2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .inv-tab-btn:hover { color: #1a1a1a; }
        .inv-tab-btn.active {
            color: #1a1a1a;
            border-bottom-color: #f5c800;
        }
        .inv-tab-panel { display: none; }
        .inv-tab-panel.active { display: block; }

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
            background: #FFDE59;
            border-bottom: 2px solid rgba(0,0,0,.1);
        }
        .inv-table thead th {
            padding: 14px 18px;
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #111111;
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
        .prod-name { font-weight: 700; color: #1a1a1a; }
        .prod-cat  { font-size: 11px; color: #bbb; margin-top: 2px; }

        /* ── Stock bar ────────────────────────────────── */
        .stock-bar-wrap { display: flex; align-items: center; gap: 8px; }
        .stock-bar-bg   { flex: 1; height: 7px; background: #f0f0f0; border-radius: 99px; overflow: hidden; }
        .stock-bar-fill { height: 100%; border-radius: 99px; transition: width .4s; }
        .fill-ok  { background: #4caf50; }
        .fill-low { background: #ff9800; }
        .fill-out { background: #e53935; }
        .stock-pct { font-size: 12px; font-weight: 700; color: #666; width: 36px; text-align: right; }

        /* ── Badges ──────────────────────────────────── */
        .status-badge {
            display: inline-block; padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px;
        }
        .badge-ok  { background: #e8f5e9; color: #2e7d32; }
        .badge-low { background: #fff3e0; color: #e65c00; }
        .badge-out { background: #fce4ec; color: #c62828; }

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
        .btn-link    { background: #f3e5f5; color: #6a1b9a; }

        /* ── Empty / loading states ─────────────────── */
        .inv-empty { text-align: center; padding: 50px 20px; color: #bbb; font-size: 14px; }
        .inv-empty ion-icon { font-size: 40px; display: block; margin: 0 auto 10px; }
        .skeleton {
            display: inline-block;
            background: linear-gradient(90deg,#eee 25%,#f8f8f8 50%,#eee 75%);
            background-size: 200% 100%; animation: shimmer 1.2s infinite;
            border-radius: 4px; height: 1em;
        }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

        /* ── Modal tweaks ────────────────────────────── */
        .modal-header { background: #1a1a1a; color: #fff; border-radius: 12px 12px 0 0; }
        .modal-header .btn-close { filter: invert(1); }
        .modal-title { font-family: 'Oswald', sans-serif; font-size: 1.2rem; font-weight: 600; }
        .modal-content { border-radius: 14px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,.18); }
        .modal-footer { border-top: 1px solid #f0f0f0; }
        .form-label { font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; color: #555; }
        .form-control, .form-select { border: 1.5px solid #e8e8e8; border-radius: 9px; font-size: 14px; transition: border .2s; }
        .form-control:focus, .form-select:focus { border-color: #f5c800; box-shadow: 0 0 0 3px rgba(245,200,0,.15); }
        .btn-save { background: #f5c800; border: none; color: #1a1a1a; font-weight: 800; border-radius: 9px; padding: 10px 24px; }
        .btn-save:hover { background: #e0b300; }
        .btn-cancel { border-radius: 9px; }

        /* ── Ingredient link rows ─────────────────────── */
        .ing-link-row {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 0; border-bottom: 1px solid #f5f5f5;
        }
        .ing-link-row:last-child { border-bottom: none; }
        .ing-link-row select, .ing-link-row input { flex: 1; }
        /* FIX: btn-remove-ing no longer shares the ing-link-row class */
        .btn-remove-ing {
            background: #fce4ec; color: #c62828; border: none;
            border-radius: 8px; padding: 6px 10px; cursor: pointer; font-size: 13px;
            flex-shrink: 0;
        }
        #ing-link-list { max-height: 260px; overflow-y: auto; margin-bottom: 10px; }

        /* ── Alert bar ───────────────────────────────── */
        .alert-low {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            border: 1.5px solid #ffb74d; border-radius: 12px;
            padding: 12px 18px; font-size: 13px; color: #bf360c; font-weight: 600;
            margin-bottom: 18px; display: none; align-items: center; gap: 10px;
        }
        .alert-low ion-icon { font-size: 18px; }

        /* ── Pagination ──────────────────────────────── */
        .inv-pagination {
            display: flex; justify-content: space-between; align-items: center;
            padding: 14px 20px; border-top: 1px solid #f0f0f0;
            font-size: 13px; color: #888;
        }
        .pg-btns { display: flex; gap: 6px; }
        .pg-btn {
            width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid #e8e8e8;
            background: #fff; font-size: 13px; font-weight: 700; cursor: pointer;
            display: flex; align-items: center; justify-content: center; transition: all .15s;
        }
        .pg-btn:hover, .pg-btn.active { background: #f5c800; border-color: #f5c800; color: #1a1a1a; }

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
        @keyframes slideUp { from{transform:translateY(20px);opacity:0}to{transform:translateY(0);opacity:1} }
        #toast.success::before { content: '✅ '; }
        #toast.error::before   { content: '❌ '; }

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
                <a href="admin.php"><img src="assets/Logo2.png" alt="ChickChicken" style="width:auto;height:55px"/></a>
            </h1>
        </div>
        <div class="navigation--admin">
            <nav>
                <ul>
                    <li><a href="admin.php#dashboard--admin" class="header_button"><ion-icon name="grid-outline"></ion-icon><span>Dashboard</span></a></li>
                    <li><a href="admin_sales_report.php" class="header_button"><ion-icon name="bar-chart-outline"></ion-icon><span>Sales Report</span></a></li>
                    <li><a href="orders--admin.php" class="header_button"><ion-icon name="bag-handle-outline"></ion-icon><span>Orders</span></a></li>
                    <li><a href="menu--admin.php" class="header_button"><ion-icon name="book-outline"></ion-icon><span>Menus</span></a></li>
                    <li><a href="inventory.php" class="header_button active"><ion-icon name="clipboard-outline"></ion-icon><span>Inventory</span></a></li>
                    <li><a href="admin-discount.php" class="header_button"><ion-icon name="pricetag-outline"></ion-icon><span>Discounts</span></a></li>
                    <li><a href="admins-review.php" class="header_button"><ion-icon name="chatbubbles-outline"></ion-icon><span>Reviews</span></a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main class="main-content">
<div class="inv-page">
    <h1>Inventory</h1>
    <p class="inv-subtitle">Track stock levels, restocks, and consumption across all menu items and raw ingredients.</p>

    <!-- ── TABS ── -->
    <div class="inv-tabs">
        <button class="inv-tab-btn active" onclick="switchTab('finished')">
            <ion-icon name="fast-food-outline"></ion-icon> Finished Goods
        </button>
        <button class="inv-tab-btn" onclick="switchTab('raw')">
            <ion-icon name="leaf-outline"></ion-icon> Raw Ingredients
        </button>
    </div>

    <!-- ════════════════════════════════════════
         TAB 1 — FINISHED GOODS (unchanged)
    ═════════════════════════════════════════ -->
    <div class="inv-tab-panel active" id="tab-finished">

        <div class="alert-low" id="lowAlert">
            <ion-icon name="warning-outline"></ion-icon>
            <span id="lowAlertText"></span>
        </div>

        <div class="inv-stats">
            <div class="inv-stat">
                <div class="inv-stat-icon yellow"><ion-icon name="cube-outline"></ion-icon></div>
                <div><div class="inv-stat-label">Total Items</div><div class="inv-stat-val" id="sTotal"><span class="skeleton" style="width:40px"></span></div></div>
            </div>
            <div class="inv-stat">
                <div class="inv-stat-icon green"><ion-icon name="checkmark-circle-outline"></ion-icon></div>
                <div><div class="inv-stat-label">In Stock</div><div class="inv-stat-val" id="sOk"><span class="skeleton" style="width:40px"></span></div></div>
            </div>
            <div class="inv-stat">
                <div class="inv-stat-icon orange"><ion-icon name="alert-circle-outline"></ion-icon></div>
                <div><div class="inv-stat-label">Low Stock</div><div class="inv-stat-val" id="sLow"><span class="skeleton" style="width:40px"></span></div></div>
            </div>
            <div class="inv-stat">
                <div class="inv-stat-icon red"><ion-icon name="close-circle-outline"></ion-icon></div>
                <div><div class="inv-stat-label">Out of Stock</div><div class="inv-stat-val" id="sOut"><span class="skeleton" style="width:40px"></span></div></div>
            </div>
        </div>

        <div class="inv-toolbar">
            <div class="inv-search">
                <ion-icon name="search-outline" class="search-ico"></ion-icon>
                <input type="text" id="searchInput" placeholder="Search items…" />
            </div>
            <select class="inv-filter-select" id="categoryFilter"><option value="">All Categories</option></select>
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

        <div class="inv-table-card">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th>#</th><th>Item</th><th>Category</th><th>Initial Stock</th>
                        <th>Remaining</th><th>Stock Level</th><th>Total Sold</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody id="invTableBody">
                    <tr><td colspan="9" class="inv-empty"><span class="skeleton" style="width:60%;display:block;margin:auto;height:18px"></span></td></tr>
                </tbody>
            </table>
            <div class="inv-pagination" id="pagination" style="display:none">
                <span id="pgInfo"></span>
                <div class="pg-btns" id="pgBtns"></div>
            </div>
        </div>
    </div><!-- /tab-finished -->

    <!-- ════════════════════════════════════════
         TAB 2 — RAW INGREDIENTS
    ═════════════════════════════════════════ -->
    <div class="inv-tab-panel" id="tab-raw">

        <div class="alert-low" id="rawLowAlert">
            <ion-icon name="warning-outline"></ion-icon>
            <span id="rawLowAlertText"></span>
        </div>

        <div class="inv-stats">
            <div class="inv-stat">
                <div class="inv-stat-icon yellow"><ion-icon name="leaf-outline"></ion-icon></div>
                <div><div class="inv-stat-label">Total Ingredients</div><div class="inv-stat-val" id="rTotal"><span class="skeleton" style="width:40px"></span></div></div>
            </div>
            <div class="inv-stat">
                <div class="inv-stat-icon green"><ion-icon name="checkmark-circle-outline"></ion-icon></div>
                <div><div class="inv-stat-label">In Stock</div><div class="inv-stat-val" id="rOk"><span class="skeleton" style="width:40px"></span></div></div>
            </div>
            <div class="inv-stat">
                <div class="inv-stat-icon orange"><ion-icon name="alert-circle-outline"></ion-icon></div>
                <div><div class="inv-stat-label">Low Stock</div><div class="inv-stat-val" id="rLow"><span class="skeleton" style="width:40px"></span></div></div>
            </div>
            <div class="inv-stat">
                <div class="inv-stat-icon red"><ion-icon name="close-circle-outline"></ion-icon></div>
                <div><div class="inv-stat-label">Out of Stock</div><div class="inv-stat-val" id="rOut"><span class="skeleton" style="width:40px"></span></div></div>
            </div>
        </div>

        <div class="inv-toolbar">
            <div class="inv-search">
                <ion-icon name="search-outline" class="search-ico"></ion-icon>
                <input type="text" id="rawSearchInput" placeholder="Search ingredients…" />
            </div>
            <select class="inv-filter-select" id="rawCategoryFilter"><option value="">All Categories</option></select>
            <select class="inv-filter-select" id="rawStatusFilter">
                <option value="">All Status</option>
                <option value="ok">In Stock</option>
                <option value="low">Low Stock</option>
                <option value="out">Out of Stock</option>
            </select>
            <button class="btn-inv-add" onclick="openRawAddModal()">
                <ion-icon name="add-outline"></ion-icon> Add Ingredient
            </button>
            <button class="btn-inv-add" style="background:#6a1b9a;color:#fff;" onclick="openLinkModal()">
                <ion-icon name="link-outline"></ion-icon> Link to Product
            </button>
        </div>

        <div class="inv-table-card">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th>#</th><th>Ingredient</th><th>Category</th><th>Unit</th>
                        <th>Initial Stock</th><th>Remaining</th><th>Stock Level</th>
                        <th>Supplier</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody id="rawTableBody">
                    <tr><td colspan="10" class="inv-empty"><span class="skeleton" style="width:60%;display:block;margin:auto;height:18px"></span></td></tr>
                </tbody>
            </table>
            <div class="inv-pagination" id="rawPagination" style="display:none">
                <span id="rawPgInfo"></span>
                <div class="pg-btns" id="rawPgBtns"></div>
            </div>
        </div>
    </div><!-- /tab-raw -->

</div>
</main>

<!-- ══════════════════════════════════════════
     FINISHED GOODS MODALS (unchanged)
══════════════════════════════════════════ -->
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
                    <label class="form-label">Low Stock Threshold</label>
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

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#c62828;">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <ion-icon name="trash-outline" style="font-size:36px;color:#c62828;display:block;margin:0 auto 10px"></ion-icon>
                <p style="font-size:14px;color:#444;">Remove <strong id="deleteName"></strong> from inventory tracking?</p>
                <input type="hidden" id="deleteId" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="doDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════
     RAW INGREDIENT MODALS
══════════════════════════════════════════ -->

<!-- Add / Edit Raw Ingredient -->
<div class="modal fade" id="rawModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rawModalTitle">Add Ingredient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="rawEditId" />
                <div class="row g-3 mb-3">
                    <div class="col-8">
                        <label class="form-label">Ingredient Name</label>
                        <input type="text" class="form-control" id="rawName" placeholder="e.g. Chicken Breast" />
                    </div>
                    <div class="col-4">
                        <label class="form-label">Unit</label>
                        <select class="form-select" id="rawUnit">
                            <option value="kg">kg</option>
                            <option value="g">g</option>
                            <option value="L">L</option>
                            <option value="ml">ml</option>
                            <option value="pcs">pcs</option>
                            <option value="pack">pack</option>
                            <option value="box">box</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" id="rawCategory" placeholder="e.g. Protein" />
                    </div>
                    <div class="col-6">
                        <label class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="rawSupplier" placeholder="e.g. Metro Manila Farms" />
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-4">
                        <label class="form-label">Initial Stock</label>
                        <input type="number" class="form-control" id="rawInitial" min="0" step="0.01" placeholder="0" />
                    </div>
                    <div class="col-4">
                        <label class="form-label">Remaining</label>
                        <input type="number" class="form-control" id="rawRemaining" min="0" step="0.01" placeholder="0" />
                    </div>
                    <div class="col-4">
                        <label class="form-label">Low Stock Threshold</label>
                        <input type="number" class="form-control" id="rawThreshold" min="0" step="0.01" placeholder="10" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="rawNotes" rows="2" placeholder="Optional notes…"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Discard</button>
                <button type="button" class="btn btn-save" onclick="saveRawItem()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Raw Restock -->
<div class="modal fade" id="rawRestockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Restock Ingredient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="rawRestockId" />
                <p style="font-size:13px;color:#666;" id="rawRestockName"></p>
                <label class="form-label">Amount to Add</label>
                <input type="number" class="form-control" id="rawRestockAmount" min="0.01" step="0.01" placeholder="e.g. 10.5" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-save" onclick="doRawRestock()">Restock</button>
            </div>
        </div>
    </div>
</div>

<!-- Raw Delete -->
<div class="modal fade" id="rawDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#c62828;">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <ion-icon name="trash-outline" style="font-size:36px;color:#c62828;display:block;margin:0 auto 10px"></ion-icon>
                <p style="font-size:14px;color:#444;">Delete ingredient <strong id="rawDeleteName"></strong>? This will also remove all product links.</p>
                <input type="hidden" id="rawDeleteId" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="doRawDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Link Product → Ingredients -->
<div class="modal fade" id="linkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#4a148c;">
                <h5 class="modal-title">Link Product → Ingredients</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label">Select Product</label>
                    <select class="form-select" id="linkProductSelect" onchange="loadExistingLinks()">
                        <option value="">— Choose a product —</option>
                    </select>
                </div>
                <div id="ing-link-list"></div>
                <button type="button" class="btn btn-light w-100" onclick="addIngLinkRow()" style="border:1.5px dashed #ccc;border-radius:9px;">
                    <ion-icon name="add-outline"></ion-icon> Add Ingredient Row
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-save" onclick="saveLinks()">Save Links</button>
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
// ══════════════════════════════════════════
//  SHARED
// ══════════════════════════════════════════
const API     = 'inventory_process.php';
const RAW_API = 'raw_ingredients_process.php';
const PER_PAGE = 10;

let toastTimer;
function toast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = type;
    el.style.display = 'block';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { el.style.display = 'none'; }, 3200);
}

function esc(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function debounce(fn, ms) {
    let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
}

async function apiGet(base, action, params = {}) {
    const q = new URLSearchParams({ action, ...params });
    const r = await fetch(`${base}?${q}`);
    if (!r.ok) throw new Error('HTTP ' + r.status);
    return r.json();
}

async function apiPost(base, action, body) {
    const r = await fetch(`${base}?action=${action}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body),
    });
    return r.json();
}

// ── Tab switching ──────────────────────────
function switchTab(tab) {
    document.querySelectorAll('.inv-tab-btn').forEach((b, i) => {
        b.classList.toggle('active', (i === 0 && tab === 'finished') || (i === 1 && tab === 'raw'));
    });
    document.getElementById('tab-finished').classList.toggle('active', tab === 'finished');
    document.getElementById('tab-raw').classList.toggle('active', tab === 'raw');
    if (tab === 'raw' && !rawLoaded) { loadRawStats(); loadRawCategories(); loadRawInventory(); rawLoaded = true; }
}

// ══════════════════════════════════════════
//  TAB 1 — FINISHED GOODS (original logic)
// ══════════════════════════════════════════
let allItems = [];
let currentPage = 1;
let editModal, restockModal, deleteModal;

document.addEventListener('DOMContentLoaded', () => {
    editModal    = new bootstrap.Modal(document.getElementById('invModal'));
    restockModal = new bootstrap.Modal(document.getElementById('restockModal'));
    deleteModal  = new bootstrap.Modal(document.getElementById('deleteModal'));
    rawModal        = new bootstrap.Modal(document.getElementById('rawModal'));
    rawRestockModal = new bootstrap.Modal(document.getElementById('rawRestockModal'));
    rawDeleteModal  = new bootstrap.Modal(document.getElementById('rawDeleteModal'));
    linkModal       = new bootstrap.Modal(document.getElementById('linkModal'));

    loadStats(); loadCategories(); loadInventory();

    document.getElementById('searchInput').addEventListener('input', debounce(renderTable, 250));
    document.getElementById('categoryFilter').addEventListener('change', renderTable);
    document.getElementById('statusFilter').addEventListener('change', renderTable);
    document.getElementById('rawSearchInput').addEventListener('input', debounce(renderRawTable, 250));
    document.getElementById('rawCategoryFilter').addEventListener('change', renderRawTable);
    document.getElementById('rawStatusFilter').addEventListener('change', renderRawTable);

    setInterval(() => { loadStats(); loadInventory(true); if (rawLoaded) { loadRawStats(); loadRawInventory(true); } }, 60000);
});

async function loadStats() {
    try {
        const s = await apiGet(API, 'stats');
        document.getElementById('sTotal').textContent = s.total;
        document.getElementById('sOk').textContent    = s.ok;
        document.getElementById('sLow').textContent   = s.low;
        document.getElementById('sOut').textContent   = s.out;
        const al = document.getElementById('lowAlert');
        if (s.low > 0 || s.out > 0) {
            al.style.display = 'flex';
            let msg = [];
            if (s.out > 0) msg.push(`${s.out} item(s) are out of stock`);
            if (s.low > 0) msg.push(`${s.low} item(s) are running low`);
            document.getElementById('lowAlertText').textContent = msg.join(' · ') + '. Consider restocking soon.';
        } else { al.style.display = 'none'; }
    } catch(e) {}
}

async function loadCategories() {
    try {
        const cats = await apiGet(API, 'categories');
        const sel = document.getElementById('categoryFilter');
        cats.forEach(c => { const o = document.createElement('option'); o.value = o.textContent = c; sel.appendChild(o); });
    } catch(e) {}
}

async function loadInventory(silent = false) {
    try {
        allItems = await apiGet(API, 'list');
        currentPage = 1;
        renderTable();
    } catch(e) {
        if (!silent) document.getElementById('invTableBody').innerHTML = `<tr><td colspan="9" class="inv-empty"><ion-icon name="cloud-offline-outline"></ion-icon> Could not connect.</td></tr>`;
    }
}

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
        tbody.innerHTML = `<tr><td colspan="9" class="inv-empty"><ion-icon name="search-outline"></ion-icon> No items match your filters.</td></tr>`;
        document.getElementById('pagination').style.display = 'none'; return;
    }
    tbody.innerHTML = page.map((it, idx) => {
        const pct     = parseFloat(it.stock_pct) || 0;
        const fillCls = it.stock_status === 'ok' ? 'fill-ok' : it.stock_status === 'low' ? 'fill-low' : 'fill-out';
        const badgeCls = `badge-${it.stock_status}`;
        const badgeTxt = it.stock_status === 'ok' ? 'In Stock' : it.stock_status === 'low' ? 'Low' : 'Out';
        return `<tr>
            <td style="color:#bbb;font-size:13px;">${start+idx+1}</td>
            <td><div class="prod-name">${esc(it.name)}</div><div class="prod-cat">${esc(it.category||'—')}</div></td>
            <td style="color:#888;font-size:13px;">${esc(it.category||'—')}</td>
            <td><strong>${it.initial_stock}</strong></td>
            <td><strong>${it.remaining}</strong></td>
            <td><div class="stock-bar-wrap"><div class="stock-bar-bg"><div class="stock-bar-fill ${fillCls}" style="width:${Math.min(pct,100)}%"></div></div><span class="stock-pct">${pct}%</span></div></td>
            <td style="color:#888;">${it.total_sold??0}</td>
            <td><span class="status-badge ${badgeCls}">${badgeTxt}</span></td>
            <td><div class="row-actions">
                <button class="btn-row btn-edit" onclick="openEditModal(${JSON.stringify(it).replace(/"/g,'&quot;')})"><ion-icon name="create-outline"></ion-icon> Edit</button>
                <button class="btn-row btn-restock" onclick="openRestockModal(${it.id},'${esc(it.name)}')"><ion-icon name="add-circle-outline"></ion-icon> Restock</button>
                <button class="btn-row btn-delete" onclick="openDeleteModal(${it.id},'${esc(it.name)}')"><ion-icon name="trash-outline"></ion-icon></button>
            </div></td>
        </tr>`;
    }).join('');
    document.getElementById('pgInfo').textContent = `Showing ${start+1}–${Math.min(start+PER_PAGE,filtered.length)} of ${filtered.length} items`;
    const pgBtns = document.getElementById('pgBtns'); pgBtns.innerHTML = '';
    for (let p = 1; p <= totalPages; p++) {
        const btn = document.createElement('button');
        btn.className = 'pg-btn' + (p === currentPage ? ' active' : '');
        btn.textContent = p;
        btn.onclick = () => { currentPage = p; renderTable(); };
        pgBtns.appendChild(btn);
    }
    document.getElementById('pagination').style.display = 'flex';
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add / Update Stock';
    document.getElementById('editId').value = '';
    document.getElementById('editInitial').value   = 50;
    document.getElementById('editRemaining').value = 50;
    document.getElementById('editThreshold').value = 10;
    const sel = document.getElementById('editProductSelect');
    sel.innerHTML = '<option value="">— Select a product —</option>';
    allItems.forEach(it => { const o = document.createElement('option'); o.value = it.product_id; o.textContent = it.name; sel.appendChild(o); });
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
    const productId = document.getElementById('editProductId').value || document.getElementById('editProductSelect').value;
    const initial   = parseInt(document.getElementById('editInitial').value);
    const remaining = parseInt(document.getElementById('editRemaining').value);
    const threshold = parseInt(document.getElementById('editThreshold').value) || 10;
    if (!productId) { toast('Please select a product.', 'error'); return; }
    if (isNaN(initial)||isNaN(remaining)) { toast('Please fill in all stock fields.', 'error'); return; }
    if (remaining > initial) { toast('Remaining cannot exceed initial stock.', 'error'); return; }
    try {
        const res = await apiPost(API, id ? 'update' : 'add', { id, product_id:productId, initial_stock:initial, remaining, low_stock_threshold:threshold });
        if (res.success) { editModal.hide(); toast('Inventory saved.'); await loadInventory(true); await loadStats(); }
        else toast(res.error||'Save failed.', 'error');
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
    if (!amount||amount<1) { toast('Enter a valid amount.','error'); return; }
    try {
        const res = await apiPost(API, 'restock', { id, amount });
        if (res.success) { restockModal.hide(); toast(`Added ${amount} units.`); await loadInventory(true); await loadStats(); }
    } catch(e) { toast('Network error.','error'); }
}

function openDeleteModal(id, name) {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteName').textContent = name;
    deleteModal.show();
}

async function doDelete() {
    const id = document.getElementById('deleteId').value;
    try {
        const res = await apiPost(API, 'delete', { id });
        if (res.success) { deleteModal.hide(); toast('Item removed.'); await loadInventory(true); await loadStats(); }
    } catch(e) { toast('Network error.','error'); }
}

// ══════════════════════════════════════════
//  TAB 2 — RAW INGREDIENTS
// ══════════════════════════════════════════
let allRawItems = [];
let rawPage = 1;
let rawLoaded = false;
let rawModal, rawRestockModal, rawDeleteModal, linkModal;
let allProducts = [];     // for link modal dropdown
let allRawForLink = [];   // for link modal ingredient dropdowns

async function loadRawStats() {
    try {
        const s = await apiGet(RAW_API, 'stats');
        document.getElementById('rTotal').textContent = s.total;
        document.getElementById('rOk').textContent    = s.ok;
        document.getElementById('rLow').textContent   = s.low;
        document.getElementById('rOut').textContent   = s.out;
        const al = document.getElementById('rawLowAlert');
        if (s.low > 0 || s.out > 0) {
            al.style.display = 'flex';
            let msg = [];
            if (s.out > 0) msg.push(`${s.out} ingredient(s) out of stock`);
            if (s.low > 0) msg.push(`${s.low} ingredient(s) running low`);
            document.getElementById('rawLowAlertText').textContent = msg.join(' · ') + '. Consider restocking.';
        } else { al.style.display = 'none'; }
    } catch(e) {}
}

async function loadRawCategories() {
    try {
        const cats = await apiGet(RAW_API, 'categories');
        const sel = document.getElementById('rawCategoryFilter');
        cats.forEach(c => { const o = document.createElement('option'); o.value = o.textContent = c; sel.appendChild(o); });
    } catch(e) {}
}

async function loadRawInventory(silent = false) {
    try {
        allRawItems = await apiGet(RAW_API, 'list');
        rawPage = 1;
        renderRawTable();
    } catch(e) {
        if (!silent) document.getElementById('rawTableBody').innerHTML = `<tr><td colspan="10" class="inv-empty"><ion-icon name="cloud-offline-outline"></ion-icon> Could not connect.</td></tr>`;
    }
}

function renderRawTable() {
    const search   = document.getElementById('rawSearchInput').value.toLowerCase();
    const category = document.getElementById('rawCategoryFilter').value;
    const status   = document.getElementById('rawStatusFilter').value;
    const filtered = allRawItems.filter(it => {
        if (search   && !it.name.toLowerCase().includes(search)) return false;
        if (category && it.category !== category) return false;
        if (status   && it.stock_status !== status) return false;
        return true;
    });
    const totalPages = Math.max(1, Math.ceil(filtered.length / PER_PAGE));
    if (rawPage > totalPages) rawPage = totalPages;
    const start = (rawPage - 1) * PER_PAGE;
    const page  = filtered.slice(start, start + PER_PAGE);
    const tbody = document.getElementById('rawTableBody');
    if (!page.length) {
        tbody.innerHTML = `<tr><td colspan="10" class="inv-empty"><ion-icon name="search-outline"></ion-icon> No ingredients match your filters.</td></tr>`;
        document.getElementById('rawPagination').style.display = 'none'; return;
    }
    tbody.innerHTML = page.map((it, idx) => {
        const pct     = parseFloat(it.stock_pct) || 0;
        const fillCls = it.stock_status === 'ok' ? 'fill-ok' : it.stock_status === 'low' ? 'fill-low' : 'fill-out';
        const badgeCls = `badge-${it.stock_status}`;
        const badgeTxt = it.stock_status === 'ok' ? 'In Stock' : it.stock_status === 'low' ? 'Low' : 'Out';
        return `<tr>
            <td style="color:#bbb;font-size:13px;">${start+idx+1}</td>
            <td><div class="prod-name">${esc(it.name)}</div><div class="prod-cat">${esc(it.notes||'')}</div></td>
            <td style="color:#888;font-size:13px;">${esc(it.category||'—')}</td>
            <td><span style="font-family:'Oswald',sans-serif;font-weight:600;">${esc(it.unit)}</span></td>
            <td><strong>${it.initial_stock}</strong></td>
            <td><strong>${it.remaining}</strong></td>
            <td><div class="stock-bar-wrap"><div class="stock-bar-bg"><div class="stock-bar-fill ${fillCls}" style="width:${Math.min(pct,100)}%"></div></div><span class="stock-pct">${pct}%</span></div></td>
            <td style="color:#888;font-size:13px;">${esc(it.supplier||'—')}</td>
            <td><span class="status-badge ${badgeCls}">${badgeTxt}</span></td>
            <td><div class="row-actions">
                <button class="btn-row btn-edit" onclick="openRawEditModal(${JSON.stringify(it).replace(/"/g,'&quot;')})"><ion-icon name="create-outline"></ion-icon> Edit</button>
                <button class="btn-row btn-restock" onclick="openRawRestockModal(${it.id},'${esc(it.name)}','${esc(it.unit)}')"><ion-icon name="add-circle-outline"></ion-icon> Restock</button>
                <button class="btn-row btn-delete" onclick="openRawDeleteModal(${it.id},'${esc(it.name)}')"><ion-icon name="trash-outline"></ion-icon></button>
            </div></td>
        </tr>`;
    }).join('');
    document.getElementById('rawPgInfo').textContent = `Showing ${start+1}–${Math.min(start+PER_PAGE,filtered.length)} of ${filtered.length} ingredients`;
    const pgBtns = document.getElementById('rawPgBtns'); pgBtns.innerHTML = '';
    for (let p = 1; p <= totalPages; p++) {
        const btn = document.createElement('button');
        btn.className = 'pg-btn' + (p === rawPage ? ' active' : '');
        btn.textContent = p;
        btn.onclick = () => { rawPage = p; renderRawTable(); };
        pgBtns.appendChild(btn);
    }
    document.getElementById('rawPagination').style.display = 'flex';
}

function openRawAddModal() {
    document.getElementById('rawModalTitle').textContent = 'Add Ingredient';
    document.getElementById('rawEditId').value   = '';
    document.getElementById('rawName').value     = '';
    document.getElementById('rawUnit').value     = 'kg';
    document.getElementById('rawCategory').value = '';
    document.getElementById('rawSupplier').value = '';
    document.getElementById('rawInitial').value  = '';
    document.getElementById('rawRemaining').value = '';
    document.getElementById('rawThreshold').value = 10;
    document.getElementById('rawNotes').value    = '';
    rawModal.show();
}

function openRawEditModal(it) {
    document.getElementById('rawModalTitle').textContent = 'Edit – ' + it.name;
    document.getElementById('rawEditId').value    = it.id;
    document.getElementById('rawName').value      = it.name;
    document.getElementById('rawUnit').value      = it.unit;
    document.getElementById('rawCategory').value  = it.category || '';
    document.getElementById('rawSupplier').value  = it.supplier || '';
    document.getElementById('rawInitial').value   = it.initial_stock;
    document.getElementById('rawRemaining').value = it.remaining;
    document.getElementById('rawThreshold').value = it.low_stock_threshold;
    document.getElementById('rawNotes').value     = it.notes || '';
    rawModal.show();
}

async function saveRawItem() {
    const id        = document.getElementById('rawEditId').value;
    const name      = document.getElementById('rawName').value.trim();
    const unit      = document.getElementById('rawUnit').value;
    const category  = document.getElementById('rawCategory').value.trim();
    const supplier  = document.getElementById('rawSupplier').value.trim();
    const initial   = parseFloat(document.getElementById('rawInitial').value);
    const remaining = parseFloat(document.getElementById('rawRemaining').value);
    const threshold = parseFloat(document.getElementById('rawThreshold').value) || 10;
    const notes     = document.getElementById('rawNotes').value.trim();
    if (!name)            { toast('Name is required.', 'error'); return; }
    if (isNaN(initial)||isNaN(remaining)) { toast('Fill in stock fields.', 'error'); return; }
    if (remaining > initial) { toast('Remaining cannot exceed initial.', 'error'); return; }
    try {
        const res = await apiPost(RAW_API, id ? 'update' : 'add', { id, name, unit, category, supplier, initial_stock:initial, remaining, low_stock_threshold:threshold, notes });
        if (res.success) { rawModal.hide(); toast('Ingredient saved.'); await loadRawInventory(true); await loadRawStats(); }
        else toast(res.error||'Save failed.', 'error');
    } catch(e) { toast('Network error.', 'error'); }
}

function openRawRestockModal(id, name, unit) {
    document.getElementById('rawRestockId').value = id;
    document.getElementById('rawRestockName').textContent = `Restocking: ${name} (${unit})`;
    document.getElementById('rawRestockAmount').value = '';
    rawRestockModal.show();
}

async function doRawRestock() {
    const id     = document.getElementById('rawRestockId').value;
    const amount = parseFloat(document.getElementById('rawRestockAmount').value);
    if (!amount||amount<=0) { toast('Enter a valid amount.','error'); return; }
    try {
        const res = await apiPost(RAW_API, 'restock', { id, amount });
        if (res.success) { rawRestockModal.hide(); toast(`Added ${amount} units.`); await loadRawInventory(true); await loadRawStats(); }
    } catch(e) { toast('Network error.','error'); }
}

function openRawDeleteModal(id, name) {
    document.getElementById('rawDeleteId').value = id;
    document.getElementById('rawDeleteName').textContent = name;
    rawDeleteModal.show();
}

async function doRawDelete() {
    const id = document.getElementById('rawDeleteId').value;
    try {
        const res = await apiPost(RAW_API, 'delete', { id });
        if (res.success) { rawDeleteModal.hide(); toast('Ingredient deleted.'); await loadRawInventory(true); await loadRawStats(); }
    } catch(e) { toast('Network error.','error'); }
}

// ── Link modal ─────────────────────────────
async function openLinkModal() {
    try {
        const [prods, raws] = await Promise.all([
            apiGet(RAW_API, 'products'),
            apiGet(RAW_API, 'list'),
        ]);
        allProducts    = prods;
        allRawForLink  = raws;
        const sel = document.getElementById('linkProductSelect');
        sel.innerHTML = '<option value="">— Choose a product —</option>';
        prods.forEach(p => { const o = document.createElement('option'); o.value = p.id; o.textContent = p.name; sel.appendChild(o); });
        document.getElementById('ing-link-list').innerHTML = '';
    } catch(e) { toast('Could not load data.','error'); return; }
    linkModal.show();
}

async function loadExistingLinks() {
    const productId = document.getElementById('linkProductSelect').value;
    const list = document.getElementById('ing-link-list');
    list.innerHTML = '';
    if (!productId) return;
    try {
        const links = await apiGet(RAW_API, 'links', { product_id: productId });
        if (links.length) {
            links.forEach(lk => addIngLinkRow(lk.ingredient_id, lk.quantity_used));
        } else {
            addIngLinkRow();
        }
    } catch(e) { addIngLinkRow(); }
}

// ── FIX 1: button no longer has class "ing-link-row" ──────────
function addIngLinkRow(ingredientId = '', qty = '') {
    const list = document.getElementById('ing-link-list');
    const row  = document.createElement('div');
    row.className = 'ing-link-row';   // only the wrapper div gets this class
    const opts = allRawForLink.map(r =>
        `<option value="${r.id}" ${r.id == ingredientId ? 'selected' : ''}>${esc(r.name)} (${esc(r.unit)})</option>`
    ).join('');
    row.innerHTML = `
        <select class="form-select form-select-sm" style="flex:2;">
            <option value="">— Ingredient —</option>
            ${opts}
        </select>
        <input type="number" class="form-control form-control-sm" style="flex:1;max-width:120px;"
               min="0.01" step="0.01" placeholder="Qty used" value="${qty}" />
        <button class="btn-remove-ing" onclick="this.parentElement.remove()">✕</button>
    `;
    list.appendChild(row);
}

// ── FIX 2: skip completely empty rows instead of failing ──────
async function saveLinks() {
    const productId = document.getElementById('linkProductSelect').value;
    if (!productId) { toast('Select a product first.','error'); return; }
    const rows = document.querySelectorAll('#ing-link-list .ing-link-row');
    const links = [];
    let valid = true;
    rows.forEach(row => {
        const ingId = row.querySelector('select').value;
        const qty   = parseFloat(row.querySelector('input').value);
        // Skip rows where both fields are empty (user added an extra blank row)
        if (!ingId && (isNaN(qty) || qty <= 0)) return;
        // If only one field is filled, that's an error
        if (!ingId || isNaN(qty) || qty <= 0) { valid = false; return; }
        links.push({ ingredient_id: ingId, quantity_used: qty });
    });
    if (!valid) { toast('Fill in all ingredient rows correctly.','error'); return; }
    try {
        const res = await apiPost(RAW_API, 'save_links', { product_id: productId, links });
        if (res.success) { linkModal.hide(); toast('Links saved! Orders will now deduct these ingredients.'); }
        else toast(res.error||'Failed to save links.','error');
    } catch(e) { toast('Network error.','error'); }
}
</script>
</body>
</html>