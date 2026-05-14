<?php
session_start();
require 'db.php'; // your existing db connection

// ── Handle form submissions ──────────────────────────────
$message = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ADD product
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

    // EDIT product
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

    // DELETE product
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id) {
            try {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
                $stmt->execute([$id]);
                $message = "✓ Product deleted.";
                $msgType = 'success';
            } catch (PDOException $e) {
                $message = "✕ Error: " . $e->getMessage();
                $msgType = 'error';
            }
        }
    }
}

// ── Fetch all products ────────────────────────────────────
$products = [];
$result   = $pdo->query("SELECT * FROM products ORDER BY category, id");
if ($result) {
    $products = $result->fetchAll(PDO::FETCH_ASSOC);
}

// ── Categories used ───────────────────────────────────────
$categories = ['Mains', 'Combo Tenders', 'Sauces'];
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
    }

    /* ── Flash message ── */
    .flash {
      padding: 12px 20px;
      border-radius: 10px;
      font-family: var(--oswald);
      font-size: 15px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .flash.success { background: #e8f5e9; color: #2e7d32; border: 1.5px solid #a5d6a7; }
    .flash.error   { background: #fce4ec; color: #c62828; border: 1.5px solid #ef9a9a; }

    /* ── Toolbar ── */
    .menu-toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 28px;
      flex-wrap: wrap;
      gap: 14px;
    }
    .menu-toolbar h1 { margin: 0; font-size: 28px; font-family: var(--oswald); }

    .btn-add-product {
      background: var(--red);
      color: #fff;
      border: none;
      border-radius: 40px;
      padding: 10px 26px;
      font-family: var(--oswald);
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 3px 3px 0 var(--red-d);
      transition: transform .2s, box-shadow .2s;
    }
    .btn-add-product:hover { transform: translate(-1px,-1px); box-shadow: 5px 5px 0 var(--red-d); }

    /* ── Category filter tabs ── */
    .cat-tabs {
      display: flex;
      gap: 8px;
      margin-bottom: 22px;
      flex-wrap: wrap;
    }
    .cat-tab {
      font-family: var(--oswald);
      font-size: 14px;
      font-weight: 600;
      padding: 6px 18px;
      border-radius: 40px;
      border: 2px solid var(--border);
      cursor: pointer;
      background: #fff;
      transition: all .2s;
    }
    .cat-tab:hover, .cat-tab.active {
      background: var(--mustard);
      border-color: var(--mustard-d);
      color: var(--black);
    }

    /* ── Product table ── */
    .product-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      background: #fff;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0,0,0,.07);
    }
    .product-table thead tr {
      background: var(--mustard);
    }
    .product-table th {
      font-family: var(--oswald);
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      padding: 14px 18px;
      color: var(--black);
      border-bottom: 2px solid rgba(0,0,0,.1);
    }
    .product-table td {
      padding: 13px 18px;
      font-size: 14px;
      font-family: var(--barlow);
      border-bottom: 1px solid #f2f2f2;
      vertical-align: middle;
    }
    .product-table tbody tr:last-child td { border-bottom: none; }
    .product-table tbody tr:hover { background: #fffdf5; }

    .prod-img-cell {
      width: 60px; height: 60px;
      border-radius: 10px;
      overflow: hidden;
      background: var(--cream);
      display: flex; align-items: center; justify-content: center;
    }
    .prod-img-cell img {
      width: 100%; height: 100%; object-fit: cover;
    }
    .prod-img-cell .no-img {
      font-size: 22px;
      color: #ccc;
    }

    .prod-name { font-family: var(--oswald); font-size: 16px; font-weight: 600; }
    .prod-price { font-family: var(--oswald); font-size: 17px; font-weight: 700; color: var(--red); }

    .cat-badge {
      background: var(--cream);
      color: var(--black);
      font-family: var(--oswald);
      font-size: 12px;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 20px;
      border: 1.5px solid rgba(0,0,0,.1);
    }

    .btn-edit, .btn-delete {
      border: none;
      border-radius: 8px;
      padding: 6px 14px;
      font-family: var(--oswald);
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: opacity .2s;
    }
    .btn-edit   { background: var(--mustard); color: var(--black); margin-right: 6px; }
    .btn-delete { background: #fce4ec; color: var(--red); }
    .btn-edit:hover, .btn-delete:hover { opacity: .8; }

    .empty-row td {
      text-align: center;
      padding: 50px;
      color: #bbb;
      font-family: var(--oswald);
      font-size: 16px;
    }

    /* ── Modal ── */
    .modal-backdrop-custom {
      display: none;
      position: fixed; inset: 0;
      background: rgba(10,10,10,.6);
      backdrop-filter: blur(3px);
      z-index: 3000;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    .modal-backdrop-custom.open { display: flex; }

    .modal-box {
      background: #fff;
      border-radius: 18px;
      width: 100%; max-width: 500px;
      box-shadow: 0 20px 60px rgba(0,0,0,.22);
      animation: popIn .28s cubic-bezier(.34,1.56,.64,1) both;
      overflow: hidden;
    }
    @keyframes popIn {
      from { opacity:0; transform:scale(.88) translateY(16px); }
      to   { opacity:1; transform:scale(1)  translateY(0); }
    }

    .modal-head {
      background: var(--mustard);
      padding: 18px 24px;
      border-bottom: 2px solid var(--black);
      display: flex; align-items: center; justify-content: space-between;
    }
    .modal-head h2 { font-family: var(--oswald); font-size: 22px; font-weight: 700; margin: 0; }
    .modal-close {
      background: none; border: 2px solid var(--black);
      width: 34px; height: 34px; border-radius: 50%;
      font-size: 18px; font-weight: 700;
      cursor: pointer; display: flex; align-items: center; justify-content: center;
      transition: background .2s;
    }
    .modal-close:hover { background: rgba(0,0,0,.08); }

    .modal-body { padding: 28px 28px 24px; }

    .form-label {
      font-family: var(--oswald);
      font-size: 13px;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: var(--mid);
      margin-bottom: 6px;
      display: block;
    }
    .form-control, .form-select {
      font-family: var(--barlow) !important;
      font-size: 15px !important;
      border: 2px solid #e0e0e0 !important;
      border-radius: 10px !important;
      padding: 10px 14px !important;
      transition: border-color .2s !important;
    }
    .form-control:focus, .form-select:focus {
      border-color: var(--mustard-d) !important;
      box-shadow: 0 0 0 3px rgba(255,222,89,.25) !important;
      outline: none !important;
    }

    .img-preview-wrap {
      margin-top: 10px;
      width: 80px; height: 80px;
      border-radius: 10px; overflow: hidden;
      background: var(--cream);
      display: none;
      border: 2px solid #e0e0e0;
    }
    .img-preview-wrap img { width: 100%; height: 100%; object-fit: cover; }

    .modal-footer-btns {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 24px;
    }
    .btn-cancel {
      background: #f5f5f5; color: var(--black);
      border: 2px solid #e0e0e0; border-radius: 40px;
      padding: 10px 24px; font-family: var(--oswald);
      font-size: 15px; font-weight: 600; cursor: pointer;
      transition: background .2s;
    }
    .btn-cancel:hover { background: #eee; }
    .btn-submit {
      background: var(--red); color: #fff;
      border: none; border-radius: 40px;
      padding: 10px 28px; font-family: var(--oswald);
      font-size: 15px; font-weight: 600; cursor: pointer;
      box-shadow: 3px 3px 0 var(--red-d);
      transition: transform .2s, box-shadow .2s;
    }
    .btn-submit:hover { transform: translate(-1px,-1px); box-shadow: 5px 5px 0 var(--red-d); }

    /* Delete confirm modal */
    .delete-msg {
      font-family: var(--barlow);
      font-size: 15px;
      color: var(--mid);
      margin-bottom: 6px;
    }
    .delete-name {
      font-family: var(--oswald);
      font-size: 20px;
      font-weight: 700;
      color: var(--black);
    }
  </style>
  <title>Menu Manager — Chick Chicken Admin</title>
</head>
<body>

<!-- ═══════════════════════════════════════ SIDEBAR (matching admin.html) ═══ -->
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
          <li><a href="admin.php" class="header_button">
            <ion-icon name="grid-outline"></ion-icon><span>Dashboard</span>
          </a></li>
          <li><a href="orders--admin.php" class="header_button">
            <ion-icon name="bag-handle-outline"></ion-icon><span>Orders</span>
          </a></li>
          <li><a href="menu--admin.php" class="header_button active">
            <ion-icon name="book-outline"></ion-icon><span>Menus</span>
          </a></li>
          <li><a href="inventory.php" class="header_button">
            <ion-icon name="clipboard-outline"></ion-icon><span>Inventory</span>
          </a></li>
        </ul>
      </nav>
    </div>
  </div>
</header>

<!-- ═══════════════════════════════════════ MAIN CONTENT ═══ -->
<main class="main-content">
  <section class="page-content active">

    <!-- Flash message -->
    <?php if ($message): ?>
    <div class="flash <?= $msgType ?>">
      <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <!-- Toolbar -->
    <div class="menu-toolbar">
      <h1>Menu Manager</h1>
      <button class="btn-add-product" id="btn-open-add">+ Add Product</button>
    </div>

    <!-- Category tabs -->
    <div class="cat-tabs">
      <button class="cat-tab active" data-cat="all">All</button>
      <?php foreach ($categories as $cat): ?>
      <button class="cat-tab" data-cat="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></button>
      <?php endforeach; ?>
    </div>

    <!-- Products table -->
    <table class="product-table" id="productTable">
      <thead>
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Category</th>
          <th>Image Path</th>
          <th>Actions</th>
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
                data-image="<?= htmlspecialchars($p['image'] ?? '') ?>">
                Edit
              </button>
              <button class="btn-delete"
                data-id="<?= $p['id'] ?>"
                data-name="<?= htmlspecialchars($p['name']) ?>">
                Delete
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

  </section>
</main>

<!-- ═══════════════════════════════════ ADD/EDIT MODAL ═══ -->
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

<!-- ═══════════════════════════════════ DELETE CONFIRM MODAL ═══ -->
<div class="modal-backdrop-custom" id="deleteModal">
  <div class="modal-box">
    <div class="modal-head" style="background:#fce4ec;">
      <h2 style="color:var(--red);">Delete Product</h2>
      <button class="modal-close" id="deleteClose">✕</button>
    </div>
    <div class="modal-body">
      <p class="delete-msg">Are you sure you want to delete:</p>
      <div class="delete-name" id="deleteProductName"></div>
      <p class="delete-msg" style="margin-top:10px;">This cannot be undone.</p>
      <form method="POST" action="">
        <input type="hidden" name="action" value="delete"/>
        <input type="hidden" name="id"     id="deleteId" value=""/>
        <div class="modal-footer-btns">
          <button type="button" class="btn-cancel" id="deleteCancelBtn">Cancel</button>
          <button type="submit" class="btn-submit" style="background:#c62828;box-shadow:3px 3px 0 #8b0000;">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="admin.js"></script>
<script>
const productModal = document.getElementById('productModal');
const deleteModal  = document.getElementById('deleteModal');

// ── Open ADD modal ──────────────────────────────────────
document.getElementById('btn-open-add').addEventListener('click', () => {
  document.getElementById('modalTitle').textContent  = 'Add Product';
  document.getElementById('formSubmit').textContent  = 'Add Product';
  document.getElementById('formAction').value = 'add';
  document.getElementById('formId').value     = '';
  document.getElementById('formName').value   = '';
  document.getElementById('formPrice').value  = '';
  document.getElementById('formCategory').value = '';
  document.getElementById('formImage').value  = '';
  document.getElementById('imgPreviewWrap').style.display = 'none';
  productModal.classList.add('open');
});

// ── Open EDIT modal ─────────────────────────────────────
document.querySelectorAll('.btn-edit').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('modalTitle').textContent  = 'Edit Product';
    document.getElementById('formSubmit').textContent  = 'Save Changes';
    document.getElementById('formAction').value = 'edit';
    document.getElementById('formId').value     = btn.dataset.id;
    document.getElementById('formName').value   = btn.dataset.name;
    document.getElementById('formPrice').value  = btn.dataset.price;
    document.getElementById('formCategory').value = btn.dataset.category;
    document.getElementById('formImage').value  = btn.dataset.image;
    updateImgPreview(btn.dataset.image);
    productModal.classList.add('open');
  });
});

// ── Close ADD/EDIT ──────────────────────────────────────
['modalClose','modalCancel'].forEach(id => {
  document.getElementById(id).addEventListener('click', () => productModal.classList.remove('open'));
});
productModal.addEventListener('click', e => { if (e.target === productModal) productModal.classList.remove('open'); });

// ── Delete modal ────────────────────────────────────────
document.querySelectorAll('.btn-delete').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('deleteProductName').textContent = btn.dataset.name;
    document.getElementById('deleteId').value = btn.dataset.id;
    deleteModal.classList.add('open');
  });
});
['deleteClose','deleteCancelBtn'].forEach(id => {
  document.getElementById(id).addEventListener('click', () => deleteModal.classList.remove('open'));
});
deleteModal.addEventListener('click', e => { if (e.target === deleteModal) deleteModal.classList.remove('open'); });

// ── Image preview ───────────────────────────────────────
function updateImgPreview(path) {
  const wrap = document.getElementById('imgPreviewWrap');
  const img  = document.getElementById('imgPreview');
  if (path) {
    img.src = path;
    wrap.style.display = 'block';
    img.onerror = () => { wrap.style.display = 'none'; };
  } else {
    wrap.style.display = 'none';
  }
}
document.getElementById('formImage').addEventListener('input', e => updateImgPreview(e.target.value));

// ── Category tab filter ─────────────────────────────────
document.querySelectorAll('.cat-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    const cat = tab.dataset.cat;
    document.querySelectorAll('#productTable tbody tr').forEach(row => {
      if (cat === 'all' || row.dataset.cat === cat) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
});
</script>

</body>
</html>