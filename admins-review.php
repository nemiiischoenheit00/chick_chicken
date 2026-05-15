<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="icon" type="image/png" href="assets/Logo.png" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Swiper -->
  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">

  <!-- Admin CSS -->
  <link rel="stylesheet" href="admin.css">

  <title>Chick Chicken - Reviews</title>

  <style>

    /* ───────────────────────────────────── */
    /* MAIN CONTENT                          */
    /* ───────────────────────────────────── */

    .main-content {
      padding: 0 28px 40px 28px;
      background: #f8f9fb;
      min-height: 100vh;
    }

    /* Match inventory sidebar logo h1 exactly — no extra margin */
    .sidebar h1 {
      margin: 0;
      padding: 0;
      font-size: unset;
      font-weight: unset;
      color: unset;
      font-family: unset;
    }

    .main-content h1 {
      font-family: "Oswald", sans-serif;
      font-size: 2rem;
      font-weight: 600;
      color: #1a1a1a;
      margin-bottom: 6px;
      margin-top: 30px;
    }

    .reviews-subtitle {
      color: #888;
      font-size: 13px;
      margin-bottom: 24px;
    }

    /* ───────────────────────────────────── */
    /* STATS ROW                             */
    /* ───────────────────────────────────── */

    .reviews-stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px;
      margin-bottom: 24px;
    }

    .reviews-stat {
      background: #fff;
      border-radius: 14px;
      padding: 18px 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,.06);
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .reviews-stat-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0;
    }

    .reviews-stat-icon.yellow { background: #fff8dc; color: #e0a800; }
    .reviews-stat-icon.green  { background: #e8f5e9; color: #2e7d32; }
    .reviews-stat-icon.orange { background: #fff3e0; color: #e65c00; }
    .reviews-stat-icon.blue   { background: #e3f2fd; color: #1565c0; }

    .reviews-stat-label {
      font-size: 11px;
      color: #999;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .5px;
    }

    .reviews-stat-val {
      font-size: 22px;
      font-weight: 800;
      color: #1a1a1a;
      line-height: 1.1;
      font-family: 'Oswald', sans-serif;
    }

    /* ───────────────────────────────────── */
    /* TOOLBAR                               */
    /* ───────────────────────────────────── */

    .reviews-toolbar {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 18px;
      flex-wrap: wrap;
    }

    .reviews-search {
      flex: 1;
      min-width: 200px;
      position: relative;
    }

    .reviews-search input {
      width: 100%;
      border: 1.5px solid #e8e8e8;
      border-radius: 10px;
      padding: 9px 14px 9px 38px;
      font-size: 14px;
      outline: none;
      background: #fff;
      transition: border .2s;
    }

    .reviews-search input:focus { border-color: #f5c800; }

    .reviews-search .search-ico {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #bbb;
      font-size: 16px;
      pointer-events: none;
    }

    .reviews-filter-select {
      border: 1.5px solid #e8e8e8;
      border-radius: 10px;
      padding: 9px 14px;
      font-size: 13px;
      background: #fff;
      outline: none;
      cursor: pointer;
      transition: border .2s;
    }

    .reviews-filter-select:focus { border-color: #f5c800; }

    /* ───────────────────────────────────── */
    /* REVIEW CARD                           */
    /* ───────────────────────────────────── */

    #reviewsContainer {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .review-card {
      background: #fff;
      border-radius: 16px;
      padding: 26px;
      box-shadow: 0 2px 10px rgba(0,0,0,.06);
      transition: 0.25s ease;
      border: 1px solid #f3f3f3;
    }

    .review-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0,0,0,.09);
    }

    .review-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 14px;
      gap: 15px;
    }

    .review-info {
      display: flex;
      flex-direction: column;
    }

    .review-name {
      font-size: 16px;
      font-weight: 700;
      color: #1a1a1a;
    }

    .review-rating {
      color: #f5b800;
      font-size: 18px;
      margin-top: 4px;
      letter-spacing: 1px;
    }

    .review-date {
      font-size: 12px;
      color: #999;
      margin-top: 4px;
    }

    .review-text {
      font-size: 14px;
      color: #555;
      line-height: 1.7;
      margin-top: 10px;
      margin-bottom: 0;
    }

    /* ───────────────────────────────────── */
    /* DELETE BUTTON                         */
    /* ───────────────────────────────────── */

    .btn-delete {
      background: #fce4ec;
      color: #c62828;
      border: none;
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: 0.2s ease;
      display: flex;
      align-items: center;
      gap: 5px;
      white-space: nowrap;
    }

    .btn-delete:hover {
      background: #c62828;
      color: #fff;
      transform: translateY(-1px);
    }

    /* ───────────────────────────────────── */
    /* EMPTY STATE                           */
    /* ───────────────────────────────────── */

    .no-reviews {
      text-align: center;
      color: #bbb;
      padding: 60px 20px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 10px rgba(0,0,0,.05);
      font-size: 14px;
    }

    .no-reviews ion-icon {
      font-size: 40px;
      display: block;
      margin: 0 auto 10px;
    }

    /* ───────────────────────────────────── */
    /* ERROR BANNER                          */
    /* ───────────────────────────────────── */

    .db-error {
      background: #fff3cd;
      border: 1.5px solid #ffc107;
      border-radius: 12px;
      padding: 14px 18px;
      font-size: 13px;
      color: #856404;
      font-weight: 600;
      margin-bottom: 20px;
      display: none;
      align-items: center;
      gap: 10px;
    }

    /* ───────────────────────────────────── */
    /* SKELETON LOADING                      */
    /* ───────────────────────────────────── */

    .skeleton {
      display: inline-block;
      width: 100%;
      height: 1.2em;
      background: linear-gradient(90deg, #e0e0e0 25%, #f5f5f5 50%, #e0e0e0 75%);
      background-size: 200% 100%;
      animation: shimmer 1.2s infinite;
      border-radius: 6px;
      vertical-align: middle;
    }

    @keyframes shimmer {
      0%   { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }

    /* ───────────────────────────────────── */
    /* RESPONSIVE                            */
    /* ───────────────────────────────────── */

    @media (max-width: 900px) {
      .reviews-stats { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
      .review-header {
        flex-direction: column;
        align-items: flex-start;
      }
      .btn-delete { width: 100%; justify-content: center; }
    }
  </style>
</head>

<body>

  <!-- SIDEBAR -->
  <header>
    <div class="sidebar">

      <div class="logo">
        <h1>
          <a href="admin.php">
            <img src="assets/Logo2.png" alt="ChickChicken"
              style="width: auto; height: 55px" />
          </a>
        </h1>
      </div>

      <div class="navigation--admin">
        <nav>
          <ul>

            <li>
              <a href="admin.php#dashboard--admin" class="header_button">
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

            <li>
              <a href="admin-reviews.php" class="header_button active">
                <ion-icon name="chatbubbles-outline"></ion-icon>
                <span>Reviews</span>
              </a>
            </li>

          </ul>
        </nav>
      </div>
      <div class="sidebar-logout-wrap">
          <a href="logout_process.php" class="btn-logout">
              <ion-icon name="log-out-outline"></ion-icon>
              <span>Logout</span>
          </a>
      </div>
    </div>
  </header>

  <!-- MAIN CONTENT -->
  <main class="main-content">

    <section id="reviews--admin" class="page-content active">

      <h1>Customer Reviews</h1>
      <p class="reviews-subtitle">View and manage all customer feedback submitted from the website.</p>

      <!-- STATS -->
      <div class="reviews-stats" id="reviewsStats" style="display:none">
        <div class="reviews-stat">
          <div class="reviews-stat-icon yellow"><ion-icon name="chatbubbles-outline"></ion-icon></div>
          <div>
            <div class="reviews-stat-label">Total Reviews</div>
            <div class="reviews-stat-val" id="statTotal">—</div>
          </div>
        </div>
        <div class="reviews-stat">
          <div class="reviews-stat-icon green"><ion-icon name="star-outline"></ion-icon></div>
          <div>
            <div class="reviews-stat-label">Avg. Rating</div>
            <div class="reviews-stat-val" id="statAvg">—</div>
          </div>
        </div>
        <div class="reviews-stat">
          <div class="reviews-stat-icon orange"><ion-icon name="star-half-outline"></ion-icon></div>
          <div>
            <div class="reviews-stat-label">5-Star Reviews</div>
            <div class="reviews-stat-val" id="statFive">—</div>
          </div>
        </div>
        <div class="reviews-stat">
          <div class="reviews-stat-icon blue"><ion-icon name="trending-up-outline"></ion-icon></div>
          <div>
            <div class="reviews-stat-label">1–2 Star Reviews</div>
            <div class="reviews-stat-val" id="statLow">—</div>
          </div>
        </div>
      </div>

      <!-- TOOLBAR -->
      <div class="reviews-toolbar">
        <div class="reviews-search">
          <ion-icon name="search-outline" class="search-ico"></ion-icon>
          <input type="text" id="searchInput" placeholder="Search reviews…" oninput="filterReviews()" />
        </div>
        <select class="reviews-filter-select" id="ratingFilter" onchange="filterReviews()">
          <option value="">All Ratings</option>
          <option value="5">★★★★★ 5 Stars</option>
          <option value="4">★★★★☆ 4 Stars</option>
          <option value="3">★★★☆☆ 3 Stars</option>
          <option value="2">★★☆☆☆ 2 Stars</option>
          <option value="1">★☆☆☆☆ 1 Star</option>
        </select>
      </div>

      <!-- DB ERROR -->
      <div class="db-error" id="dbError">
        ⚠️ Could not connect to the database.
        Make sure XAMPP is running and
        <code>reviews-api.php</code> is configured.
      </div>

      <!-- REVIEWS -->
      <div id="reviewsContainer">

        <!-- Skeleton Loader -->
        <div class="review-card">
          <p><span class="skeleton"></span></p>
          <p><span class="skeleton" style="width:60%"></span></p>
          <p><span class="skeleton" style="width:80%"></span></p>
        </div>
        <div class="review-card">
          <p><span class="skeleton"></span></p>
          <p><span class="skeleton" style="width:50%"></span></p>
          <p><span class="skeleton" style="width:90%"></span></p>
        </div>

      </div>

    </section>

  </main>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

  <!-- Ionicons -->
  <script type="module"
    src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>

  <script nomodule
    src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <!-- Swiper -->
  <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

  <!-- Reviews Script -->
  <script>

    let allReviews = [];

    async function loadReviews() {

      try {

        const res = await fetch('reviews-api.php?action=list');

        if (!res.ok) {
          throw new Error("HTTP " + res.status);
        }

        allReviews = await res.json();

        // Compute stats
        if (allReviews.length) {
          const total = allReviews.length;
          const avg   = (allReviews.reduce((s, r) => s + parseInt(r.rating), 0) / total).toFixed(1);
          const five  = allReviews.filter(r => parseInt(r.rating) === 5).length;
          const low   = allReviews.filter(r => parseInt(r.rating) <= 2).length;

          document.getElementById('statTotal').textContent = total;
          document.getElementById('statAvg').textContent   = avg + ' ★';
          document.getElementById('statFive').textContent  = five;
          document.getElementById('statLow').textContent   = low;
          document.getElementById('reviewsStats').style.display = 'grid';
        }

        renderReviews(allReviews);

      } catch (e) {

        console.error("Review load error:", e);
        document.getElementById('dbError').style.display = 'flex';
        document.getElementById('reviewsContainer').innerHTML = '';
      }
    }

    function filterReviews() {
      const search = document.getElementById('searchInput').value.toLowerCase();
      const rating = document.getElementById('ratingFilter').value;

      const filtered = allReviews.filter(r => {
        if (search && !r.name.toLowerCase().includes(search) && !r.review.toLowerCase().includes(search)) return false;
        if (rating && parseInt(r.rating) !== parseInt(rating)) return false;
        return true;
      });

      renderReviews(filtered);
    }

    function renderReviews(reviews) {
      const container = document.getElementById('reviewsContainer');

      if (!reviews.length) {
        container.innerHTML = `
          <div class="no-reviews">
            <ion-icon name="chatbubbles-outline"></ion-icon>
            No reviews match your filters.
          </div>`;
        return;
      }

      container.innerHTML = reviews.map(r => `

        <div class="review-card" data-id="${r.id}">

          <div class="review-header">

            <div class="review-info">

              <span class="review-name">
                ${escapeHtml(r.name)}
              </span>

              <span class="review-rating">
                ${'★'.repeat(r.rating)}${'☆'.repeat(5 - r.rating)}
              </span>

              <span class="review-date">
                ${new Date(r.created_at).toLocaleDateString(
                  'en-PH',
                  { month: 'short', day: 'numeric', year: 'numeric' }
                )}
              </span>

            </div>

            <button class="btn-delete" onclick="deleteReview(${r.id})">
              <ion-icon name="trash-outline"></ion-icon>
              Delete
            </button>

          </div>

          <p class="review-text">
            ${escapeHtml(r.review)}
          </p>

        </div>

      `).join('');
    }

    async function deleteReview(id) {

      if (!confirm('Delete this review?')) return;

      await fetch(`reviews-api.php?action=delete&id=${id}`);

      loadReviews();
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    loadReviews();

  </script>

</body>

</html>