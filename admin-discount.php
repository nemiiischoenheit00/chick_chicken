<?php
session_start();
require_once 'db.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="assets/Logo.png" />
  <link rel="stylesheet" href="admin.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Chick Chicken – Discount Approvals</title>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');

    /* ── Page layout ── */
    .disc-page { padding: 0 28px 40px 28px; }
    .disc-page h1 {
      font-family: 'Oswald', sans-serif;
      font-size: 2rem; font-weight: 600;
      margin-bottom: 6px; color: #1a1a1a;
    }
    .disc-subtitle { color: #888; font-size: 13px; margin-bottom: 24px; }

    /* ── Stat cards ── */
    .disc-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px; }
    .disc-stat {
      background: #fff; border-radius: 14px; padding: 18px 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,.06);
      display: flex; align-items: center; gap: 14px;
    }
    .disc-stat-icon {
      width: 44px; height: 44px; border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px; flex-shrink: 0;
    }
    .disc-stat-icon.yellow { background: #fff8dc; color: #e0a800; }
    .disc-stat-icon.orange { background: #fff3e0; color: #e65c00; }
    .disc-stat-icon.green  { background: #e8f5e9; color: #2e7d32; }
    .disc-stat-icon.red    { background: #fce4ec; color: #c62828; }
    .disc-stat-label { font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
    .disc-stat-val   { font-size: 22px; font-weight: 800; color: #1a1a1a; line-height: 1.1; font-family: 'Oswald', sans-serif; }

    /* ── Toolbar ── */
    .disc-toolbar { display: flex; align-items: center; gap: 10px; margin-bottom: 18px; flex-wrap: wrap; }
    .disc-search { flex: 1; min-width: 200px; position: relative; }
    .disc-search input {
      width: 100%; border: 1.5px solid #e8e8e8; border-radius: 10px;
      padding: 9px 14px 9px 38px; font-size: 14px; outline: none;
      background: #fff; transition: border .2s;
    }
    .disc-search input:focus { border-color: #f5c800; }
    .disc-search .search-ico {
      position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
      color: #bbb; font-size: 16px; pointer-events: none;
    }
    .disc-filter-select {
      border: 1.5px solid #e8e8e8; border-radius: 10px;
      padding: 9px 14px; font-size: 13px; background: #fff;
      outline: none; cursor: pointer; transition: border .2s;
    }
    .disc-filter-select:focus { border-color: #f5c800; }

    /* ── Table card ── */
    .disc-table-card { background: #fff; border-radius: 16px; box-shadow: 0 2px 14px rgba(0,0,0,.07); overflow: hidden; }
    .disc-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .disc-table thead tr { background: #FFDE59; border-bottom: 2px solid rgba(0,0,0,.1); }
    .disc-table thead th {
      padding: 14px 18px; font-family: 'Oswald', sans-serif;
      font-weight: 700; font-size: 13px; text-transform: uppercase;
      letter-spacing: 1px; color: #111111; white-space: nowrap;
    }
    .disc-table tbody tr { border-bottom: 1px solid #f5f5f5; transition: background .15s; }
    .disc-table tbody tr:last-child { border-bottom: none; }
    .disc-table tbody tr:hover { background: #fffdf0; }
    .disc-table td { padding: 13px 16px; vertical-align: middle; }

    .applicant-name  { font-weight: 700; color: #1a1a1a; }
    .applicant-email { font-size: 11px; color: #bbb; margin-top: 2px; }

    /* ── Status badges ── */
    .status-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; }
    .badge-pending  { background: #fff8dc; color: #b45309; }
    .badge-approved { background: #e8f5e9; color: #2e7d32; }
    .badge-rejected { background: #fce4ec; color: #c62828; }

    /* ── Discount type chip ── */
    .type-chip { display: inline-flex; align-items: center; gap: 5px; background: #f5f5f5; color: #555; border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; }
    .type-chip.senior  { background: #e3f2fd; color: #1565c0; }
    .type-chip.pwd     { background: #f3e5f5; color: #6a1b9a; }
    .type-chip.student { background: #e8f5e9; color: #2e7d32; }

    /* ── Row action buttons ── */
    .row-actions { display: flex; gap: 6px; flex-wrap: wrap; }
    .btn-row {
      border: none; border-radius: 8px; padding: 6px 10px;
      font-size: 12px; font-weight: 700; cursor: pointer;
      transition: opacity .15s, transform .1s;
      display: flex; align-items: center; gap: 4px;
    }
    .btn-row:hover { opacity: .8; transform: translateY(-1px); }
    .btn-view    { background: #e8f4fd; color: #1565c0; }
    .btn-approve { background: #e8f5e9; color: #2e7d32; }
    .btn-reject  { background: #fce4ec; color: #c62828; }

    /* ── ID image thumb ── */
    .id-thumb {
      width: 44px; height: 44px; object-fit: cover;
      border-radius: 8px; border: 1.5px solid #eee;
      cursor: zoom-in;
      transition: transform .15s, box-shadow .15s;
    }
    .id-thumb:hover { transform: scale(1.12); box-shadow: 0 4px 14px rgba(0,0,0,.18); }

    /* ── Empty / loading ── */
    .disc-empty { text-align: center; padding: 50px 20px; color: #bbb; font-size: 14px; }
    .disc-empty ion-icon { font-size: 40px; display: block; margin: 0 auto 10px; }
    .skeleton {
      display: inline-block;
      background: linear-gradient(90deg,#eee 25%,#f8f8f8 50%,#eee 75%);
      background-size: 200% 100%;
      animation: shimmer 1.2s infinite;
      border-radius: 4px; height: 1em;
    }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

    /* ── Modal tweaks ── */
    .modal-header { background: #1a1a1a; color: #fff; border-radius: 12px 12px 0 0; }
    .modal-header .btn-close { filter: invert(1); }
    .modal-title { font-family: 'Oswald', sans-serif; font-size: 1.2rem; font-weight: 600; }
    .modal-content { border-radius: 14px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,.18); }
    .modal-footer { border-top: 1px solid #f0f0f0; }
    .form-label { font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; color: #555; }
    .form-control, .form-select { border: 1.5px solid #e8e8e8; border-radius: 9px; font-size: 14px; transition: border .2s; }
    .form-control:focus, .form-select:focus { border-color: #f5c800; box-shadow: 0 0 0 3px rgba(245,200,0,.15); }
    .btn-approve-modal { background: #2e7d32; border: none; color: #fff; font-weight: 800; border-radius: 9px; padding: 10px 24px; }
    .btn-approve-modal:hover { background: #1b5e20; color: #fff; }
    .btn-reject-modal  { background: #c62828; border: none; color: #fff; font-weight: 800; border-radius: 9px; padding: 10px 24px; }
    .btn-reject-modal:hover { background: #8d1c1c; color: #fff; }
    .btn-cancel { border-radius: 9px; }

    /* Applicant detail rows */
    .detail-row { display: flex; gap: 16px; margin-bottom: 14px; }
    .detail-field { flex: 1; }
    .detail-field label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #bbb; display: block; margin-bottom: 4px; }
    .detail-field span { font-size: 15px; color: #222; font-weight: 500; }

    /* Full ID image in modal — zoomable */
    .id-preview-wrap {
      border-radius: 10px; overflow: hidden;
      border: 1.5px solid #eee;
      margin-bottom: 8px;
      background: #f9f9f9;
      display: flex; align-items: center; justify-content: center;
      min-height: 180px;
      cursor: zoom-in;
      transition: border-color .2s;
    }
    .id-preview-wrap:hover { border-color: #aaa; }
    .id-preview-wrap img {
      max-width: 100%; max-height: 320px;
      object-fit: contain; display: block;
      transition: transform .2s;
    }
    .id-preview-wrap:hover img { transform: scale(1.02); }
    .id-zoom-hint { font-size: 11px; color: #bbb; text-align: center; margin-bottom: 14px; font-style: italic; }

    /* Quick approve/reject confirm modal */
    .confirm-icon { font-size: 36px; display: block; margin: 0 auto 10px; }

    /* ── Pending alert bar ── */
    .alert-pending {
      background: linear-gradient(135deg, #fff8dc, #fff3cd);
      border: 1.5px solid #f5c800; border-radius: 12px;
      padding: 12px 18px; font-size: 13px; color: #7a5c00; font-weight: 600;
      margin-bottom: 18px; display: none; align-items: center; gap: 10px;
    }
    .alert-pending ion-icon { font-size: 18px; }

    /* ── Pagination ── */
    .disc-pagination {
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
    .pg-btn:disabled { opacity: .4; cursor: default; }

    /* ── Toast ── */
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

    /* ── IMAGE ZOOM LIGHTBOX ── */
    #imgZoomOverlay {
      display: none;
      position: fixed; inset: 0;
      background: rgba(0,0,0,0.88);
      z-index: 99999;
      align-items: center;
      justify-content: center;
      cursor: zoom-out;
    }
    #imgZoomOverlay.active { display: flex; }
    #imgZoomBig {
      max-width: 92vw;
      max-height: 90vh;
      object-fit: contain;
      border-radius: 12px;
      box-shadow: 0 16px 60px rgba(0,0,0,0.5);
      /* prevent the overlay click from firing when clicking the image itself */
      pointer-events: none;
    }
    .zoom-close-btn {
      position: absolute; top: 20px; right: 26px;
      color: #fff; font-size: 34px; font-weight: 300;
      cursor: pointer; line-height: 1;
      opacity: 0.75; transition: opacity .15s;
      pointer-events: all;
    }
    .zoom-close-btn:hover { opacity: 1; }
    .zoom-label {
      position: absolute; bottom: 20px; left: 50%;
      transform: translateX(-50%);
      color: rgba(255,255,255,0.45); font-size: 12px;
      pointer-events: none;
    }

    @media (max-width: 900px) { .disc-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) {
      .disc-page { padding: 0 14px 40px; }
      .detail-row { flex-direction: column; gap: 8px; }
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
          <li><a href="inventory.php" class="header_button"><ion-icon name="clipboard-outline"></ion-icon><span>Inventory</span></a></li>
          <li><a href="admin-discount.php" class="header_button active"><ion-icon name="pricetag-outline"></ion-icon><span>Discounts</span></a></li>
          <li><a href="admins-review.php" class="header_button"><ion-icon name="chatbubbles-outline"></ion-icon><span>Reviews</span></a></li>
        </ul>
      </nav>
    </div>
  </div>
</header>

<main class="main-content">
<div class="disc-page">

  <h1>Discount Approvals</h1>
  <p class="disc-subtitle">Review and manage senior citizen, PWD, and student discount applications.</p>

  <!-- Pending alert bar -->
  <div class="alert-pending" id="pendingAlert">
    <ion-icon name="time-outline"></ion-icon>
    <span id="pendingAlertText"></span>
  </div>

  <!-- Stat cards -->
  <div class="disc-stats">
    <div class="disc-stat">
      <div class="disc-stat-icon yellow"><ion-icon name="people-outline"></ion-icon></div>
      <div>
        <div class="disc-stat-label">Total Applications</div>
        <div class="disc-stat-val" id="sTotal"><span class="skeleton" style="width:40px"></span></div>
      </div>
    </div>
    <div class="disc-stat">
      <div class="disc-stat-icon orange"><ion-icon name="time-outline"></ion-icon></div>
      <div>
        <div class="disc-stat-label">Pending</div>
        <div class="disc-stat-val" id="sPending"><span class="skeleton" style="width:40px"></span></div>
      </div>
    </div>
    <div class="disc-stat">
      <div class="disc-stat-icon green"><ion-icon name="checkmark-circle-outline"></ion-icon></div>
      <div>
        <div class="disc-stat-label">Approved</div>
        <div class="disc-stat-val" id="sApproved"><span class="skeleton" style="width:40px"></span></div>
      </div>
    </div>
    <div class="disc-stat">
      <div class="disc-stat-icon red"><ion-icon name="close-circle-outline"></ion-icon></div>
      <div>
        <div class="disc-stat-label">Rejected</div>
        <div class="disc-stat-val" id="sRejected"><span class="skeleton" style="width:40px"></span></div>
      </div>
    </div>
  </div>

  <!-- Toolbar -->
  <div class="disc-toolbar">
    <div class="disc-search">
      <ion-icon name="search-outline" class="search-ico"></ion-icon>
      <input type="text" id="searchInput" placeholder="Search by name or email…" />
    </div>
    <select class="disc-filter-select" id="typeFilter">
      <option value="">All Types</option>
      <option value="Senior Citizen">Senior Citizen</option>
      <option value="PWD">PWD</option>
      <option value="Student">Student</option>
    </select>
    <select class="disc-filter-select" id="statusFilter">
      <option value="">All Status</option>
      <option value="pending">Pending</option>
      <option value="approved">Approved</option>
      <option value="rejected">Rejected</option>
    </select>
  </div>

  <!-- Table -->
  <div class="disc-table-card">
    <table class="disc-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Applicant</th>
          <th>Discount Type</th>
          <th>Submitted</th>
          <th>ID Proof</th>
          <th>Status</th>
          <th>Notes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="discTableBody">
        <tr><td colspan="8" class="disc-empty">
          <span class="skeleton" style="width:60%;display:block;margin:auto;height:18px"></span>
        </td></tr>
      </tbody>
    </table>
    <div class="disc-pagination" id="pagination" style="display:none">
      <span id="pgInfo"></span>
      <div class="pg-btns" id="pgBtns"></div>
    </div>
  </div>

</div>
</main>

<!-- ── VIEW / DECIDE MODAL ── -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Application Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <input type="hidden" id="viewId" />

        <div class="detail-row">
          <div class="detail-field"><label>Full Name</label><span id="vName">—</span></div>
          <div class="detail-field"><label>Email</label><span id="vEmail">—</span></div>
        </div>
        <div class="detail-row">
          <div class="detail-field"><label>Phone</label><span id="vPhone">—</span></div>
          <div class="detail-field"><label>Discount Type</label><span id="vType">—</span></div>
        </div>
        <div class="detail-row">
          <div class="detail-field"><label>Submitted</label><span id="vDate">—</span></div>
          <div class="detail-field"><label>Status</label><span id="vStatus">—</span></div>
        </div>

        <div style="margin-bottom:4px;">
          <label class="form-label">ID / Proof Image</label>
          <!-- clicking the wrap or image opens the lightbox -->
          <div class="id-preview-wrap" onclick="openZoomFromModal()">
            <img id="vImage" src="" alt="ID Proof" />
          </div>
          <p class="id-zoom-hint">Click image to enlarge</p>
        </div>

        <div class="mb-3">
          <label class="form-label">Admin Notes <small style="color:#bbb;font-weight:400;">(optional — shown to user)</small></label>
          <textarea class="form-control" id="vNotes" rows="3" placeholder="e.g. ID was unclear, please resubmit…"></textarea>
        </div>
      </div>
      <div class="modal-footer" id="vFooter">
        <button type="button" class="btn btn-light btn-cancel" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-reject-modal" onclick="decide('rejected')">
          <ion-icon name="close-outline" style="vertical-align:middle;margin-right:4px"></ion-icon> Reject
        </button>
        <button type="button" class="btn btn-approve-modal" onclick="decide('approved')">
          <ion-icon name="checkmark-outline" style="vertical-align:middle;margin-right:4px"></ion-icon> Approve
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ── QUICK APPROVE CONFIRM ── -->
<div class="modal fade" id="quickApproveModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background:#2e7d32;">
        <h5 class="modal-title">Approve Application</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4 text-center">
        <ion-icon name="checkmark-circle-outline" class="confirm-icon" style="color:#2e7d32"></ion-icon>
        <p style="font-size:14px;color:#444;">Approve the <strong id="qaType"></strong> discount for <strong id="qaName"></strong>?</p>
        <input type="hidden" id="qaId" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-approve-modal" onclick="doQuickDecide('approved')">Approve</button>
      </div>
    </div>
  </div>
</div>

<!-- ── QUICK REJECT CONFIRM ── -->
<div class="modal fade" id="quickRejectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background:#c62828;">
        <h5 class="modal-title">Reject Application</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4 text-center">
        <ion-icon name="close-circle-outline" class="confirm-icon" style="color:#c62828"></ion-icon>
        <p style="font-size:14px;color:#444;">Reject the <strong id="qrType"></strong> discount for <strong id="qrName"></strong>?</p>
        <input type="hidden" id="qrId" />
        <div style="text-align:left;margin-top:10px;">
          <label class="form-label">Reason (optional)</label>
          <textarea class="form-control" id="qrNotes" rows="2" placeholder="e.g. ID photo was blurry…"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-reject-modal" onclick="doQuickDecide('rejected')">Reject</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast -->
<div id="toast"></div>

<!-- ── IMAGE ZOOM LIGHTBOX ── -->
<div id="imgZoomOverlay" onclick="closeZoom()">
  <span class="zoom-close-btn" onclick="closeZoom()">✕</span>
  <img id="imgZoomBig" src="" alt="ID enlarged" />
  <span class="zoom-label">Press Esc or click anywhere to close</span>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="admin.js"></script>

<script>
// ── CONFIG ──────────────────────────────────────────────────
const API      = 'discount_process.php';
const PER_PAGE = 10;
let allItems   = [];
let currentPage = 1;
let viewModal, qaModal, qrModal;

// ── INIT ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
  qaModal   = new bootstrap.Modal(document.getElementById('quickApproveModal'));
  qrModal   = new bootstrap.Modal(document.getElementById('quickRejectModal'));

  loadStats();
  loadApplications();

  document.getElementById('searchInput').addEventListener('input', debounce(renderTable, 250));
  document.getElementById('typeFilter').addEventListener('change', renderTable);
  document.getElementById('statusFilter').addEventListener('change', renderTable);

  // Close lightbox on Escape
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeZoom(); });

  // Auto-refresh every 60 s
  setInterval(() => { loadStats(); loadApplications(true); }, 60000);
});

// ── FETCH HELPERS ───────────────────────────────────────────
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

// ── STATS ───────────────────────────────────────────────────
async function loadStats() {
  try {
    const s = await get('stats');
    document.getElementById('sTotal').textContent    = s.total;
    document.getElementById('sPending').textContent  = s.pending;
    document.getElementById('sApproved').textContent = s.approved;
    document.getElementById('sRejected').textContent = s.rejected;

    const alert = document.getElementById('pendingAlert');
    if (s.pending > 0) {
      alert.style.display = 'flex';
      document.getElementById('pendingAlertText').textContent =
        `${s.pending} application${s.pending > 1 ? 's' : ''} awaiting review. Please process them promptly.`;
    } else {
      alert.style.display = 'none';
    }
  } catch(e) { console.error('Stats error', e); }
}

// ── LOAD ────────────────────────────────────────────────────
async function loadApplications(silent = false) {
  try {
    allItems = await get('list');
    currentPage = 1;
    renderTable();
  } catch(e) {
    if (!silent) {
      document.getElementById('discTableBody').innerHTML = `
        <tr><td colspan="8" class="disc-empty">
          <ion-icon name="cloud-offline-outline"></ion-icon>
          Could not connect. Check that XAMPP is running.
        </td></tr>`;
    }
  }
}

// ── RENDER ──────────────────────────────────────────────────
function renderTable() {
  const search = document.getElementById('searchInput').value.toLowerCase();
  const type   = document.getElementById('typeFilter').value;
  const status = document.getElementById('statusFilter').value;

  const filtered = allItems.filter(it => {
    const fullName = (it.first_name + ' ' + it.last_name).toLowerCase();
    if (search && !fullName.includes(search) && !it.email.toLowerCase().includes(search)) return false;
    if (type   && it.type   !== type)   return false;
    if (status && it.status !== status) return false;
    return true;
  });

  const totalPages = Math.max(1, Math.ceil(filtered.length / PER_PAGE));
  if (currentPage > totalPages) currentPage = totalPages;
  const start = (currentPage - 1) * PER_PAGE;
  const page  = filtered.slice(start, start + PER_PAGE);

  const tbody = document.getElementById('discTableBody');

  if (!page.length) {
    tbody.innerHTML = `<tr><td colspan="8" class="disc-empty">
      <ion-icon name="search-outline"></ion-icon>
      No applications match your filters.
    </td></tr>`;
    document.getElementById('pagination').style.display = 'none';
    return;
  }

  tbody.innerHTML = page.map((it, idx) => {
    const badgeCls  = `badge-${it.status}`;
    const badgeTxt  = { pending: 'Pending', approved: 'Approved', rejected: 'Rejected' }[it.status] || it.status;
    const typeKey   = it.type === 'Senior Citizen' ? 'senior' : it.type === 'PWD' ? 'pwd' : 'student';
    const typeEmoji = it.type === 'Senior Citizen' ? '👴' : it.type === 'PWD' ? '♿' : '🎓';

    const notesTxt = it.notes
      ? `<span style="font-size:12px;color:#888;max-width:140px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${esc(it.notes)}">${esc(it.notes)}</span>`
      : `<span style="color:#ddd;font-size:12px;">—</span>`;

    const approveBtn = it.status !== 'approved'
      ? `<button class="btn-row btn-approve" onclick="openQuickApprove(${it.id},'${esc(it.first_name+' '+it.last_name)}','${esc(it.type)}')">
           <ion-icon name="checkmark-outline"></ion-icon> Approve
         </button>` : '';
    const rejectBtn = it.status !== 'rejected'
      ? `<button class="btn-row btn-reject" onclick="openQuickReject(${it.id},'${esc(it.first_name+' '+it.last_name)}','${esc(it.type)}')">
           <ion-icon name="close-outline"></ion-icon> Reject
         </button>` : '';

    // Thumbnail: click zooms the image directly; View button opens the detail modal
    const thumbCell = it.id_image_path
      ? `<img class="id-thumb" src="${esc(it.id_image_path)}" alt="ID"
             onclick="openZoom('${esc(it.id_image_path)}')"
             title="Click to zoom" />`
      : `<span style="color:#ddd;font-size:12px;">No image</span>`;

    return `<tr>
      <td style="color:#bbb;font-size:13px;">${start + idx + 1}</td>
      <td>
        <div class="applicant-name">${esc(it.first_name)} ${esc(it.last_name)}</div>
        <div class="applicant-email">${esc(it.email)}</div>
      </td>
      <td><span class="type-chip ${typeKey}">${typeEmoji} ${esc(it.type)}</span></td>
      <td style="color:#888;font-size:13px;white-space:nowrap;">${formatDate(it.created_at)}</td>
      <td>${thumbCell}</td>
      <td><span class="status-badge ${badgeCls}">${badgeTxt}</span></td>
      <td>${notesTxt}</td>
      <td>
        <div class="row-actions">
          <button class="btn-row btn-view" onclick="openView(${JSON.stringify(it).replace(/"/g,'&quot;')})">
            <ion-icon name="eye-outline"></ion-icon> View
          </button>
          ${approveBtn}
          ${rejectBtn}
        </div>
      </td>
    </tr>`;
  }).join('');

  document.getElementById('pgInfo').textContent =
    `Showing ${start + 1}–${Math.min(start + PER_PAGE, filtered.length)} of ${filtered.length} applications`;

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

// ── VIEW MODAL ──────────────────────────────────────────────
function openView(it) {
  document.getElementById('viewId').value       = it.id;
  document.getElementById('vName').textContent  = it.first_name + ' ' + it.last_name;
  document.getElementById('vEmail').textContent = it.email;
  document.getElementById('vPhone').textContent = it.phone || '—';
  document.getElementById('vType').textContent  = it.type;
  document.getElementById('vDate').textContent  = formatDate(it.created_at);
  document.getElementById('vNotes').value       = it.notes || '';

  const badgeMap = {
    pending:  '<span class="status-badge badge-pending">Pending</span>',
    approved: '<span class="status-badge badge-approved">Approved</span>',
    rejected: '<span class="status-badge badge-rejected">Rejected</span>',
  };
  document.getElementById('vStatus').innerHTML = badgeMap[it.status] || it.status;

  const img = document.getElementById('vImage');
  if (it.id_image_path) {
    img.src = it.id_image_path;
    img.style.display = 'block';
  } else {
    img.style.display = 'none';
  }

  const footer     = document.getElementById('vFooter');
  const approveBtn = footer.querySelector('.btn-approve-modal');
  const rejectBtn  = footer.querySelector('.btn-reject-modal');
  approveBtn.style.display = it.status !== 'approved' ? 'inline-flex' : 'none';
  rejectBtn.style.display  = it.status !== 'rejected' ? 'inline-flex' : 'none';

  viewModal.show();
}

// Opens lightbox using the image already loaded in the modal
function openZoomFromModal() {
  const src = document.getElementById('vImage').src;
  if (src) openZoom(src);
}

// ── DECIDE FROM VIEW MODAL ───────────────────────────────────
async function decide(newStatus) {
  const id    = document.getElementById('viewId').value;
  const notes = document.getElementById('vNotes').value.trim();
  try {
    const res = await post('decide', { id, status: newStatus, notes });
    if (res.success) {
      viewModal.hide();
      toast(`Application ${newStatus} successfully.`, 'success');
      await loadApplications(true);
      await loadStats();
    } else {
      toast(res.error || 'Action failed.', 'error');
    }
  } catch(e) { toast('Network error.', 'error'); }
}

// ── QUICK APPROVE ───────────────────────────────────────────
function openQuickApprove(id, name, type) {
  document.getElementById('qaId').value = id;
  document.getElementById('qaName').textContent = name;
  document.getElementById('qaType').textContent = type;
  qaModal.show();
}

// ── QUICK REJECT ────────────────────────────────────────────
function openQuickReject(id, name, type) {
  document.getElementById('qrId').value = id;
  document.getElementById('qrName').textContent = name;
  document.getElementById('qrType').textContent = type;
  document.getElementById('qrNotes').value = '';
  qrModal.show();
}

async function doQuickDecide(newStatus) {
  const isApprove = newStatus === 'approved';
  const id    = document.getElementById(isApprove ? 'qaId' : 'qrId').value;
  const notes = isApprove ? '' : document.getElementById('qrNotes').value.trim();
  try {
    const res = await post('decide', { id, status: newStatus, notes });
    if (res.success) {
      (isApprove ? qaModal : qrModal).hide();
      toast(`Application ${newStatus} successfully.`, 'success');
      await loadApplications(true);
      await loadStats();
    } else {
      toast(res.error || 'Action failed.', 'error');
    }
  } catch(e) { toast('Network error.', 'error'); }
}

// ── IMAGE ZOOM LIGHTBOX ──────────────────────────────────────
function openZoom(src) {
  document.getElementById('imgZoomBig').src = src;
  document.getElementById('imgZoomOverlay').classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeZoom() {
  document.getElementById('imgZoomOverlay').classList.remove('active');
  document.body.style.overflow = '';
}

// ── UTILS ────────────────────────────────────────────────────
function esc(str) {
  return String(str)
    .replace(/&/g,'&amp;').replace(/</g,'&lt;')
    .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function debounce(fn, ms) {
  let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
}

function formatDate(str) {
  if (!str) return '—';
  const d = new Date(str);
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

let toastTimer;
function toast(msg, type = 'success') {
  const el = document.getElementById('toast');
  el.textContent = msg;
  el.className   = type;
  el.style.display = 'block';
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => { el.style.display = 'none'; }, 3000);
}
</script>
</body>
</html>