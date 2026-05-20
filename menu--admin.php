<?php
session_start();
require 'db.php';

$message = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ADD
    if ($action === 'add') {
        $name     = trim($_POST['name'] ?? '');
        $price    = floatval($_POST['price'] ?? 0);
        $category = trim($_POST['category'] ?? '');
        $image    = trim($_POST['image'] ?? '');
        if ($name && $price > 0) {
            try {
                $stmt = $pdo->prepare("INSERT INTO products (name, price, category, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $price, $category, $image]);
                $message = "✓ Product \"$name\" added successfully.";
                $msgType = 'success';
            } catch (PDOException $e) {
                $message = "✕ Error: " . $e->getMessage();
                $msgType = 'error';
            }
        } else {
            $message = "✕ Name and price are required.";
            $msgType = 'error';
        }
    }

    // EDIT
    if ($action === 'edit') {
        $id       = intval($_POST['id'] ?? 0);
        $name     = trim($_POST['name'] ?? '');
        $price    = floatval($_POST['price'] ?? 0);
        $category = trim($_POST['category'] ?? '');
        $image    = trim($_POST['image'] ?? '');
        if ($id && $name && $price > 0) {
            try {
                $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, category=?, image=? WHERE id=?");
                $stmt->execute([$name, $price, $category, $image, $id]);
                $message = "✓ Product updated successfully.";
                $msgType = 'success';
            } catch (PDOException $e) {
                $message = "✕ Error: " . $e->getMessage();
                $msgType = 'error';
            }
        } else {
            $message = "✕ All fields required.";
            $msgType = 'error';
        }
    }

    // SOFT DELETE
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id) {
            try {
                $stmt = $pdo->prepare("UPDATE products SET deleted_at = NOW() WHERE id=?");
                $stmt->execute([$id]);
                $message = "✓ Product moved to Trash.";
                $msgType = 'success';
            } catch (PDOException $e) {
                $message = "✕ Error: " . $e->getMessage();
                $msgType = 'error';
            }
        }
    }

    // RESTORE
    if ($action === 'restore') {
        $id = intval($_POST['id'] ?? 0);
        if ($id) {
            try {
                $stmt = $pdo->prepare("UPDATE products SET deleted_at = NULL WHERE id=?");
                $stmt->execute([$id]);
                $message = "✓ Product restored successfully.";
                $msgType = 'success';
            } catch (PDOException $e) {
                $message = "✕ Error: " . $e->getMessage();
                $msgType = 'error';
            }
        }
    }

    // PERMANENT DELETE
    if ($action === 'permanent_delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id) {
            try {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id=? AND deleted_at IS NOT NULL");
                $stmt->execute([$id]);
                $message = "✓ Product permanently deleted.";
                $msgType = 'success';
            } catch (PDOException $e) {
                $message = "✕ Error: " . $e->getMessage();
                $msgType = 'error';
            }
        }
    }
}

// Fetch active products
$products = [];
$result = $pdo->query("SELECT * FROM products WHERE deleted_at IS NULL ORDER BY category, id");
if ($result) $products = $result->fetchAll(PDO::FETCH_ASSOC);

// Fetch deleted products
$trashed = [];
$trashedResult = $pdo->query("SELECT * FROM products WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC");
if ($trashedResult) $trashed = $trashedResult->fetchAll(PDO::FETCH_ASSOC);

// Categories
$catResult  = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' AND deleted_at IS NULL ORDER BY category");
$categories = $catResult ? $catResult->fetchAll(PDO::FETCH_COLUMN) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/png" href="assets/Logo.png"/>
  <link rel="stylesheet" href="admin.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700&family=Oswald:wght@200..700&display=swap');

    :root {
      --mustard:   #FFDE59;
      --mustard-d: #e8c73c;
      --red:       #D62828;
      --red-d:     #a81f1f;
      --black:     #111111;
      --offwhite:  #FAF8F3;
      --cream:     #FFF7E1;
      --mid:       #6b6b6b;
      --border:    rgba(0,0,0,0.09);
      --oswald:    'Oswald', sans-serif;
      --barlow:    'Alegreya Sans', sans-serif;
      --green:     #2e7d32;
      --green-d:   #1b5e20;
    }

    .flash {
      padding: 12px 20px; border-radius: 10px;
      font-family: var(--oswald); font-size: 15px;
      margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
    }
    .flash.success { background:#e8f5e9; color:#2e7d32; border:1.5px solid #a5d6a7; }
    .flash.error   { background:#fce4ec; color:#c62828; border:1.5px solid #ef9a9a; }

    .menu-toolbar {
      display:flex; align-items:center; justify-content:space-between;
      margin-bottom:28px; flex-wrap:wrap; gap:14px;
    }
    .menu-toolbar h1 { margin:0; font-size:28px; font-family:var(--oswald); }

    .toolbar-actions { display:flex; gap:10px; align-items:center; }

    .btn-add-product {
      background:var(--red); color:#fff; border:none; border-radius:40px;
      padding:10px 26px; font-family:var(--oswald); font-size:16px; font-weight:600;
      cursor:pointer; box-shadow:3px 3px 0 var(--red-d); transition:transform .2s,box-shadow .2s;
    }
    .btn-add-product:hover { transform:translate(-1px,-1px); box-shadow:5px 5px 0 var(--red-d); }

    .btn-trash-toggle {
      background:#fff; color:var(--mid); border:2px solid #e0e0e0; border-radius:40px;
      padding:9px 20px; font-family:var(--oswald); font-size:15px; font-weight:600;
      cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:7px;
    }
    .btn-trash-toggle:hover { border-color:#aaa; color:var(--black); }
    .btn-trash-toggle.active { background:#fce4ec; color:var(--red); border-color:#ef9a9a; }

    .trash-count {
      background:var(--red); color:#fff; font-size:11px; font-weight:700;
      border-radius:20px; padding:1px 7px; font-family:var(--oswald);
    }

    /* View toggle */
    #trashView { display:none; }
    #trashView.active { display:block; }
    #activeView.hidden { display:none; }

    .view-label {
      font-family:var(--oswald); font-size:13px; font-weight:700;
      letter-spacing:1px; text-transform:uppercase; color:var(--mid);
      margin-bottom:14px; display:flex; align-items:center; gap:8px;
    }
    .trash-banner {
      background:#fff8e1; border:1.5px solid #ffe082; border-radius:10px;
      padding:12px 18px; margin-bottom:18px;
      font-family:var(--barlow); font-size:14px; color:#795548;
      display:flex; align-items:center; gap:9px;
    }

    .cat-tabs {
      display:flex; gap:8px; margin-bottom:22px; flex-wrap:wrap;
    }
    .cat-tab {
      font-family:var(--oswald); font-size:14px; font-weight:600;
      padding:6px 18px; border-radius:40px; border:2px solid var(--border);
      cursor:pointer; background:#fff; transition:all .2s;
    }
    .cat-tab:hover, .cat-tab.active {
      background:var(--mustard); border-color:var(--mustard-d); color:var(--black);
    }

    .product-table {
      width:100%; border-collapse:separate; border-spacing:0;
      background:#fff; border-radius:14px; overflow:hidden;
      box-shadow:0 2px 12px rgba(0,0,0,.07);
    }
    .product-table thead tr { background:var(--mustard); }
    .product-table th {
      font-family:var(--oswald); font-size:13px; font-weight:700;
      letter-spacing:1px; text-transform:uppercase; padding:14px 18px;
      color:var(--black); border-bottom:2px solid rgba(0,0,0,.1);
    }
    .product-table td {
      padding:13px 18px; font-size:14px; font-family:var(--barlow);
      border-bottom:1px solid #f2f2f2; vertical-align:middle;
    }
    .product-table tbody tr:last-child td { border-bottom:none; }
    .product-table tbody tr:hover { background:#fffdf5; }

    /* Trash table styling */
    .trash-table thead tr { background:#fce4ec !important; }
    .trash-table tbody tr:hover { background:#fff5f5 !important; }
    .deleted-row td { opacity: .75; }

    .prod-img-cell {
      width:60px; height:60px; border-radius:10px; overflow:hidden;
      background:var(--cream); display:flex; align-items:center; justify-content:center;
    }
    .prod-img-cell img { width:100%; height:100%; object-fit:cover; }
    .prod-img-cell .no-img { font-size:22px; color:#ccc; }

    .prod-name  { font-family:var(--oswald); font-size:16px; font-weight:600; }
    .prod-price { font-family:var(--oswald); font-size:17px; font-weight:700; color:var(--red); }

    .cat-badge {
      background:var(--cream); color:var(--black);
      font-family:var(--oswald); font-size:12px; font-weight:700;
      padding:3px 10px; border-radius:20px; border:1.5px solid rgba(0,0,0,.1);
    }

    .btn-edit, .btn-delete, .btn-restore, .btn-perm-delete {
      border:none; border-radius:8px; padding:6px 14px;
      font-family:var(--oswald); font-size:13px; font-weight:600;
      cursor:pointer; transition:opacity .2s;
    }
    .btn-edit        { background:var(--mustard);  color:var(--black); margin-right:6px; }
    .btn-delete      { background:#fce4ec;          color:var(--red); }
    .btn-restore     { background:#e8f5e9;          color:var(--green); margin-right:6px; }
    .btn-perm-delete { background:#fce4ec;          color:var(--red); }
    .btn-edit:hover, .btn-delete:hover, .btn-restore:hover, .btn-perm-delete:hover { opacity:.8; }

    .deleted-date {
      font-size:11px; color:#aaa; font-family:var(--barlow);
      white-space:nowrap;
    }

    .empty-row td {
      text-align:center; padding:50px; color:#bbb;
      font-family:var(--oswald); font-size:16px;
    }

    /* ── Modals ── */
    .modal-backdrop-custom {
      display:none; position:fixed; inset:0;
      background:rgba(10,10,10,.6); backdrop-filter:blur(3px);
      z-index:3000; justify-content:center; align-items:center; padding:20px;
    }
    .modal-backdrop-custom.open { display:flex; }

    .modal-box {
      background:#fff; border-radius:18px; width:100%; max-width:500px;
      box-shadow:0 20px 60px rgba(0,0,0,.22);
      animation:popIn .28s cubic-bezier(.34,1.56,.64,1) both; overflow:hidden;
    }
    @keyframes popIn {
      from { opacity:0; transform:scale(.88) translateY(16px); }
      to   { opacity:1; transform:scale(1)  translateY(0); }
    }

    .modal-head {
      background:var(--mustard); padding:18px 24px;
      border-bottom:2px solid var(--black);
      display:flex; align-items:center; justify-content:space-between;
    }
    .modal-head h2 { font-family:var(--oswald); font-size:22px; font-weight:700; margin:0; }
    .modal-close {
      background:none; border:2px solid var(--black);
      width:34px; height:34px; border-radius:50%;
      font-size:18px; font-weight:700;
      cursor:pointer; display:flex; align-items:center; justify-content:center;
      transition:background .2s;
    }
    .modal-close:hover { background:rgba(0,0,0,.08); }

    .modal-body { padding:28px 28px 24px; }

    .form-label {
      font-family:var(--oswald); font-size:13px; font-weight:600;
      letter-spacing:1px; text-transform:uppercase; color:var(--mid);
      margin-bottom:6px; display:block;
    }
    .form-control, .form-select {
      font-family:var(--barlow) !important; font-size:15px !important;
      border:2px solid #e0e0e0 !important; border-radius:10px !important;
      padding:10px 14px !important; transition:border-color .2s !important;
    }
    .form-control:focus, .form-select:focus {
      border-color:var(--mustard-d) !important;
      box-shadow:0 0 0 3px rgba(255,222,89,.25) !important;
      outline:none !important;
    }

    .img-preview-wrap {
      margin-top:10px; width:80px; height:80px;
      border-radius:10px; overflow:hidden; background:var(--cream);
      display:none; border:2px solid #e0e0e0;
    }
    .img-preview-wrap img { width:100%; height:100%; object-fit:cover; }

    .modal-footer-btns { display:flex; justify-content:flex-end; gap:10px; margin-top:24px; }
    .btn-cancel {
      background:#f5f5f5; color:var(--black); border:2px solid #e0e0e0; border-radius:40px;
      padding:10px 24px; font-family:var(--oswald); font-size:15px; font-weight:600;
      cursor:pointer; transition:background .2s;
    }
    .btn-cancel:hover { background:#eee; }
    .btn-submit {
      background:var(--red); color:#fff; border:none; border-radius:40px;
      padding:10px 28px; font-family:var(--oswald); font-size:15px; font-weight:600;
      cursor:pointer; box-shadow:3px 3px 0 var(--red-d);
      transition:transform .2s,box-shadow .2s;
    }
    .btn-submit:hover { transform:translate(-1px,-1px); box-shadow:5px 5px 0 var(--red-d); }
    .btn-submit:disabled { opacity:.45; cursor:not-allowed; transform:none !important; box-shadow:3px 3px 0 var(--red-d) !important; }

    /* Delete confirm specifics */
    .delete-msg  { font-family:var(--barlow); font-size:15px; color:var(--mid); margin-bottom:6px; }
    .delete-name { font-family:var(--oswald); font-size:20px; font-weight:700; color:var(--black); }

    .confirm-input-wrap { margin-top:18px; }
    .confirm-hint {
      font-family:var(--barlow); font-size:13px; color:var(--mid); margin-bottom:8px;
    }
    .confirm-hint strong { color:var(--black); }
    .confirm-field {
      font-family:var(--barlow) !important; font-size:15px !important;
      border:2px solid #e0e0e0 !important; border-radius:10px !important;
      padding:10px 14px !important; width:100%; transition:border-color .2s !important;
      outline:none;
    }
    .confirm-field.valid   { border-color:#a5d6a7 !important; background:#f1f8e9; }
    .confirm-field.invalid { border-color:#ef9a9a !important; background:#fff5f5; }

    /* Restore confirm */
    .restore-name { font-family:var(--oswald); font-size:20px; font-weight:700; color:var(--black); }
    .btn-restore-confirm {
      background:var(--green); color:#fff; border:none; border-radius:40px;
      padding:10px 28px; font-family:var(--oswald); font-size:15px; font-weight:600;
      cursor:pointer; box-shadow:3px 3px 0 var(--green-d);
      transition:transform .2s,box-shadow .2s;
    }
    .btn-restore-confirm:hover { transform:translate(-1px,-1px); box-shadow:5px 5px 0 var(--green-d); }
  </style>
  <title>Menu Manager — Chick Chicken Admin</title>
</head>
<body>

<!-- SIDEBAR -->
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
          <li><a href="admin.php" class="header_button"><ion-icon name="grid-outline"></ion-icon><span>Dashboard</span></a></li>
          <li><a href="admin_sales_report.php" class="header_button"><ion-icon name="bar-chart-outline"></ion-icon><span>Sales Report</span></a></li>
          <li><a href="orders--admin.php" class="header_button"><ion-icon name="bag-handle-outline"></ion-icon><span>Orders</span></a></li>
          <li><a href="menu--admin.php" class="header_button active"><ion-icon name="book-outline"></ion-icon><span>Menus</span></a></li>
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
  </div>
</header>

<!-- MAIN CONTENT -->
<main class="main-content">
  <section class="page-content active">

    <?php if ($message): ?>
    <div class="flash <?= $msgType ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Toolbar -->
    <div class="menu-toolbar">
      <h1>Menu Manager</h1>
      <div class="toolbar-actions">
        <button class="btn-trash-toggle" id="btn-trash-toggle">
          🗑 Trash
          <?php if (count($trashed) > 0): ?>
          <span class="trash-count"><?= count($trashed) ?></span>
          <?php endif; ?>
        </button>
        <button class="btn-add-product" id="btn-open-add">+ Add Product</button>
      </div>
    </div>

    <!-- ═══ ACTIVE PRODUCTS VIEW ═══ -->
    <div id="activeView">
      <div class="cat-tabs">
        <button class="cat-tab active" data-cat="all">All</button>
        <?php foreach ($categories as $cat): ?>
        <button class="cat-tab" data-cat="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></button>
        <?php endforeach; ?>
      </div>

      <table class="product-table" id="productTable">
        <thead>
          <tr>
            <th>Image</th><th>Name</th><th>Price</th><th>Category</th><th>Image Path</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($products)): ?>
          <tr class="empty-row"><td colspan="6">No products yet. Add one above!</td></tr>
          <?php else: ?>
            <?php foreach ($products as $p): ?>
            <tr data-cat="<?= htmlspecialchars($p['category'] ?? '') ?>">
              <td>
                <div class="prod-img-cell">
                  <?php if ($p['image']): ?>
                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" onerror="this.style.display='none';this.nextElementSibling.style.display='block'"/>
                    <span class="no-img" style="display:none">🍗</span>
                  <?php else: ?>
                    <span class="no-img">🍗</span>
                  <?php endif; ?>
                </div>
              </td>
              <td><div class="prod-name"><?= htmlspecialchars($p['name']) ?></div></td>
              <td><div class="prod-price">₱<?= number_format($p['price'], 2) ?></div></td>
              <td><?php if ($p['category']): ?><span class="cat-badge"><?= htmlspecialchars($p['category']) ?></span><?php else: ?><span style="color:#ccc;">—</span><?php endif; ?></td>
              <td style="font-size:12px;color:#aaa;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?= htmlspecialchars($p['image'] ?? '') ?>"><?= htmlspecialchars($p['image'] ?? '—') ?></td>
              <td>
                <button class="btn-edit"
                  data-id="<?= $p['id'] ?>"
                  data-name="<?= htmlspecialchars($p['name']) ?>"
                  data-price="<?= $p['price'] ?>"
                  data-category="<?= htmlspecialchars($p['category'] ?? '') ?>"
                  data-image="<?= htmlspecialchars($p['image'] ?? '') ?>">Edit</button>
                <button class="btn-delete"
                  data-id="<?= $p['id'] ?>"
                  data-name="<?= htmlspecialchars($p['name']) ?>">Delete</button>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- ═══ TRASH VIEW ═══ -->
    <div id="trashView">
      <div class="trash-banner">
        ℹ️ Items in Trash are hidden from your menu. Restore them to make them active again, or permanently delete them.
      </div>
      <table class="product-table trash-table">
        <thead>
          <tr>
            <th>Image</th><th>Name</th><th>Price</th><th>Category</th><th>Deleted</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($trashed)): ?>
          <tr class="empty-row"><td colspan="6">Trash is empty.</td></tr>
          <?php else: ?>
            <?php foreach ($trashed as $p): ?>
            <tr class="deleted-row">
              <td>
                <div class="prod-img-cell">
                  <?php if ($p['image']): ?>
                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="" onerror="this.style.display='none';this.nextElementSibling.style.display='block'"/>
                    <span class="no-img" style="display:none">🍗</span>
                  <?php else: ?>
                    <span class="no-img">🍗</span>
                  <?php endif; ?>
                </div>
              </td>
              <td><div class="prod-name"><?= htmlspecialchars($p['name']) ?></div></td>
              <td><div class="prod-price">₱<?= number_format($p['price'], 2) ?></div></td>
              <td><?php if ($p['category']): ?><span class="cat-badge"><?= htmlspecialchars($p['category']) ?></span><?php else: ?><span style="color:#ccc;">—</span><?php endif; ?></td>
              <td>
                <span class="deleted-date">
                  <?= date('M j, Y g:i A', strtotime($p['deleted_at'])) ?>
                </span>
              </td>
              <td>
                <button class="btn-restore"
                  data-id="<?= $p['id'] ?>"
                  data-name="<?= htmlspecialchars($p['name']) ?>">↩ Restore</button>
                <button class="btn-perm-delete"
                  data-id="<?= $p['id'] ?>"
                  data-name="<?= htmlspecialchars($p['name']) ?>">✕ Delete Forever</button>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </section>
</main>

<!-- ═══ ADD/EDIT MODAL ═══ -->
<div class="modal-backdrop-custom" id="productModal">
  <div class="modal-box">
    <div class="modal-head">
      <h2 id="modalTitle">Add Product</h2>
      <button class="modal-close" id="modalClose">✕</button>
    </div>
    <div class="modal-body">
      <form method="POST" action="" id="productForm">
        <input type="hidden" name="action" id="formAction" value="add"/>
        <input type="hidden" name="id"     id="formId"     value=""/>
        <div class="mb-3">
          <label class="form-label">Product Name *</label>
          <input type="text" name="name" id="formName" class="form-control" placeholder="e.g. Chick Rice" required/>
        </div>
        <div class="mb-3">
          <label class="form-label">Base Price (₱) *</label>
          <input type="number" name="price" id="formPrice" class="form-control" placeholder="169.00" step="0.01" min="0" required/>
        </div>
        <div class="mb-3">
          <label class="form-label">Category</label>
          <select name="category" id="formCategory" class="form-select">
            <option value="">— Select —</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Image Path</label>
          <input type="text" name="image" id="formImage" class="form-control" placeholder="menuassets/Chick_Rice.png"/>
          <small style="color:#aaa;font-size:12px;">Relative path from root, e.g. <code>menuassets/Chick_Rice.png</code></small>
          <div class="img-preview-wrap" id="imgPreviewWrap">
            <img id="imgPreview" src="" alt="Preview"/>
          </div>
        </div>
        <div class="modal-footer-btns">
          <button type="button" class="btn-cancel" id="modalCancel">Cancel</button>
          <button type="submit" class="btn-submit" id="formSubmit">Add Product</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ═══ DELETE CONFIRM MODAL ═══ -->
<div class="modal-backdrop-custom" id="deleteModal">
  <div class="modal-box">
    <div class="modal-head" style="background:#fce4ec;">
      <h2 style="color:var(--red);">🗑 Move to Trash?</h2>
      <button class="modal-close" id="deleteClose">✕</button>
    </div>
    <div class="modal-body">
      <p class="delete-msg">You're about to delete:</p>
      <div class="delete-name" id="deleteProductName"></div>
      <p class="delete-msg" style="margin-top:8px;">The product will be moved to Trash and can be restored later.</p>

      <div class="confirm-input-wrap">
        <p class="confirm-hint">Type <strong id="confirmHintName"></strong> to confirm:</p>
        <input type="text" class="confirm-field" id="confirmNameInput" placeholder="Type product name here…" autocomplete="off"/>
      </div>

      <form method="POST" action="" id="deleteForm">
        <input type="hidden" name="action" value="delete"/>
        <input type="hidden" name="id" id="deleteId" value=""/>
        <div class="modal-footer-btns">
          <button type="button" class="btn-cancel" id="deleteCancelBtn">Cancel</button>
          <button type="submit" class="btn-submit" id="deleteSubmitBtn" disabled>Move to Trash</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ═══ PERMANENT DELETE CONFIRM MODAL ═══ -->
<div class="modal-backdrop-custom" id="permDeleteModal">
  <div class="modal-box">
    <div class="modal-head" style="background:#fce4ec;">
      <h2 style="color:var(--red);">⚠️ Delete Forever?</h2>
      <button class="modal-close" id="permDeleteClose">✕</button>
    </div>
    <div class="modal-body">
      <p class="delete-msg">This will <strong>permanently</strong> remove:</p>
      <div class="delete-name" id="permDeleteProductName"></div>
      <p class="delete-msg" style="margin-top:8px;color:var(--red);font-weight:600;">This cannot be undone.</p>

      <div class="confirm-input-wrap">
        <p class="confirm-hint">Type <strong id="permConfirmHintName"></strong> to confirm:</p>
        <input type="text" class="confirm-field" id="permConfirmNameInput" placeholder="Type product name here…" autocomplete="off"/>
      </div>

      <form method="POST" action="" id="permDeleteForm">
        <input type="hidden" name="action" value="permanent_delete"/>
        <input type="hidden" name="id" id="permDeleteId" value=""/>
        <div class="modal-footer-btns">
          <button type="button" class="btn-cancel" id="permDeleteCancelBtn">Cancel</button>
          <button type="submit" class="btn-submit" id="permDeleteSubmitBtn" disabled style="background:#8b0000;box-shadow:3px 3px 0 #4a0000;">Delete Forever</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ═══ RESTORE CONFIRM MODAL ═══ -->
<div class="modal-backdrop-custom" id="restoreModal">
  <div class="modal-box">
    <div class="modal-head" style="background:#e8f5e9;">
      <h2 style="color:var(--green);">↩ Restore Product?</h2>
      <button class="modal-close" id="restoreClose">✕</button>
    </div>
    <div class="modal-body">
      <p class="delete-msg">This will restore the product and make it active again:</p>
      <div class="restore-name" id="restoreProductName"></div>
      <form method="POST" action="" id="restoreForm">
        <input type="hidden" name="action" value="restore"/>
        <input type="hidden" name="id" id="restoreId" value=""/>
        <div class="modal-footer-btns">
          <button type="button" class="btn-cancel" id="restoreCancelBtn">Cancel</button>
          <button type="submit" class="btn-restore-confirm">↩ Restore</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="admin.js"></script>
<script>
// ── Helpers ──────────────────────────────────────────────
const $  = id => document.getElementById(id);
const openModal  = id => $(id).classList.add('open');
const closeModal = id => $(id).classList.remove('open');

function bindClose(modalId, ...btnIds) {
  btnIds.forEach(id => $(id).addEventListener('click', () => closeModal(modalId)));
  $(modalId).addEventListener('click', e => { if (e.target === $(modalId)) closeModal(modalId); });
}

// ── Trash toggle ─────────────────────────────────────────
$('btn-trash-toggle').addEventListener('click', () => {
  const isTrash = $('trashView').classList.toggle('active');
  $('activeView').classList.toggle('hidden', isTrash);
  $('btn-open-add').style.display = isTrash ? 'none' : '';
  $('btn-trash-toggle').classList.toggle('active', isTrash);
  $('btn-trash-toggle').querySelector('span.trash-count') && ($('btn-trash-toggle').querySelector('span.trash-count').style.display = '');
});

// ── Add modal ────────────────────────────────────────────
$('btn-open-add').addEventListener('click', () => {
  $('modalTitle').textContent = 'Add Product';
  $('formSubmit').textContent = 'Add Product';
  $('formAction').value = 'add';
  $('formId').value = $('formName').value = $('formPrice').value = $('formImage').value = '';
  $('formCategory').value = '';
  $('imgPreviewWrap').style.display = 'none';
  openModal('productModal');
});
bindClose('productModal', 'modalClose', 'modalCancel');

// ── Edit modal ───────────────────────────────────────────
document.querySelectorAll('.btn-edit').forEach(btn => {
  btn.addEventListener('click', () => {
    $('modalTitle').textContent = 'Edit Product';
    $('formSubmit').textContent = 'Save Changes';
    $('formAction').value    = 'edit';
    $('formId').value        = btn.dataset.id;
    $('formName').value      = btn.dataset.name;
    $('formPrice').value     = btn.dataset.price;
    $('formCategory').value  = btn.dataset.category;
    $('formImage').value     = btn.dataset.image;
    updateImgPreview(btn.dataset.image);
    openModal('productModal');
  });
});

// ── Image preview ────────────────────────────────────────
function updateImgPreview(path) {
  const wrap = $('imgPreviewWrap'), img = $('imgPreview');
  if (path) {
    img.src = path;
    wrap.style.display = 'block';
    img.onerror = () => { wrap.style.display = 'none'; };
  } else {
    wrap.style.display = 'none';
  }
}
$('formImage').addEventListener('input', e => updateImgPreview(e.target.value));

// ── Delete modal (soft) ───────────────────────────────────
document.querySelectorAll('.btn-delete').forEach(btn => {
  btn.addEventListener('click', () => {
    const name = btn.dataset.name;
    $('deleteProductName').textContent = name;
    $('confirmHintName').textContent   = name;
    $('deleteId').value                = btn.dataset.id;
    $('confirmNameInput').value        = '';
    $('deleteSubmitBtn').disabled      = true;
    $('confirmNameInput').className    = 'confirm-field';
    openModal('deleteModal');
    setTimeout(() => $('confirmNameInput').focus(), 300);
  });
});
$('confirmNameInput').addEventListener('input', function() {
  const expected = $('deleteProductName').textContent.trim();
  const match    = this.value.trim() === expected;
  this.className = 'confirm-field ' + (this.value ? (match ? 'valid' : 'invalid') : '');
  $('deleteSubmitBtn').disabled = !match;
});
bindClose('deleteModal', 'deleteClose', 'deleteCancelBtn');

// ── Restore modal ─────────────────────────────────────────
document.querySelectorAll('.btn-restore').forEach(btn => {
  btn.addEventListener('click', () => {
    $('restoreProductName').textContent = btn.dataset.name;
    $('restoreId').value = btn.dataset.id;
    openModal('restoreModal');
  });
});
bindClose('restoreModal', 'restoreClose', 'restoreCancelBtn');

// ── Permanent delete modal ────────────────────────────────
document.querySelectorAll('.btn-perm-delete').forEach(btn => {
  btn.addEventListener('click', () => {
    const name = btn.dataset.name;
    $('permDeleteProductName').textContent = name;
    $('permConfirmHintName').textContent   = name;
    $('permDeleteId').value                = btn.dataset.id;
    $('permConfirmNameInput').value        = '';
    $('permDeleteSubmitBtn').disabled      = true;
    $('permConfirmNameInput').className    = 'confirm-field';
    openModal('permDeleteModal');
    setTimeout(() => $('permConfirmNameInput').focus(), 300);
  });
});
$('permConfirmNameInput').addEventListener('input', function() {
  const expected = $('permDeleteProductName').textContent.trim();
  const match    = this.value.trim() === expected;
  this.className = 'confirm-field ' + (this.value ? (match ? 'valid' : 'invalid') : '');
  $('permDeleteSubmitBtn').disabled = !match;
});
bindClose('permDeleteModal', 'permDeleteClose', 'permDeleteCancelBtn');

// ── Category tab filter ───────────────────────────────────
document.querySelectorAll('.cat-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    const cat = tab.dataset.cat.trim().toLowerCase();
    document.querySelectorAll('#productTable tbody tr').forEach(row => {
      if (row.classList.contains('empty-row')) return;
      const rowCat = (row.dataset.cat ?? '').trim().toLowerCase();
      row.style.display = (cat === 'all' || rowCat === cat) ? '' : 'none';
    });
  });
});
</script>
</body>
</html>