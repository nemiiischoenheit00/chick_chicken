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

        .inv-page { padding: 0 28px 40px 28px; }
        .inv-page h1 { font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 600; margin-bottom: 6px; color: #1a1a1a; }
        .inv-subtitle { color: #888; font-size: 13px; margin-bottom: 24px; }

        /* ── Tabs ── */
        .inv-tabs { display: flex; gap: 4px; margin-bottom: 24px; background: #f5f5f5; border-radius: 12px; padding: 4px; width: fit-content; }
        .inv-tab { padding: 8px 22px; border-radius: 9px; border: none; background: transparent; font-size: 13px; font-weight: 700; color: #888; cursor: pointer; transition: all .2s; display: flex; align-items: center; gap: 7px; }
        .inv-tab ion-icon { font-size: 16px; }
        .inv-tab.active { background: #fff; color: #1a1a1a; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .inv-tab:hover:not(.active) { color: #555; }

        .inv-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px; }
        .inv-stat { background: #fff; border-radius: 14px; padding: 18px 20px; box-shadow: 0 2px 10px rgba(0,0,0,.06); display: flex; align-items: center; gap: 14px; }
        .inv-stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .inv-stat-icon.yellow  { background: #fff8dc; color: #e0a800; }
        .inv-stat-icon.green   { background: #e8f5e9; color: #2e7d32; }
        .inv-stat-icon.orange  { background: #fff3e0; color: #e65c00; }
        .inv-stat-icon.red     { background: #fce4ec; color: #c62828; }
        .inv-stat-label { font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
        .inv-stat-val   { font-size: 22px; font-weight: 800; color: #1a1a1a; line-height: 1.1; font-family: 'Oswald', sans-serif; }

        .inv-toolbar { display: flex; align-items: center; gap: 10px; margin-bottom: 18px; flex-wrap: wrap; }
        .inv-search { flex: 1; min-width: 200px; position: relative; }
        .inv-search input { width: 100%; border: 1.5px solid #e8e8e8; border-radius: 10px; padding: 9px 14px 9px 38px; font-size: 14px; outline: none; background: #fff; transition: border .2s; }
        .inv-search input:focus { border-color: #f5c800; }
        .inv-search .search-ico { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #bbb; font-size: 16px; pointer-events: none; }
        .inv-filter-select { border: 1.5px solid #e8e8e8; border-radius: 10px; padding: 9px 14px; font-size: 13px; background: #fff; outline: none; cursor: pointer; transition: border .2s; }
        .inv-filter-select:focus { border-color: #f5c800; }
        .btn-inv-add { background: #f5c800; border: none; border-radius: 10px; padding: 9px 20px; font-weight: 700; font-size: 14px; color: #1a1a1a; display: flex; align-items: center; gap: 6px; cursor: pointer; transition: background .2s, transform .1s; white-space: nowrap; }
        .btn-inv-add:hover { background: #e0b300; transform: translateY(-1px); }

        .inv-table-card { background: #fff; border-radius: 16px; box-shadow: 0 2px 14px rgba(0,0,0,.07); overflow: hidden; }
        .inv-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .inv-table thead tr { background: #fafafa; border-bottom: 2px solid #f0f0f0; }
        .inv-table thead th { padding: 13px 16px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .6px; color: #888; white-space: nowrap; }
        .inv-table tbody tr { border-bottom: 1px solid #f5f5f5; transition: background .15s; }
        .inv-table tbody tr:last-child { border-bottom: none; }
        .inv-table tbody tr:hover { background: #fffdf0; }
        .inv-table td { padding: 13px 16px; vertical-align: middle; }
        .prod-name { font-weight: 700; color: #1a1a1a; }
        .prod-cat  { font-size: 11px; color: #bbb; margin-top: 2px; }

        .unit-chip { display: inline-block; background: #f0f0f0; color: #555; font-size: 10px; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; padding: 2px 7px; border-radius: 6px; margin-left: 4px; vertical-align: middle; }

        /* Ingredient-type chip */
        .ing-chip { display: inline-block; background: #f0f7ff; color: #1565c0; font-size: 10px; font-weight: 700; letter-spacing: .4px; padding: 2px 7px; border-radius: 6px; vertical-align: middle; }

        .stock-bar-wrap { display: flex; align-items: center; gap: 8px; }
        .stock-bar-bg { flex: 1; height: 7px; background: #f0f0f0; border-radius: 99px; overflow: hidden; }
        .stock-bar-fill { height: 100%; border-radius: 99px; transition: width .4s; }
        .fill-ok  { background: #4caf50; }
        .fill-low { background: #ff9800; }
        .fill-out { background: #e53935; }
        .stock-pct { font-size: 12px; font-weight: 700; color: #666; width: 36px; text-align: right; }

        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; }
        .badge-ok  { background: #e8f5e9; color: #2e7d32; }
        .badge-low { background: #fff3e0; color: #e65c00; }
        .badge-out { background: #fce4ec; color: #c62828; }

        .row-actions { display: flex; gap: 6px; }
        .btn-row { border: none; border-radius: 8px; padding: 6px 10px; font-size: 12px; font-weight: 700; cursor: pointer; transition: opacity .15s, transform .1s; display: flex; align-items: center; gap: 4px; }
        .btn-row:hover { opacity: .8; transform: translateY(-1px); }
        .btn-edit    { background: #e8f4fd; color: #1565c0; }
        .btn-restock { background: #e8f5e9; color: #2e7d32; }
        .btn-delete  { background: #fce4ec; color: #c62828; }

        .inv-empty { text-align: center; padding: 50px 20px; color: #bbb; font-size: 14px; }
        .inv-empty ion-icon { font-size: 40px; display: block; margin: 0 auto 10px; }
        .skeleton { display: inline-block; background: linear-gradient(90deg,#eee 25%,#f8f8f8 50%,#eee 75%); background-size: 200% 100%; animation: shimmer 1.2s infinite; border-radius: 4px; height: 1em; }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

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

        .unit-preset-row { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
        .unit-preset { padding: 4px 12px; border-radius: 20px; border: 1.5px solid #e8e8e8; background: #fafafa; font-size: 12px; font-weight: 700; cursor: pointer; transition: all .15px; color: #555; }
        .unit-preset:hover, .unit-preset.active { background: #f5c800; border-color: #f5c800; color: #1a1a1a; }
        .unit-hint { font-size: 11px; color: #bbb; margin-top: 4px; }

        #toast { position: fixed; bottom: 24px; right: 24px; background: #1a1a1a; color: #fff; padding: 12px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; display: none; z-index: 9999; box-shadow: 0 8px 30px rgba(0,0,0,.2); animation: slideUp .25s ease; }
        @keyframes slideUp { from { transform: translateY(20px); opacity:0 } to { transform:translateY(0); opacity:1 } }
        #toast.success::before { content: '✅ '; }
        #toast.error::before   { content: '❌ '; }

        .alert-low { background: linear-gradient(135deg, #fff3e0, #ffe0b2); border: 1.5px solid #ffb74d; border-radius: 12px; padding: 12px 18px; font-size: 13px; color: #bf360c; font-weight: 600; margin-bottom: 18px; display: none; align-items: center; gap: 10px; }
        .alert-low ion-icon { font-size: 18px; }

        .inv-pagination { display: flex; justify-content: space-between; align-items: center; padding: 14px 20px; border-top: 1px solid #f0f0f0; font-size: 13px; color: #888; }
        .pg-btns { display: flex; gap: 6px; }
        .pg-btn { width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid #e8e8e8; background: #fff; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .15s; }
        .pg-btn:hover, .pg-btn.active { background: #f5c800; border-color: #f5c800; color: #1a1a1a; }
        .pg-btn:disabled { opacity: .4; cursor: default; }

        /* Raw section hidden by default via JS, not CSS, to avoid display:'' conflict */
        @media (max-width: 900px) { .inv-stats { grid-template-columns: repeat(2, 1fr); } }
    </style>
</head>

<body>
<header>
    <div class="sidebar">
        <div class="logo">
            <h1><a href="admin.html"><img src="assets/Logo2.png" alt="ChickChicken" style="width:auto;height:55px"/></a></h1>
        </div>
        <div class="navigation--admin">
            <nav>
                <ul>
                    <li><a href="admin.php#dashboard--admin" class="header_button"><ion-icon name="grid-outline"></ion-icon><span>Dashboard</span></a></li>
                    <li><a href="orders--admin.php" class="header_button"><ion-icon name="bag-handle-outline"></ion-icon><span>Orders</span></a></li>
                    <li><a href="menu--admin.php" class="header_button"><ion-icon name="book-outline"></ion-icon><span>Menus</span></a></li>
                    <li><a href="inventory.php" class="header_button active"><ion-icon name="clipboard-outline"></ion-icon><span>Inventory</span></a></li>
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
        <button class="inv-tab active" id="tabProducts" onclick="switchTab('products')">
            <ion-icon name="fast-food-outline"></ion-icon> Menu Products
        </button>
        <button class="inv-tab" id="tabRaw" onclick="switchTab('raw')">
            <ion-icon name="leaf-outline"></ion-icon> Raw Ingredients
        </button>
    </div>

    <!-- ══════════════════════════════════════ -->
    <!-- PRODUCTS SECTION                       -->
    <!-- ══════════════════════════════════════ -->
    <div id="productsSection">
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
                        <th>#</th>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Initial Stock</th>
                        <th>Remaining</th>
                        <th>Stock Level</th>
                        <th>Total Sold</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="invTableBody">
                    <tr><td colspan="10" class="inv-empty">
                        <span class="skeleton" style="width:60%;display:block;margin:auto;height:18px"></span>
                    </td></tr>
                </tbody>
            </table>
            <div class="inv-pagination" id="pagination" style="display:none">
                <span id="pgInfo"></span>
                <div class="pg-btns" id="pgBtns"></div>
            </div>
        </div>
    </div><!-- /productsSection -->

    <!-- ══════════════════════════════════════ -->
    <!-- RAW INGREDIENTS SECTION                -->
    <!-- ══════════════════════════════════════ -->
    <div id="rawSection" style="display:none">
        <div class="alert-low" id="riLowAlert">
            <ion-icon name="warning-outline"></ion-icon>
            <span id="riLowAlertText"></span>
        </div>

        <div class="inv-stats">
            <div class="inv-stat">
                <div class="inv-stat-icon yellow"><ion-icon name="leaf-outline"></ion-icon></div>
                <div><div class="inv-stat-label">Total Ingredients</div><div class="inv-stat-val" id="riTotal"><span class="skeleton" style="width:40px"></span></div></div>
            </div>
            <div class="inv-stat">
                <div class="inv-stat-icon green"><ion-icon name="checkmark-circle-outline"></ion-icon></div>
                <div><div class="inv-stat-label">In Stock</div><div class="inv-stat-val" id="riOk"><span class="skeleton" style="width:40px"></span></div></div>
            </div>
            <div class="inv-stat">
                <div class="inv-stat-icon orange"><ion-icon name="alert-circle-outline"></ion-icon></div>
                <div><div class="inv-stat-label">Low Stock</div><div class="inv-stat-val" id="riLow"><span class="skeleton" style="width:40px"></span></div></div>
            </div>
            <div class="inv-stat">
                <div class="inv-stat-icon red"><ion-icon name="close-circle-outline"></ion-icon></div>
                <div><div class="inv-stat-label">Out of Stock</div><div class="inv-stat-val" id="riOut"><span class="skeleton" style="width:40px"></span></div></div>
            </div>
        </div>

        <div class="inv-toolbar">
            <div class="inv-search">
                <ion-icon name="search-outline" class="search-ico"></ion-icon>
                <input type="text" id="riSearchInput" placeholder="Search ingredients…" />
            </div>
            <select class="inv-filter-select" id="riCategoryFilter"><option value="">All Categories</option></select>
            <select class="inv-filter-select" id="riStatusFilter">
                <option value="">All Status</option>
                <option value="ok">In Stock</option>
                <option value="low">Low Stock</option>
                <option value="out">Out of Stock</option>
            </select>
            <button class="btn-inv-add" onclick="openRiAddModal()">
                <ion-icon name="add-outline"></ion-icon> Add Ingredient
            </button>
        </div>

        <div class="inv-table-card">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ingredient</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Initial Stock</th>
                        <th>Remaining</th>
                        <th>Stock Level</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="riTableBody">
                    <tr><td colspan="10" class="inv-empty">
                        <span class="skeleton" style="width:60%;display:block;margin:auto;height:18px"></span>
                    </td></tr>
                </tbody>
            </table>
            <div class="inv-pagination" id="riPagination" style="display:none">
                <span id="riPgInfo"></span>
                <div class="pg-btns" id="riPgBtns"></div>
            </div>
        </div>
    </div><!-- /rawSection -->

</div>
</main>

<!-- ══════════════════════════════════════════════ -->
<!-- PRODUCT INVENTORY MODALS                       -->
<!-- ══════════════════════════════════════════════ -->

<!-- EDIT / ADD MODAL -->
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

                <div class="mb-3">
                    <label class="form-label">Measurement Unit</label>
                    <input type="text" class="form-control" id="editUnit" placeholder="e.g. kg, pcs, liters, packs…" />
                    <div class="unit-preset-row" id="unitPresets">
                        <button type="button" class="unit-preset" onclick="setUnit('kg')">kg</button>
                        <button type="button" class="unit-preset" onclick="setUnit('g')">g</button>
                        <button type="button" class="unit-preset" onclick="setUnit('pcs')">pcs</button>
                        <button type="button" class="unit-preset" onclick="setUnit('liters')">liters</button>
                        <button type="button" class="unit-preset" onclick="setUnit('ml')">ml</button>
                        <button type="button" class="unit-preset" onclick="setUnit('packs')">packs</button>
                        <button type="button" class="unit-preset" onclick="setUnit('boxes')">boxes</button>
                        <button type="button" class="unit-preset" onclick="setUnit('trays')">trays</button>
                        <button type="button" class="unit-preset" onclick="setUnit('bags')">bags</button>
                    </div>
                    <div class="unit-hint">Click a preset or type a custom unit above.</div>
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

<!-- RESTOCK MODAL -->
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
                <label class="form-label">Amount to Add <span id="restockUnitLabel" style="color:#888;font-weight:400;text-transform:none;letter-spacing:0;"></span></label>
                <input type="number" class="form-control" id="restockAmount" min="1" placeholder="e.g. 50" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-save" onclick="doRestock()">Restock</button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE CONFIRM MODAL -->
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

<!-- ══════════════════════════════════════════════ -->
<!-- RAW INGREDIENT MODALS                          -->
<!-- ══════════════════════════════════════════════ -->

<!-- ADD / EDIT RAW INGREDIENT MODAL -->
<div class="modal fade" id="riModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#1a3a2a;">
                <h5 class="modal-title" id="riModalTitle">Add Raw Ingredient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="riEditId" />

                <div class="row g-3 mb-3">
                    <div class="col-8">
                        <label class="form-label">Ingredient Name <span style="color:#e53935">*</span></label>
                        <input type="text" class="form-control" id="riName" placeholder="e.g. Chicken Breast, Cooking Oil…" />
                    </div>
                    <div class="col-4">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" id="riCategory" placeholder="e.g. Meat, Spice…" list="riCategoryList" />
                        <datalist id="riCategoryList">
                            <option value="Meat">
                            <option value="Poultry">
                            <option value="Seafood">
                            <option value="Vegetables">
                            <option value="Fruits">
                            <option value="Dairy">
                            <option value="Spices">
                            <option value="Condiments">
                            <option value="Oils & Fats">
                            <option value="Grains & Flour">
                            <option value="Beverages">
                            <option value="Packaging">
                            <option value="Cleaning">
                            <option value="Other">
                        </datalist>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Measurement Unit</label>
                    <input type="text" class="form-control" id="riUnit" placeholder="e.g. kg, pcs, liters, packs…" />
                    <div class="unit-preset-row">
                        <button type="button" class="unit-preset" onclick="setRiUnit('kg')">kg</button>
                        <button type="button" class="unit-preset" onclick="setRiUnit('g')">g</button>
                        <button type="button" class="unit-preset" onclick="setRiUnit('pcs')">pcs</button>
                        <button type="button" class="unit-preset" onclick="setRiUnit('liters')">liters</button>
                        <button type="button" class="unit-preset" onclick="setRiUnit('ml')">ml</button>
                        <button type="button" class="unit-preset" onclick="setRiUnit('packs')">packs</button>
                        <button type="button" class="unit-preset" onclick="setRiUnit('boxes')">boxes</button>
                        <button type="button" class="unit-preset" onclick="setRiUnit('trays')">trays</button>
                        <button type="button" class="unit-preset" onclick="setRiUnit('bags')">bags</button>
                        <button type="button" class="unit-preset" onclick="setRiUnit('sacks')">sacks</button>
                        <button type="button" class="unit-preset" onclick="setRiUnit('bottles')">bottles</button>
                        <button type="button" class="unit-preset" onclick="setRiUnit('cans')">cans</button>
                    </div>
                    <div class="unit-hint">Click a preset or type a custom unit.</div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-4">
                        <label class="form-label">Initial Stock</label>
                        <input type="number" class="form-control" id="riInitial" min="0" step="0.01" placeholder="e.g. 100" />
                    </div>
                    <div class="col-4">
                        <label class="form-label">Remaining</label>
                        <input type="number" class="form-control" id="riRemaining" min="0" step="0.01" placeholder="e.g. 80" />
                    </div>
                    <div class="col-4">
                        <label class="form-label">Low Threshold</label>
                        <input type="number" class="form-control" id="riThreshold" min="0" step="0.01" placeholder="e.g. 10" />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Supplier <small style="color:#bbb;font-weight:400;">(optional)</small></label>
                    <input type="text" class="form-control" id="riSupplier" placeholder="e.g. ABC Farm, Local Market…" />
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes <small style="color:#bbb;font-weight:400;">(optional)</small></label>
                    <textarea class="form-control" id="riNotes" rows="2" placeholder="Storage instructions, handling notes, etc."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-cancel" data-bs-dismiss="modal">Discard</button>
                <button type="button" class="btn btn-save" onclick="saveRiItem()">Save Ingredient</button>
            </div>
        </div>
    </div>
</div>

<!-- RESTOCK RAW INGREDIENT MODAL -->
<div class="modal fade" id="riRestockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#1a3a2a;">
                <h5 class="modal-title">Restock Ingredient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="riRestockId" />
                <p style="font-size:13px;color:#666;" id="riRestockName"></p>
                <label class="form-label">Amount to Add <span id="riRestockUnitLabel" style="color:#888;font-weight:400;text-transform:none;letter-spacing:0;"></span></label>
                <input type="number" class="form-control" id="riRestockAmount" min="0.01" step="0.01" placeholder="e.g. 20" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-save" onclick="doRiRestock()">Restock</button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE RAW INGREDIENT MODAL -->
<div class="modal fade" id="riDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#c62828;">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <ion-icon name="trash-outline" style="font-size:36px;color:#c62828;display:block;margin:0 auto 10px"></ion-icon>
                <p style="font-size:14px;color:#444;">Permanently delete <strong id="riDeleteName"></strong> from raw ingredients?</p>
                <input type="hidden" id="riDeleteId" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="doRiDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>

<div id="toast"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="admin.js"></script>

<script>
const API = 'inventory_process.php';
const PER_PAGE = 10;

let allItems = [];
let currentPage = 1;
let allRiItems = [];
let riCurrentPage = 1;

let activeTab = 'products';

let editModal, restockModal, deleteModal;
let riModal, riRestockModal, riDeleteModal;

document.addEventListener('DOMContentLoaded', () => {
    editModal      = new bootstrap.Modal(document.getElementById('invModal'));
    restockModal   = new bootstrap.Modal(document.getElementById('restockModal'));
    deleteModal    = new bootstrap.Modal(document.getElementById('deleteModal'));
    riModal        = new bootstrap.Modal(document.getElementById('riModal'));
    riRestockModal = new bootstrap.Modal(document.getElementById('riRestockModal'));
    riDeleteModal  = new bootstrap.Modal(document.getElementById('riDeleteModal'));

    // Ensure correct initial visibility via JS (avoids CSS display:'' conflict)
    document.getElementById('productsSection').style.display = 'block';
    document.getElementById('rawSection').style.display = 'none';

    loadStats();
    loadCategories();
    loadInventory();
    loadRiStats();
    loadRiCategories();
    loadRiInventory();

    document.getElementById('searchInput').addEventListener('input', debounce(renderTable, 250));
    document.getElementById('categoryFilter').addEventListener('change', renderTable);
    document.getElementById('statusFilter').addEventListener('change', renderTable);
    document.getElementById('editUnit').addEventListener('input', syncUnitPresets);

    document.getElementById('riSearchInput').addEventListener('input', debounce(renderRiTable, 250));
    document.getElementById('riCategoryFilter').addEventListener('change', renderRiTable);
    document.getElementById('riStatusFilter').addEventListener('change', renderRiTable);
    document.getElementById('riUnit').addEventListener('input', syncRiUnitPresets);

    setInterval(() => {
        loadStats(); loadInventory(true);
        loadRiStats(); loadRiInventory(true);
    }, 60000);
});

// ─────────────────────────────────────────────
// TAB SWITCHING — FIX 3: use 'block' not ''
// ─────────────────────────────────────────────
function switchTab(tab) {
    activeTab = tab;
    document.getElementById('productsSection').style.display = tab === 'products' ? 'block' : 'none';
    document.getElementById('rawSection').style.display      = tab === 'raw'      ? 'block' : 'none';
    document.getElementById('tabProducts').classList.toggle('active', tab === 'products');
    document.getElementById('tabRaw').classList.toggle('active', tab === 'raw');
}

// ─────────────────────────────────────────────
// API HELPERS
// ─────────────────────────────────────────────
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
    if (!r.ok) throw new Error('HTTP ' + r.status);
    return r.json();
}

// ─────────────────────────────────────────────
// PRODUCT INVENTORY
// ─────────────────────────────────────────────
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
    } catch (e) { console.error('Stats error:', e); }
}

async function loadCategories() {
    try {
        const cats = await get('categories');
        const sel = document.getElementById('categoryFilter');
        cats.forEach(c => {
            const o = document.createElement('option');
            o.value = c; o.textContent = c;
            sel.appendChild(o);
        });
    } catch (e) { console.error(e); }
}

async function loadInventory(silent = false) {
    try {
        allItems = await get('list');
        currentPage = 1;
        renderTable();
    } catch (e) {
        console.error(e);
        if (!silent) document.getElementById('invTableBody').innerHTML = `
            <tr><td colspan="10" class="inv-empty">
                <ion-icon name="cloud-offline-outline"></ion-icon>
                Could not connect. Check that XAMPP is running.
            </td></tr>`;
    }
}

function renderTable() {
    const search   = document.getElementById('searchInput').value.toLowerCase();
    const category = document.getElementById('categoryFilter').value;
    const status   = document.getElementById('statusFilter').value;

    const filtered = allItems.filter(it => {
        if (search && !it.name.toLowerCase().includes(search)) return false;
        if (category && it.category !== category) return false;
        if (status && it.stock_status !== status) return false;
        return true;
    });

    const totalPages = Math.max(1, Math.ceil(filtered.length / PER_PAGE));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage - 1) * PER_PAGE;
    const page  = filtered.slice(start, start + PER_PAGE);
    const tbody = document.getElementById('invTableBody');

    if (!page.length) {
        tbody.innerHTML = `<tr><td colspan="10" class="inv-empty"><ion-icon name="search-outline"></ion-icon>No items match your filters.</td></tr>`;
        document.getElementById('pagination').style.display = 'none';
        return;
    }

    tbody.innerHTML = page.map((it, idx) => {
        const pct     = parseFloat(it.stock_pct) || 0;
        const fillCls = it.stock_status === 'ok' ? 'fill-ok' : it.stock_status === 'low' ? 'fill-low' : 'fill-out';
        const badgeCls = `badge-${it.stock_status}`;
        const badgeTxt = it.stock_status === 'ok' ? 'In Stock' : it.stock_status === 'low' ? 'Low' : 'Out';
        const unit = it.unit ? esc(it.unit) : 'pcs';
        return `
            <tr>
                <td style="color:#bbb;font-size:13px;">${start + idx + 1}</td>
                <td><div class="prod-name">${esc(it.name)}</div><div class="prod-cat">${esc(it.category || '—')}</div></td>
                <td style="color:#888;font-size:13px;">${esc(it.category || '—')}</td>
                <td><span class="unit-chip">${unit}</span></td>
                <td><strong>${it.initial_stock}</strong> <span style="color:#ccc;font-size:12px;">${unit}</span></td>
                <td><strong>${it.remaining}</strong> <span style="color:#ccc;font-size:12px;">${unit}</span></td>
                <td>
                    <div class="stock-bar-wrap">
                        <div class="stock-bar-bg"><div class="stock-bar-fill ${fillCls}" style="width:${Math.min(pct,100)}%"></div></div>
                        <span class="stock-pct">${pct}%</span>
                    </div>
                </td>
                <td style="color:#888;">${it.total_sold ?? 0} <span style="color:#ccc;font-size:12px;">${unit}</span></td>
                <td><span class="status-badge ${badgeCls}">${badgeTxt}</span></td>
                <td>
                    <div class="row-actions">
                        <button class="btn-row btn-edit" onclick='openEditModal(${JSON.stringify(it)})'><ion-icon name="create-outline"></ion-icon> Edit</button>
                        <button class="btn-row btn-restock" onclick="openRestockModal(${it.id}, '${esc(it.name)}', '${unit}')"><ion-icon name="add-circle-outline"></ion-icon> Restock</button>
                        <button class="btn-row btn-delete" onclick="openDeleteModal(${it.id}, '${esc(it.name)}')"><ion-icon name="trash-outline"></ion-icon></button>
                    </div>
                </td>
            </tr>`;
    }).join('');

    document.getElementById('pgInfo').textContent = `Showing ${start + 1}–${Math.min(start + PER_PAGE, filtered.length)} of ${filtered.length} items`;
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

// ─────────────────────────────────────────────
// RAW INGREDIENTS
// ─────────────────────────────────────────────
async function loadRiStats() {
    try {
        const s = await get('ri_stats');
        document.getElementById('riTotal').textContent = s.total;
        document.getElementById('riOk').textContent    = s.ok;
        document.getElementById('riLow').textContent   = s.low;
        document.getElementById('riOut').textContent   = s.out;
        const alert = document.getElementById('riLowAlert');
        if (s.low > 0 || s.out > 0) {
            alert.style.display = 'flex';
            let msg = [];
            if (s.out > 0) msg.push(`${s.out} ingredient(s) are out of stock`);
            if (s.low > 0) msg.push(`${s.low} ingredient(s) are running low`);
            document.getElementById('riLowAlertText').textContent = msg.join(' · ') + '. Consider restocking soon.';
        } else {
            alert.style.display = 'none';
        }
    } catch (e) { console.error('RI Stats error:', e); }
}

async function loadRiCategories() {
    try {
        const cats = await get('ri_categories');
        const sel = document.getElementById('riCategoryFilter');
        cats.forEach(c => {
            const o = document.createElement('option');
            o.value = c; o.textContent = c;
            sel.appendChild(o);
        });
    } catch (e) { console.error(e); }
}

async function loadRiInventory(silent = false) {
    try {
        allRiItems = await get('ri_list');
        riCurrentPage = 1;
        renderRiTable();
    } catch (e) {
        console.error(e);
        if (!silent) document.getElementById('riTableBody').innerHTML = `
            <tr><td colspan="10" class="inv-empty">
                <ion-icon name="cloud-offline-outline"></ion-icon>
                Could not load ingredients.
            </td></tr>`;
    }
}

function renderRiTable() {
    const search   = document.getElementById('riSearchInput').value.toLowerCase();
    const category = document.getElementById('riCategoryFilter').value;
    const status   = document.getElementById('riStatusFilter').value;

    const filtered = allRiItems.filter(it => {
        if (search && !it.name.toLowerCase().includes(search) && !(it.supplier || '').toLowerCase().includes(search)) return false;
        if (category && it.category !== category) return false;
        if (status && it.stock_status !== status) return false;
        return true;
    });

    const totalPages = Math.max(1, Math.ceil(filtered.length / PER_PAGE));
    if (riCurrentPage > totalPages) riCurrentPage = totalPages;
    const start = (riCurrentPage - 1) * PER_PAGE;
    const page  = filtered.slice(start, start + PER_PAGE);
    const tbody = document.getElementById('riTableBody');

    if (!page.length) {
        tbody.innerHTML = `<tr><td colspan="10" class="inv-empty"><ion-icon name="leaf-outline"></ion-icon>No ingredients found. Add your first raw ingredient!</td></tr>`;
        document.getElementById('riPagination').style.display = 'none';
        return;
    }

    // FIX 1: Use JSON.stringify(it) directly — same pattern as products renderTable.
    // Previously used encodeURIComponent(JSON.stringify(it)) which passed a string
    // instead of an object to openRiEditModal, causing it.name etc. to be undefined.
    tbody.innerHTML = page.map((it, idx) => {
        const pct      = parseFloat(it.stock_pct) || 0;
        const fillCls  = it.stock_status === 'ok' ? 'fill-ok' : it.stock_status === 'low' ? 'fill-low' : 'fill-out';
        const badgeCls = `badge-${it.stock_status}`;
        const badgeTxt = it.stock_status === 'ok' ? 'In Stock' : it.stock_status === 'low' ? 'Low' : 'Out';
        const unit     = esc(it.unit || 'pcs');
        return `
            <tr>
                <td style="color:#bbb;font-size:13px;">${start + idx + 1}</td>
                <td>
                    <div class="prod-name">${esc(it.name)}</div>
                    ${it.notes ? `<div class="prod-cat" title="${esc(it.notes)}">${esc(it.notes.substring(0,40))}${it.notes.length>40?'…':''}</div>` : ''}
                </td>
                <td><span class="ing-chip">${esc(it.category || 'Ingredient')}</span></td>
                <td><span class="unit-chip">${unit}</span></td>
                <td><strong>${it.initial_stock}</strong> <span style="color:#ccc;font-size:12px;">${unit}</span></td>
                <td><strong>${it.remaining}</strong> <span style="color:#ccc;font-size:12px;">${unit}</span></td>
                <td>
                    <div class="stock-bar-wrap">
                        <div class="stock-bar-bg"><div class="stock-bar-fill ${fillCls}" style="width:${Math.min(pct,100)}%"></div></div>
                        <span class="stock-pct">${pct}%</span>
                    </div>
                </td>
                <td style="color:#888;font-size:13px;">${esc(it.supplier || '—')}</td>
                <td><span class="status-badge ${badgeCls}">${badgeTxt}</span></td>
                <td>
                    <div class="row-actions">
                        <button class="btn-row btn-edit" onclick='openRiEditModal(${JSON.stringify(it)})'><ion-icon name="create-outline"></ion-icon> Edit</button>
                        <button class="btn-row btn-restock" onclick="openRiRestockModal(${it.id}, '${esc(it.name)}', '${unit}')"><ion-icon name="add-circle-outline"></ion-icon> Restock</button>
                        <button class="btn-row btn-delete" onclick="openRiDeleteModal(${it.id}, '${esc(it.name)}')"><ion-icon name="trash-outline"></ion-icon></button>
                    </div>
                </td>
            </tr>`;
    }).join('');

    document.getElementById('riPgInfo').textContent = `Showing ${start + 1}–${Math.min(start + PER_PAGE, filtered.length)} of ${filtered.length} ingredients`;
    const pgBtns = document.getElementById('riPgBtns');
    pgBtns.innerHTML = '';
    for (let p = 1; p <= totalPages; p++) {
        const btn = document.createElement('button');
        btn.className = 'pg-btn' + (p === riCurrentPage ? ' active' : '');
        btn.textContent = p;
        btn.onclick = () => { riCurrentPage = p; renderRiTable(); };
        pgBtns.appendChild(btn);
    }
    document.getElementById('riPagination').style.display = 'flex';
}

// ─── Raw Ingredient unit helpers ───
function setRiUnit(val) {
    document.getElementById('riUnit').value = val;
    syncRiUnitPresets();
}
function syncRiUnitPresets() {
    const val = document.getElementById('riUnit').value.trim().toLowerCase();
    document.querySelectorAll('#riModal .unit-preset').forEach(btn => {
        btn.classList.toggle('active', btn.textContent.toLowerCase() === val);
    });
}

// ─── Raw Ingredient modals ───
function openRiAddModal() {
    document.getElementById('riModalTitle').textContent = 'Add Raw Ingredient';
    document.getElementById('riEditId').value    = '';
    document.getElementById('riName').value      = '';
    document.getElementById('riCategory').value  = '';
    document.getElementById('riUnit').value      = 'kg';
    document.getElementById('riInitial').value   = '';
    document.getElementById('riRemaining').value = '';
    document.getElementById('riThreshold').value = '10';
    document.getElementById('riSupplier').value  = '';
    document.getElementById('riNotes').value     = '';
    syncRiUnitPresets();
    riModal.show();
}

// FIX 1: Receives plain object directly via JSON.stringify in onclick — no parsing needed.
function openRiEditModal(it) {
    document.getElementById('riModalTitle').textContent = 'Edit – ' + it.name;
    document.getElementById('riEditId').value    = it.id;
    document.getElementById('riName').value      = it.name;
    document.getElementById('riCategory').value  = it.category || '';
    document.getElementById('riUnit').value      = it.unit || 'kg';
    document.getElementById('riInitial').value   = it.initial_stock;
    document.getElementById('riRemaining').value = it.remaining;
    document.getElementById('riThreshold').value = it.low_stock_threshold;
    document.getElementById('riSupplier').value  = it.supplier || '';
    document.getElementById('riNotes').value     = it.notes || '';
    syncRiUnitPresets();
    riModal.show();
}

async function saveRiItem() {
    const id        = document.getElementById('riEditId').value;
    const name      = document.getElementById('riName').value.trim();
    const category  = document.getElementById('riCategory').value.trim() || 'Ingredient';
    const unit      = document.getElementById('riUnit').value.trim() || 'kg';
    const initial   = parseFloat(document.getElementById('riInitial').value);
    const remaining = parseFloat(document.getElementById('riRemaining').value);
    const threshold = parseFloat(document.getElementById('riThreshold').value) || 10;
    const supplier  = document.getElementById('riSupplier').value.trim();
    const notes     = document.getElementById('riNotes').value.trim();

    if (!name) { toast('Ingredient name is required.', 'error'); return; }
    if (isNaN(initial) || isNaN(remaining)) { toast('Please fill in stock values.', 'error'); return; }
    if (remaining > initial) { toast('Remaining cannot exceed initial stock.', 'error'); return; }

    try {
        const action = id ? 'ri_update' : 'ri_add';
        const res = await post(action, { id, name, category, unit, initial_stock: initial, remaining, low_stock_threshold: threshold, supplier, notes });
        if (res.success) {
            riModal.hide();
            toast('Ingredient saved.', 'success');
            await loadRiInventory(true);
            await loadRiStats();
            // Refresh category dropdown if new category was added
            document.getElementById('riCategoryFilter').innerHTML = '<option value="">All Categories</option>';
            await loadRiCategories();
        } else {
            toast(res.error || 'Save failed.', 'error');
        }
    } catch (e) { toast('Network error.', 'error'); }
}

function openRiRestockModal(id, name, unit) {
    document.getElementById('riRestockId').value = id;
    document.getElementById('riRestockName').textContent = 'Restocking: ' + name;
    document.getElementById('riRestockUnitLabel').textContent = unit ? `(${unit})` : '';
    document.getElementById('riRestockAmount').value = '';
    riRestockModal.show();
}

async function doRiRestock() {
    const id     = document.getElementById('riRestockId').value;
    const amount = parseFloat(document.getElementById('riRestockAmount').value);
    if (!amount || amount <= 0) { toast('Enter a valid amount.', 'error'); return; }
    try {
        const res = await post('ri_restock', { id, amount });
        if (res.success) {
            riRestockModal.hide();
            toast(`Added ${amount} units to ingredient stock.`, 'success');
            await loadRiInventory(true);
            await loadRiStats();
        }
    } catch (e) { toast('Network error.', 'error'); }
}

function openRiDeleteModal(id, name) {
    document.getElementById('riDeleteId').value = id;
    document.getElementById('riDeleteName').textContent = name;
    riDeleteModal.show();
}

async function doRiDelete() {
    const id = document.getElementById('riDeleteId').value;
    try {
        const res = await post('ri_delete', { id });
        if (res.success) {
            riDeleteModal.hide();
            toast('Ingredient removed.', 'success');
            await loadRiInventory(true);
            await loadRiStats();
        }
    } catch (e) { toast('Network error.', 'error'); }
}

// ─────────────────────────────────────────────
// PRODUCT INVENTORY MODALS
// ─────────────────────────────────────────────
function setUnit(val) {
    document.getElementById('editUnit').value = val;
    syncUnitPresets();
}
function syncUnitPresets() {
    const val = document.getElementById('editUnit').value.trim().toLowerCase();
    document.querySelectorAll('#invModal .unit-preset').forEach(btn => {
        btn.classList.toggle('active', btn.textContent.toLowerCase() === val);
    });
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add / Update Stock';
    document.getElementById('editId').value      = '';
    document.getElementById('editUnit').value    = 'pcs';
    document.getElementById('editInitial').value = 50;
    document.getElementById('editRemaining').value = 50;
    document.getElementById('editThreshold').value = 10;
    syncUnitPresets();
    const sel = document.getElementById('editProductSelect');
    sel.innerHTML = '<option value="">— Select a product —</option>';
    allItems.forEach(it => {
        const o = document.createElement('option');
        o.value = it.product_id; o.textContent = it.name;
        sel.appendChild(o);
    });
    document.getElementById('productSelectWrap').style.display = 'block';
    editModal.show();
}

function openEditModal(it) {
    document.getElementById('modalTitle').textContent = 'Edit Stock – ' + it.name;
    document.getElementById('editId').value        = it.id;
    document.getElementById('editProductId').value = it.product_id;
    document.getElementById('editUnit').value      = it.unit || 'pcs';
    document.getElementById('editInitial').value   = it.initial_stock;
    document.getElementById('editRemaining').value = it.remaining;
    document.getElementById('editThreshold').value = it.low_stock_threshold;
    document.getElementById('productSelectWrap').style.display = 'none';
    syncUnitPresets();
    editModal.show();
}

async function saveItem() {
    const id        = document.getElementById('editId').value;
    const productId = document.getElementById('editProductId').value || document.getElementById('editProductSelect').value;
    const unit      = document.getElementById('editUnit').value.trim() || 'pcs';
    const initial   = parseInt(document.getElementById('editInitial').value);
    const remaining = parseInt(document.getElementById('editRemaining').value);
    const threshold = parseInt(document.getElementById('editThreshold').value) || 10;

    if (!productId)                        { toast('Please select a product.', 'error'); return; }
    if (isNaN(initial) || isNaN(remaining)){ toast('Please fill in all stock fields.', 'error'); return; }
    if (remaining > initial)               { toast('Remaining cannot exceed initial stock.', 'error'); return; }

    try {
        const action = id ? 'update' : 'add';
        const res = await post(action, { id, product_id: productId, unit, initial_stock: initial, remaining, low_stock_threshold: threshold });
        if (res.success) {
            editModal.hide();
            toast('Inventory saved successfully.', 'success');
            await loadInventory(true);
            await loadStats();
        } else {
            toast(res.error || 'Save failed.', 'error');
        }
    } catch (e) { toast('Network error.', 'error'); }
}

function openRestockModal(id, name, unit) {
    document.getElementById('restockId').value = id;
    document.getElementById('restockName').textContent = 'Restocking: ' + name;
    document.getElementById('restockUnitLabel').textContent = unit ? `(${unit})` : '';
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
    } catch (e) { toast('Network error.', 'error'); }
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
    } catch (e) { toast('Network error.', 'error'); }
}

// ─────────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────────
function esc(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function debounce(fn, ms) {
    let t;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
}
let toastTimer;
function toast(msg, type = 'success') {
    const el = document.getElementById('toast');
    el.textContent = msg;
    el.className = type;
    el.style.display = 'block';
    clearTimeout(toastTimer);
    // FIX 2: was 'n' (typo) — corrected to 'none'
    toastTimer = setTimeout(() => { el.style.display = 'none'; }, 3000);
}
</script>
</body>
</html>